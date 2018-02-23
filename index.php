<?php
	include("connect.php");
	//include("getpage.php"); 
	include_once 'header.php';

  //SQL queries
  $user =  $_SESSION['user_name'];
	$result=mysqli_query($conn, "SELECT * FROM `podaci`");
  $korisnici = mysqli_query($conn, "SELECT * FROM korisnik WHERE user_name = '$user'");
  $korisnik_row = mysqli_fetch_array($korisnici, MYSQLI_ASSOC);

  $trainings = mysqli_query($conn, "SELECT * FROM trainings ORDER BY id DESC LIMIT 1");
	$trainings_row = mysqli_fetch_array($trainings, MYSQLI_ASSOC);
?>


<div class="container" style="padding-top: 50px;">
	<?php if (isset($_SESSION['user_name'])) {
    	echo '<h1 style="text-align: center; margin-top: 20px;">' . $_SESSION['user_name'] . '</h1>';
	}
    else {
    	echo '<h1 style="text-align: center; margin-top: 20px;">Log in to view your heartbeat rate information</h1>';
    } ?>
</div>

<style>
  .button {
    background-color: #008CBA; /* Blue */
    border: none;
    color: white;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 20px;
    width: 250px;
    height: 40px;
  }
</style>

<!-- LINEGRAPH -->
  <div id="graph1" class="container" style="float: left; width: 650px; height: 270px;">
  	<div class="row align-items-start">
  		<div class="col-sm">
        <?php include 'getdata1.php'; include 'linegraph2.html'; ?>
    	</div>
    </div>
  </div>


<!-- CALCULATIONS -->
<script type="text/javascript" src="includes/calculations.js"></script>
  <div class="well well-lg" style="margin: 0 10px 0 650px; height: 300px;">
    <div class="row">
      <div class="col-sm-3">
        <div class="container-fluid">

        <form id="form" method="post" onsubmit="calculateTotal();return false">
  
        <span style="font-size: 24px">Training time(T):</span><br>
        <input type="text" name="training_time" id="training_time" value="<?php echo $trainings_row['training_time']; ?>" style="height: 40px; width: 250px; font-size: 22px;"><br><br><br>
        <span style="font-size: 24px ">Age(A):</span><br>
        <input type="text" name="age" id="age" value="<?php echo $korisnik_row['age']; ?>" style="height: 40px; width: 250px; font-size: 22px;"><br><br><br> &emsp;
        <input type="radio" name="gender" value="male" checked><span style="font-size: 20">Male</span> &emsp;
        <input type="radio" name="gender" value="female"><span style="font-size: 20">Female</span>

        
        </div>
    </div>

    <div class="col-sm-3">
    <div class="container-fluid" style="width: 250px; float: center;">

    
      <span style="font-size: 24px">Weight(W)[kg]:</span><br>
      <input align="right" type="text" name="weight" id="weight" value="<?php echo $korisnik_row['weight']; ?>" style="height: 40px; width: 250px; font-size: 22px;"><br><br><br>
      <span style="font-size: 24px">Calories burned:</span><br>
      <input type="text" name="calories" id="calories" style="height: 40px; width: 250px; font-size: 22px;"><br><br><br>
   

    </div>
    </div>

    <div class="col-sm-3">
    <div class="container-fluid" style="width: 250px; float: right;">

    
      <span style="font-size: 24px">Power[Watts]:</span><br>
      <input align="right" type="text" name="power" id="power" style="height: 40px; width: 250px; font-size: 22px;"><br><br><br>
      <span style="font-size: 24px">Watts/kg:</span><br>
      <input type="text" name="watts" id="watts" style="height: 40px; width: 250px; font-size: 22px"><br><br><br>

      <button type="submit" class="button" name="submit">Calculate</button>
    </form>

    </div>
    </div>
    </div>
  </div>


<!-- BARGRAPH -->
  <div id="graph2" class="container" style="float: left; width: 650px; margin-top: 100px;" >
  	<div class="row align-items-start">
  		<div class="col-sm">
  			<?php include 'getdata2.php'; include 'bargraph.js'; ?>
    	</div>
    </div>
  </div>

<!-- PULSE -->
  <div id="heartbeat" class="container" style="float: left; width: 650px; margin-top: 100px;" >
    <div class="row align-items-start">
      <div class="col-sm">
        <form id="hbr">
        <label style="font-size: 24px; color: blue; margin-left: 60px;">Mean Heartbeat from last exercise:</label>
        <input type="text" name="HB" id="HB" value="<?php echo round($average); ?>" style="height: 40px; width: 100px; font-size: 24px;">
        </form>
        <?php include 'heartbeat.html'; ?>
      </div>
    </div>
  </div>

<!-- FORMULAS -->
  <div class="well well-lg" style="margin: 50px 10px 0 1300px; height: 440px;">
    <h3>Mathematical formulas</h3><br>
    <h4 style="margin-bottom: -10px;">For Male:</h4><br>
    <span style="color: blue; font-size: 18;">Calorie burned = ((-55.0969 + (0.6309 x HR) + (0.1988 x W) + (0.2017 x A)) / 4.184) x 60 x T </span><br>
    <h4 style="margin-bottom: -10px;">For Female:</h4><br>
    <span style="color: blue; font-size: 18;">Calorie burned = ((-20.4022 + (0.4472 x HR) + (0.1263 x W) + (0.074 x A)) / 4.184) x 60 x T </span><br>

    <h4 style="color: grey;">Where:</h4>
    <h4 style="color: grey;">HR = Heart Rate</h4>
    <h4 style="color: grey;">W = Weight in pounds</h4>
    <h4 style="color: grey;">A = Age</h4>
    <h4 style="color: grey;">T = Exercise duration time in hours</h4>
  </div>

</body>
</html>