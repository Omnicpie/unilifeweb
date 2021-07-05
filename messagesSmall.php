<html>
	<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/userhome.css">
     <link rel="stylesheet" type="text/css" href="./styles/prefs.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Messages | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
		<link rel="stylesheet" type="text/css" href="styles/profile.css">
   </head>
   	<?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
		header("location:index.php"); // redirects if user is not logged in
	}
	$user = $_SESSION['user']; //assigns user value
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	sendtoServer($soc, $pair, "getname", $user);
	$name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0];
	?>
   <body>
   <?php
   sendtoServer($soc, $pair, "getchat", $user);
   $chat = serverRead($soc, $pair);
   sendtoServer($soc, $pair, "quit");
   $chatXML = simplexml_load_string($chat);
   if ($chatXML->count()== 0 || (boolean) $chatXML == false){
       print '<p>No Chats to display</p>'; 
   }
   else{
       foreach($chatXML->children() as $chatchild){
           Print'<button onclick="window.top.location.href = &quot;messages.php?chat='.$chatchild['id'].'&quot;" id="chat">'.$chatchild['chat_name'].'</button>';
       }
   }
   ?>
  </body>
</html>