<?php
	include("connect.php");
    session_start();
    $url = 'http://192.168.0.60';
 
    //Use file_get_contents to GET the URL in question.
    $contents = file_get_contents($url);
    //file_put_contents($url, ""); //brisanje
    //If $contents is not a boolean FALSE value.
    $time = new DateTime(date("Y-m-d h:i:s"));
    //$start_time = $time; //pocetno vrijeme
    $counter = 0;
    $HRdata = array();

    if($contents !== false){
     	//Print out the contents.
     	//echo $contents;
    	$data = explode("-", $contents);
    	foreach($data as $data){
    		if($data > 50 && $data < 160){
                $stamp = $time->format('Y-m-d H:i:s');
    			$sql = "INSERT INTO podaci (`heart_rate`, `user_name`, `time`) VALUES ('$data', 'Pero123', '$stamp')";
    			$result = mysqli_query($conn, $sql);
                $time->add(new DateInterval('PT' . 1 . 'S'));
                //print_r($time);
                $counter++;
                $HRdata[] = $data;
    		} 
    	}
        $training_time = round($counter / 60);
    }

    $time_sql = "INSERT INTO trainings (user_name, training_time)
                VALUES ('Pero123', '$training_time')";
    $result = mysqli_query($conn, $time_sql);

    $a = array_filter($HRdata);
    $average = array_sum($a)/count($a);

?>
