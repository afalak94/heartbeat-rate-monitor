<?php

if (isset($_POST['submit'])) {
	include_once '../connect.php';

	$user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$weight = mysqli_real_escape_string($conn, $_POST['weight']);
	$age = mysqli_real_escape_string($conn, $_POST['age']);
	$gender = mysqli_real_escape_string($conn, $_POST['gender']);
	//$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	//Error handlers
	//Check for empty fields
	if (empty($user_name) || empty($password) || empty($weight) || empty($age)) {
		header("Location: ../signup.php?signup=empty");
		exit();
	} else {
		//Check if input chars are valid
		if (!preg_match("/^[a-zA-Z0-9]*$/", $user_name) || !preg_match("/^[0-9]*$/", $weight) || !preg_match("/^[0-9]*$/", $age)) {
			header("Location: ../signup.php?signup=invalid");
			exit();
		} else {
			//Check if email is valid
			
			$sql = "SELECT * FROM korisnik WHERE user_name='$user_name'";
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);
			if ($resultCheck > 0) {
				header("Location: ../signup.php?signup=usertaken");
				exit();
			} else {
				//hashing the pwd
				//$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
				//insert the user into the db
				$sql = "INSERT INTO korisnik (user_name, password, weight, age, sex) VALUES ('$user_name', '$password', '$weight', '$age', '$gender');";
				mysqli_query($conn, $sql);
				header("Location: ../index.php?signup=success");
				exit();
			}
			
		}
	}
} else {
	header("Location: ../signup.php");
	exit();
}