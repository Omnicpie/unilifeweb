<?php
    session_start(); //starts the session
    include 'servercom.php';
	  $soc   = setupServer();
	  $pair  = sendAES($soc);
    $user  = $_SESSION['user'];
    $group = $_GET['groupid'];
    sendtoServer($soc, $pair, "leavegroup", $user, $group);
    sendtoServer($soc, $pair, "quit");
    header('Location: groups.php');
?>