<?php

if (isset($_POST['submit'])) {
	session_start();
	session_unset();
	session_write_close();
	session_destroy();
	header("Location: ../index.php");
	exit();
}