#include <SPI.h>
#include <SD.h>
#include <Ethernet.h>


byte mac[] = { 0x02, 0xAA, 0xBB, 0xCC, 0xDE, 0x02 }; //MAC address
IPAddress ip(192, 168, 0, 60); //Arduino IP address
EthernetServer server(80);
EthernetClient client;

int pulsePin = 0;                 // Analog pin 0 of puls sensor

volatile int BPM;                   // int that holds raw Analog in 0. updated every 2mS
volatile int Signal;                // holds the incoming raw data
volatile int IBI = 600;             // int that holds the time interval between beats! Must be seeded! 
volatile boolean Pulse = false;     // True kada ima pulsa na senzoru 
volatile boolean QS = false;        // True kada arduino prepozna beat

volatile int rate[10];                      // array to hold last ten IBI values
volatile unsigned long sampleCounter = 0;          // used to determine pulse timing
volatile unsigned long lastBeatTime = 0;           // used to find IBI
volatile int P = 512;                      // used to find peak in pulse wave, seeded
volatile int T = 512;                     // used to find trough in pulse wave, seeded
volatile int thresh = 525;                // used to find instant moment of heart beat, seeded
volatile int amp = 100;                   // used to hold amplitude of pulse waveform, seeded
volatile boolean firstBeat = true;        // used to seed rate array so we startup with reasonable BPM
volatile boolean secondBeat = false;      // used to seed rate array so we startup with reasonable BPM

File mySensorData;
File webFile;
File TotalCounter;
int counter=0;
int tc=0;



void setup()
{
  Serial.begin(115200);
  Ethernet.begin(mac, ip);
  server.begin(); 
  Serial.print("My IP address: ");
  Serial.print(ip);
  Serial.println();
    
    if (!SD.begin(4)) {
        Serial.println("ERROR - SD card initialization failed!");
        return;
    }
  Serial.println("SUCCESS - SD card initialized.");
    
  if (!SD.exists("index.htm")) {
         Serial.println("ERROR - Can't find index.htm file!");
         return;
    }
    Serial.println("SUCCESS - Found index.htm file.");
    
  if (!SD.exists("tc.txt")) {
         Serial.println("ERROR - Can't find tc.txt file!");
         return;
    }  
   Serial.println("SUCCESS - Found tc.txt file.");

  TotalCounter = SD.open("tc.txt");
  
  if (TotalCounter) {
      while (TotalCounter.available()) {
        tc = TotalCounter.read();
        Serial.println ("Total Counter: ");
        Serial.print (tc);
       }
  TotalCounter.close();
  }
  
  if (tc == 49){
    TotalCounter = SD.open("TC.txt", FILE_WRITE);
    TotalCounter.print("2");
    TotalCounter.close();
    }
  else if (tc == 50) {
    SD.remove ("TC.txt");
    TotalCounter = SD.open("TC.txt", FILE_WRITE);
    TotalCounter.print("1");
    TotalCounter.close();
    
    SD.remove ("index.htm");
    webFile = SD.open("index.htm", FILE_WRITE);
    webFile.close();
    }
    
  
  
     
  webFile = SD.open("index.htm", FILE_WRITE);
  webFile.println();
  webFile.print("<p> ");
  webFile.close();

Serial.println();
Serial.println();
interruptSetup();

}

void loop()
{
  if (QS == true) // A Heartbeat Was Found
      {     
          if(BPM>60 && BPM<150){
            
            Serial.print("BPM: ");
            Serial.println(BPM);
            webFile = SD.open("index.htm", FILE_WRITE);
            if(webFile){  
              webFile.print("-");     
              webFile.print(BPM);
              webFile.close();
              counter=0;
              }
                           
            QS = false; 
          } 
      }
    else {
      Serial.println("Put your finger on sensor.");
      counter++;
    }
    delay(1000);
    if(counter==10){
      webFile = SD.open("index.htm", FILE_WRITE);
      webFile.print("</p>");
      webFile.println("</body>");
      webFile.println("</html>");
      webFile.close();
      counter=0;
    }
     
createServer();    
}


void interruptSetup()
{     
  // Initializes Timer2 to throw an interrupt every 2mS.
  TCCR2A = 0x02;     // DISABLE PWM ON DIGITAL PINS 3 AND 11, AND GO INTO CTC MODE
  TCCR2B = 0x06;     // DON'T FORCE COMPARE, 256 PRESCALER 
  OCR2A = 0X7C;      // SET THE TOP OF THE COUNT TO 124 FOR 500Hz SAMPLE RATE
  TIMSK2 = 0x02;     // ENABLE INTERRUPT ON MATCH BETWEEN TIMER2 AND OCR2A
  sei();             // MAKE SURE GLOBAL INTERRUPTS ARE ENABLED      
} 


