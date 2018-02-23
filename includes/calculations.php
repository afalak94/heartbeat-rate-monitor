<?php

if (isset($_POST['submit'])) {
	include_once '../connect.php';
	include 'calculations.js';
	
	$training_time = mysqli_real_escape_string($conn, $_POST['training_time']);
	#$age = mysqli_real_escape_string($conn, $_POST['age']);
	#$gender = mysqli_real_escape_string($conn, $_POST['gender']);
	#$weight = mysqli_real_escape_string($conn, $_POST['weight']);
	#$calories = mysqli_real_escape_string($conn, $_POST['calories']);
	#$power = mysqli_real_escape_string($conn, $_POST['power']);
	#$watts = mysqli_real_escape_string($conn, $_POST['watts']);


}
