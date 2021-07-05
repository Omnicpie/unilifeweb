<?php

session_start(); //starts the session
if($_SESSION['user']){ //checks if user is logged in
}
else{
    header("location:../index.php"); // redirects if user is not logged in
}
$user = $_SESSION['user']; //assigns user value
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/creategroupstyle.css">
		<link rel="stylesheet" type="text/css" href="./styles/edpr.css">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Edit Profile | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
        <style>
        textarea{
          width:60%;
        }
        select{
            width:40%;
        }
        </style>
	</head>
 <body>
 <hr style="margin:0 0 30pt 0">
 <h2>WARNING: This will delete the group!</h2>
 <h3>Are you absolutely sure you want to do this?</h3>
<?php
  print '<a style="background:red;padding:5pt;border-radius:5px;"href="grpDelete.php?deleteClicked='.$_GET['groupid'].'">Yes, Delete Group</a>';

?>
  </body>
</html>
<?php
if(isset($_GET['deleteClicked'])){
    $gid = $_GET['deleteClicked'];
	      include 'servercom.php'; 
        $soc = setupServer();
	      $pair = sendAES($soc);
        if(file_exists())
        sendtoServer($soc, $pair, "delgroup", $gid, $user);
        sendtoServer($soc, $pair, "quit");
        echo "<script>window.top.location.href = \"home.php\";</script>"; 
}
?>