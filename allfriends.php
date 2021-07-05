<?php
session_start(); //starts the session
if($_SESSION['user']){ //checks if user is logged in
}
else{
    header("location:./index.php"); // redirects if user is not logged in
}
$user = $_SESSION['user']; //assigns user value
include './servercom.php';
$soc = setupServer();
$pair = sendAES($soc);
sendtoServer($soc, $pair, "getname", $user);
$name = serverRead($soc, $pair);
$split = explode(" ", $name);
$fname = $split[0];


$profileID = $_GET['id'];
sendtoServer($soc, $pair, "userinfo", $profileID);
$profile = serverRead($soc, $pair);
$usersdet = simplexml_load_string($profile);
foreach($usersdet->children() as $userinf){
    $profileName = $userinf['first_name']. ' ' .$userinf['last_name'];
    $profilePrivate = $userinf['private_profile'];
}
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="./styles/userhome.css">
        <link rel="stylesheet" type="text/css" href="./styles/profile.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php print'<title>'.$profileName.' | UniLife</title>'; ?>
        <link rel="icon" href="./content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="./content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="./content/favicon-32x32.png">
    </head>
    <body  style="display:flex;flex-direction:column;height:100%;">
        <header>
          	<a class="logo" style="float:left;"href="home.php">
          		<img class="imagelogo"src="./content/tempLogo.png" alt="UniLife">
          	</a>
          	<input type="text" id="myInput" placeholder="Search.."  onchange="search()">
          	<a style="float:right;padding-top:13pt;margin-right:3pt;" href="logout.php">Logout</a>
          	<a style="float:right;padding-top:13pt;margin:0 3pt 0 0;" <?php Print 'href="prof.php?name='.$name.'-'.$user.'">'.$fname.' |'?></a>  <!--Displays users name-->
          	<div id="navigation">
          		<div class="navigation-sub" onclick="dropIt();"><i class="fa fa-bell"></i><span style="background:var(--other-accent); padding: 0 2pt;border-radius:50%;font-size:10pt;"><?php sendtoServer($soc, $pair, "numreq", $user);
          		$i = serverRead($soc, $pair);
          		print intval($i);?></span></div>
          	</div>
          	<div id="requests" class="navigation-dropdown hide"style="height:200px;background:url('content/loading.gif') no-repeat center var(--background-colour); background-size: 50%;">
          		<iframe src="requests2.php" style="width:99%;height:84%;" frameborder="0"></iframe>
          		<a href="requests.php" style="background-color: var(--accent-colour);width:100%;display:block;height:20pt;padding:5pt 0 0px 0pt;font-size: 10pt;">View All</a>
          	</div>
           <div id="navigation">
          		<div id="navigation-sub" onclick="downIt();"><i class="fa fa-envelope"></i></div>
          	</div>
          	<div id="messages" class="navigation-dropdown hide" style="height:200px;background:url('content/loading.gif') no-repeat center var(--background-colour); background-size: 50%;">
                  <iframe src="messagesSmall.php" style="width:99%;height:84%;" frameborder="0"></iframe>
          		    <a href="messages.php" style="background-color: var(--accent-colour);width:100%;display:block;height:20pt;padding:5pt 0 0px 0pt;font-size: 10pt;">View All</a>
            </div>
          	<a href="home.php" style="float: right;margin-top: 8pt;height: max-content;font-size: 1em;"><i class="fa fa-home"></i></a>
          	<div id="navigation">
          		<div id="navigation-sub" onclick="foldIt();"><i class="fa fa-caret-down"></i></div>
          	</div>
          	<div id="system" class="navigation-dropdown hide" style="/*height:200px;background:url('content/loading.gif') no-repeat center var(--background-colour);*/left:90.5%; background-size: 50%;">
          		<a href="preferences.php" style="background-color: var(--background-colour);width:100%;display:block;padding:5pt;font-size: 10pt;">Settings</a>
          	</div>
        </header>
        <hr style="margin:0 0 29pt 0">
        <?php
        print '<a class="groupbutt" style="width:max-content" href="./prof.php?name='.$profileName.'-'.$profileID.'"><i  id="side" style="margin-top:10pt;color:var(--text-colour);" class="fa fa-chevron-circle-left"></i>Back to Profile</a>';
        sendtoServer($soc, $pair, "getfriends", $profileID);
        $reqs = serverRead($soc, $pair);
        sendtoServer($soc, $pair, "quit");
        $userRequests = simplexml_load_string($reqs);
        $count = $userRequests->count();
        print'<div class="posts" style="margin-top:0;"><h3 style="text-align:center;">'.$profileName.'\'s Friends</h3>';
        print '<hr/>';
        foreach($userRequests->children() as $userRequest){
          $userid = $userRequest['user_id'];
          $username = $userRequest['user_name'];
          print '<div class="post">';
          print '<a style="cursor:pointer" id="proflink" onclick="window.location.href=&quot;prof.php?name='.$username.'-'.$userid.'&quot;">';
          print '    <div class="group">';
          print '        <p id="name" style="text-align:center;">'.$username.'</p>';
          print '    </div></a></div>';
        }
        print'</div>';
        ?>
        <script type="text/javascript" src="./js/supportfunctions.js"></script>
   		  <script type="text/javascript" src="./js/colourMode.js"></script>
		    <script type="text/javascript" src="./js/andriodCheck.js"></script>
		    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
    </body>
</html>