<?php
/*
	AUTHOR:  Ryan Anderson
	Form for creating a new chat
 based from creategroup.php
*/
?>

<html>
	<head>
 <link rel="stylesheet" type="text/css" href="./styles/variables.css">
       <!--<link rel="stylesheet" type="text/css" href="./styles/regstyle.css">-->
       <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/creategroupstyle.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Create Chat | UniLife</title>
       <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
       <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
       <link rel="icon" type="image/png" href="content/favicon-32x32.png">
       <style>
       input[type="text"]{
          border: none;
          border-bottom: 4px solid var(--accent-colour);
          background:none;
          display:block;
          text-align:center;
          font-weight:bold;
          width:75%;
          margin:auto;
          color:var(--text-colour);
        }
        .checkboxcontainer{
            height:200px;
            width:60%;
            border-bottom:5px solid var(--accent-colour);
            overflow-y:auto;
            overflow-x:hidden;
            margin:auto;
        }
       </style>
	</head>
<?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
		header("location:index.php"); // redirects if user is not logged in
	}
	$user = $_SESSION['user']; //assigns user value
	?>
	<body>
        
        <hr style="margin:0 0 60pt 0">
        <!-- form for creating a group -->
        <h2>Create a new chat</h2>
        <h4>PLEASE NOTE THIS IS NOT AN INSTANT MESSANGER AND YOU WILL HAVE TO RELOAD TO SEE NEW CHATS</h4>
        <form class="creategroup" action="createchat.php" method="POST">
			<p style="margin-top:8pt;">Chat Name (Left bland this will be participants names)</p>
           <input type="text" name="chatname" placeholder="Enter a Chat Name..."><br/><br/>
			<p>Friends in Chat</p> 
      <div class="checkboxcontainer">
      <?php
      	include 'servercom.php';
      		$soc = setupServer();
      		$pair = sendAES($soc);
          sendtoServer($soc, $pair, "getfriends", $user);
          $friendsList = serverRead($soc, $pair);
          sendtoServer($soc, $pair, "quit", "");
          $friends = simplexml_load_string($friendsList);
          foreach($friends->children() as $friend){
              Print '<div style="display:flex"><label style="margin-left:auto" for="'.$friend['user_id'].'">'.$friend['user_name'].'</label><input style="width:max-content;margin-right:auto;" type="checkbox" name="friends[]" value="'.$friend['user_id'].'"></div>';
          }
      ?>
      </div><br/>
      
			<input type="submit" value="Submit"/>
		</form>
     </body>
	 <script type="text/javascript" src="./js/supportfunctions.js"></script>
	 <script type="text/javascript" src="./js/colourMode.js"></script>
	 <script type="text/javascript" src="./js/andriodCheck.js"></script>
	 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>
<?php

	if($_SERVER["REQUEST_METHOD"] == "POST"){
    ## include 'servercom.php';
		$soc1 = setupServer();
		$pair1 = sendAES($soc1);
    sendtoServer($soc1, $pair1, "chatcreate",  $user, $_POST['friends'][0], $_POST['chatname']); //adds group to ginfo table in db
    $id = serverRead($soc1, $pair1);
    for ($i = 1; $i < count($_POST['friends']); $i++){
        sendtoServer($soc1, $pair1, "chatadduser", $_POST['friends'][$i], $id);
    }
		sendtoServer($soc1, $pair1, "quit", "");
    echo 'Complete, Please reload page using this buttom: <br/><button onclick="window.top.location.assign(&quot;messages.php&quot;)">Reload</button>';
    }
 ?>
