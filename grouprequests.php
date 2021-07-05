<?php
include 'servercom.php';
if($_GET['op'] == "a"){
    $groupid = $_GET['groupid'];
    $userid = $_GET['userid'];
	  $soc = setupServer();
	  $pair = sendAES($soc);
    sendtoServer($soc, $pair, "reqaccept", $groupid, $userid);
    sendtoServer($soc, $pair, "quit");
    header("location:grpMessageRequests.php?groupid=".$groupid);
}
if($_GET['op'] == "d"){
    $groupid = $_GET['groupid'];
    $usersid = $_GET['userid'];
	  $soc = setupServer();
	  $pair = sendAES($soc);
    sendtoServer($soc, $pair, "reqdeny", $groupid, $userid);
    sendtoServer($soc, $pair, "quit");
    header("location:grpMessageRequests.php?groupid=".$groupid);
}
?>