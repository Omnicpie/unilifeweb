<?php
//AUTHOR: Ryan Anderson
//Authenicates user and logs in if valid.

	session_start();
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	//retrieve email and password
	$email = $_POST['email'];
	$pass = $_POST['password'];
	//checks login valid
 $browser = get_browser(null, true);
	sendtoServer($soc, $pair, "auth", $email, $pass, $browser['browser'], $browser['platform']);
	$result = serverRead($soc, $pair);
	if ($result == "Y"){
		//gets userid for sess
		sendtoServer($soc, $pair, "userid", $email);
		$user = serverRead($soc, $pair);
		sendtoServer($soc, $pair, "quit", "");
		$_SESSION['user'] = $user; //set the user id in a session.
    if(isset($_COOKIE['FIRST_LOGIN'])){
         unset($_COOKIE['FIRST_LOGIN']);
         setcookie('FIRST_LOGIN', null, time()-1); 
        header("location: editprof.php");
    }
    else{
		header("location: home.php"); // redirects the user to the authenticated home page
    
   }
	}
	else{
		sendtoServer($soc, $pair, "quit", "");
		Print '<script>alert("Incorrect Username/Password!");</script>'; //Prompts the user
		Print '<script>window.location.assign("login.php");</script>'; // redirects to login
	}
?>
