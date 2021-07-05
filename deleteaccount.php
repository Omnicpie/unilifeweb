<?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
		header("location:index.php"); // redirects if user is not logged in
	}
	$user = $_SESSION['user']; //assigns user value
include 'servercom.php';
$soc = setupServer();
$pair = sendAES($soc);
sendtoServer($soc, $pair, "delaccount", $user);
sendtoServer($soc, $pair, "quit");
header("location: logout.php");
?>