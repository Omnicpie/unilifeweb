<?php 
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
	##	header("location:index"); // redirects if user is not logged in
	}
	$user = $_SESSION['user']; //assigns user value
	//ALL THIS IS TO GET THE USER'S FIRST NAME
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
  sendtoServer($soc, $pair, "messend", $user, $_POST['chatid'], $_POST['message']);
  sendtoServer($soc, $pair, "quit");
  header("location:chat.php?chatid=".$_POST['chatid']."&d=".$_POST['message']);
?>