ISR(TIMER2_COMPA_vect) //triggered when Timer2 counts to 124
{  
  cli();                                      // disable interrupts while we do this
  Signal = analogRead(pulsePin);              // read the Pulse Sensor 
  sampleCounter += 2;                         // keep track of the time in mS with this variable
  int N = sampleCounter - lastBeatTime;       // monitor the time since the last beat to avoid noise
                                              //  find the peak and trough of the pulse wave
  if(Signal < thresh && N > (IBI/5)*3) // avoid dichrotic noise by waiting 3/5 of last IBI
    {      
      if (Signal < T) // T is the trough
      {                        
        T = Signal; // keep track of lowest point in pulse wave 
      }
    }

  if(Signal > thresh && Signal > P)
    {          // thresh condition helps avoid noise
      P = Signal;                             // P is the peak
    }                                        // keep track of highest point in pulse wave

  //  NOW IT'S TIME TO LOOK FOR THE HEART BEAT
  // signal surges up in value every time there is a pulse
  if (N > 250)
  {                                   // avoid high frequency noise
    if ( (Signal > thresh) && (Pulse == false) && (N > (IBI/5)*3) )
      {        
        Pulse = true;                               // set the Pulse flag when we think there is a pulse
        IBI = sampleCounter - lastBeatTime;         // measure time between beats in mS
        lastBeatTime = sampleCounter;               // keep track of time for next pulse
  
        if(secondBeat)
        {                        // if this is the second beat, if secondBeat == TRUE
          secondBeat = false;                  // clear secondBeat flag
          for(int i=0; i<=9; i++) // seed the running total to get a realisitic BPM at startup
          {             
            rate[i] = IBI;                      
          }
        }
  
        if(firstBeat) // if it's the first time we found a beat, if firstBeat == TRUE
        {                         
          firstBeat = false;                   // clear firstBeat flag
          secondBeat = true;                   // set the second beat flag
          sei();                               // enable interrupts again
          return;                              // IBI value is unreliable so discard it
        }   
      // keep a running total of the last 10 IBI values
      word runningTotal = 0;                  // clear the runningTotal variable    

      for(int i=0; i<=8; i++)
        {                // shift data in the rate array
          rate[i] = rate[i+1];                  // and drop the oldest IBI value 
          runningTotal += rate[i];              // add up the 9 oldest IBI values
        }

      rate[9] = IBI;                          // add the latest IBI to the rate array
      runningTotal += rate[9];                // add the latest IBI to runningTotal
      runningTotal /= 10;                     // average the last 10 IBI values 
      BPM = 60000/runningTotal;               // how many beats can fit into a minute? that's BPM!
      QS = true;                              // set Quantified Self flag 
      // QS FLAG IS NOT CLEARED INSIDE THIS ISR
    }                       
  }

  if (Signal < thresh && Pulse == true)
    {   // when the values are going down, the beat is over
        // turn off pin 13 LED
      Pulse = false;                         // reset the Pulse flag so we can do it again
      amp = P - T;                           // get amplitude of the pulse wave
      thresh = amp/2 + T;                    // set thresh at 50% of the amplitude
      P = thresh;                            // reset these for next time
      T = thresh;
    }

  if (N > 2500)
    {                           // if 2.5 seconds go by without a beat
      thresh = 512;                          // set thresh default
      P = 512;                               // set P default
      T = 512;                               // set T default
      lastBeatTime = sampleCounter;          // bring the lastBeatTime up to date        
      firstBeat = true;                      // set these to avoid noise
      secondBeat = false;                    // when we get the heartbeat back
    }

  sei();                                   // enable interrupts when youre done!
}// end isr



void createServer(){

EthernetClient client = server.available();  // try to get client

    if (client) {  
        boolean currentLineIsBlank = true;
        while (client.connected()) {
            if (client.available()) {   // client data available to read
                char c = client.read(); // read 1 byte (character) from client
                // last line of client request is blank and ends with \n
                // respond to client only after last line received
                if (c == '\n' && currentLineIsBlank) {
                    // send a standard http response header
                    client.println("HTTP/1.1 200 OK");
                    client.println("Content-Type: text/html");
                    client.println("Connection: close");
                    client.println();
                    // send web page
                    webFile = SD.open("index.htm");        // open web page file
                    if (webFile) {
                        while(webFile.available()) {
                            client.write(webFile.read()); // send web page to client
                        }
                        webFile.close();
                    }
                    break;
                }
                // every line of text received from the client ends with \r\n
                if (c == '\n') {
                    // last character on line of received text
                    // starting new line with next character read
                    currentLineIsBlank = true;
                } 
                else if (c != '\r') {
                    // a text character was received from client
                    currentLineIsBlank = false;
                }
            } // end if (client.available())
        } // end while (client.connected())
        delay(1);      // give the web browser time to receive the data
        client.stop(); // close the connection
    } // end if (client)
}



