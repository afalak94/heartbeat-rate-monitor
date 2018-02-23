<?php

session_start();

if (isset($_POST['submit'])) {
	include '../connect.php';

	$user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);

	//Check if inputs are empty
	if (empty($user_name) || empty($password)) {
		header("Location: ../index.php?login=empty");
		exit();
	} else {
		$sql = "SELECT * FROM korisnik WHERE user_name='$user_name'";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			header("Location: ../index.php?login=error1");
			exit();
		} else {
			if ($row = mysqli_fetch_assoc($result)) {

				if ($password != $row['password']) {
					header("Location: ../index.php?login=error2");
					exit();
				} elseif ($password == $row['password']) {
					//log in the user
					$_SESSION['user_name'] = $row['user_name'];
					$_SESSION['password'] = $row['password'];
					$_SESSION['weight'] = $row['weight'];
					$_SESSION['age'] = $row['age'];
					header("Location: ../index.php?login=success");
					exit();
				}
			}
		}
	}
} else {
	header("Location: ../index.php?login=error");
	exit();
}