<?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
		header("location:index.php"); // redirects if user is not logged in
	}
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	if($_SERVER['REQUEST_METHOD'] == "GET")
	{
		sendtoServer($soc, $pair, "del", $_GET['id']);
		sendtoServer($soc, $pair, "quit", "");
		header("location:" . $_GET['curpage']);
	}
?>
