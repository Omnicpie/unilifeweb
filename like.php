<?php
    include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
   sendtoServer($soc, $pair, "likepost", $_GET['postid'], $_GET['userid'], $_GET['group']);
 sendtoServer($soc, $pair, "quit", "");
 #print '<a href="home.php">home</a>';
 header('Location: '.$_GET['cur']);
 ?>