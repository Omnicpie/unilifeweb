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
        .message{
            width:40%;
        }
        .head{
            display:flex;
            position: fixed;
            top: 0;
            background-color: var(--background-colour);
            z-index: 99999;
            width: 100%;
            border-bottom: 1px solid var(--header-colour);
        }
        input[type="text"]{
            border: none;
            border-bottom: 4px solid var(--accent-colour);
            background: none;
            text-align: center;
            font-weight: bold;
            width: 75%;
            color: var(--text-colour);
        }
        </style>
	</head>
	<?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
	##	header("location:index"); // redirects if user is not logged in
	}
	$user = $_SESSION['user']; //assigns user value
   if($_GET['chatid'] == ""){
       die("No Chat Selected");
   }
	//ALL THIS IS TO GET THE USER'S FIRST NAME
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	sendtoServer($soc, $pair, "getname", $user);
	$name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0];
  $chatid = (string) $_GET['chatid'];
  sendtoServer($soc, $pair, "getchat", $user);
  $chats = serverRead($soc, $pair);
  $chatsXML = simplexml_load_string($chats);
  foreach ($chatsXML as $chatXML){
      if ($chatXML['id'] == $chatid){
          $chatname = $chatXML['chat_name'];
      }
  }
	?>
	<body style="height:100%;">
     <div class="head">
         <h1><?php print $chatname; ?></h1>
         <div id="navigation" style="margin-left:auto;">
          		<div id="navigation-sub" style="background-color: var(--other-accent);width: 22pt;text-align: center;border-radius: 50%;padding: 3pt 0;margin-top: 13pt;margin-right: 20pt;"onclick="foldIt();"><i style="font-size: 1.3em;pointer-events: none;"class="fa fa-info"></i></div>
          	</div>
          	<div id="system" class="navigation-dropdown hide" style="/*height:200px;background:url('content/loading.gif') no-repeat center var(--background-colour);*/left:90.5%; background-size: 50%;">
          		<?php print'<a href="leavechat.php?chatid='.$chatid.'&userid='.$user.'" style="background-color: var(--background-colour);width:100%;display:block;padding:5pt;font-size: 10pt;">Leave Chat</a>'; ?>
          	</div>
     </div>
     <br style="height:40pt;margin-bottom: 47pt;">
     <div class="messages">
      <?php 
      sendtoServer($soc, $pair, "getmessages", $chatid);
      $messages = serverRead($soc, $pair);
      $messagesXML = simplexml_load_string($messages);
      if ($messagesXML->count()== 0 || (boolean) $messagesXML == false){
          print '<p>No Messages yet</p>'; 
      }
      else{
          foreach ($messagesXML as $messageXML){
              if ($messageXML['user_id'] == $user){
                  print '<div class="message" style="margin-left:auto;background-color:var(--accent-colour)">';
                  print '    <p>'.$messageXML['user_name'].' <span>'.$messageXML['data_sent'].'</span></p>';
                  print '    <p style="padding-bottom:5pt;text-align:inherit;direction:rtl;margin-right:15pt;">'.$messageXML['content'].'</p>';
                  print '</div>';
              }
              else{
                  print '<div class="message" style="margin-right:auto;background-color:#fff;color:var(--header-colour) !important;">';
                  print '    <p style="color:inherit">'.$messageXML['user_name'].' <span>'.$messageXML['data_sent'].'</span></p>';
                  print '    <p style="padding-bottom:5pt;color:inherit;text-align:inherit;margin-left:15pt;">'.$messageXML['content'].'</p>';
                  print '</div>';
              }
          }
      }
      sendtoServer($soc, $pair, "quit", "");
       ?>
       </div>
       <hr style="position: initial;margin: 0;margin-top: 0px;margin-top: 46pt;width: 99%;visibility: hidden;">

       <div style="position: fixed;bottom: 0;width: 100%;display: flex;border-top: 1px solid var(--header-colour);background-color:var(--background-colour);">
         <form action="sendchat.php" method="post" style="display: flex;justify-content: center;margin-top: 10pt;width:100%;">
             <?php print '<input type="hidden" name="chatid" value="'.$chatid.'">'; ?>
             <input type="text" required maxlength="1000" name="message" placeholder="Send a Message...">
             <button type="submit" style="background:0; border:0; font-size:20pt;color:var(--other-accent); cursor:pointer;"><i class="fa fa-send"></i></button>
         </form>
       </div>
	    <script type="text/javascript" src="./js/supportfunctions.js"></script>
	    <script type="text/javascript" src="./js/colourMode.js"></script>
	    <script type="text/javascript" src="./js/andriodCheck.js"></script>
		<script src="js/custom-file-input.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
	</body>
</html>