<?php
   	include("connect.php");
   	
   	$link=Connection();

   $heart_rate=$_POST["heart_rate"];
	$user_name=$_POST["user_name"];
	
	$query = "INSERT INTO `podaci` (`heart_rate`, `user_name`) 
		VALUES ('".$heart_rate."','".$user_name."')"; 
   	
   	mysql_query($query,$link);
	   mysql_close($link);

   	header("Location: index.php");
?>
