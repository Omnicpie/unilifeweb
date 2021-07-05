<?php
    session_start(); //starts the session
    include 'servercom.php';
	  $soc   = setupServer();
	  $pair  = sendAES($soc);
    $user  = $_SESSION['user'];
    $group = $_GET['groupid'];
    sendtoServer($soc, $pair, "joingroup", $user, $group);
    sendtoServer($soc, $pair, "quit");
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>