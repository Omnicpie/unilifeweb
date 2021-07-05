<?php 
$commentid = $_GET['id'];
$postid = $_GET['post'];
$isgroup = $_GET['isgroup'];
include 'servercom.php';
$soc = setupServer();
$pair = sendAES($soc);
sendtoServer($soc, $pair, "delcomment", $commentid, $isgroup);
sendtoServer($soc, $pair, "quit");
header("location:comments.php?postid=".$postid."&isgroup=".$isgroup);
?>