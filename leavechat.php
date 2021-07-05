<?php
include 'servercom.php';
$userid = $_GET['userid'];
$chatid = $_GET['chatid'];
$soc = setupServer();
$pair = sendAES($soc, $pair);
sendtoServer($soc, $pair, "chatleave", $userid, $chatid);
sendtoServer($soc, $pair, "quit");
?>
<html>
<head>
    <script>
      window.top.location.assign("messages.php");
    </script>
</head>
<body>
</body>
</html>