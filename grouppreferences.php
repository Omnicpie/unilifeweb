<!DOCTYPE html>
<html>
	<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/userhome.css">
     <link rel="stylesheet" type="text/css" href="./styles/prefs.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Group Preferences | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
		<link rel="stylesheet" type="text/css" href="styles/profile.css">
		<style>
			body{
				position: absolute;
			    margin: 0px;
			    overflow: hidden;
			    top: 0px;
			    bottom: 0px;
			    width: 100%;
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
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	sendtoServer($soc, $pair, "getname", $user);
	$name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0];
  $groupid = (string) $_GET['groupid'];
 	sendtoServer($soc, $pair, "getgroup", $groupid, $user);
  $groupRead = serverRead($soc, $pair);
  $groupInfo = simplexml_load_string($groupRead);
  if ($groupInfo === false) {
      $errors = libxml_get_errors();
      foreach ($errors as $error) {
          echo display_xml_error($error, $groupInfo);
      }
      libxml_clear_errors();
	    }
  foreach($groupInfo->children() as $infoItem){
      $groupname = $infoItem['group_name'];
      $privGroup = intval($infoItem['private_group']);
    }
	sendtoServer($soc, $pair,"getpermissionlevel", $user, $groupid);
   $permLevel = intval(serverRead($soc, $pair));
	?>
	<body style="display:flex;flex-direction:column;">
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
        <div class="prefs" style="display:flex;flex-grow:2;flex-direction:column">
            <a class="groupbutt" style="height:40pt; width:122pt;" href=<?php print '"group.php?id='.$groupid.'"'; ?>><i  id="side" style="margin-top:10pt;" class="fa fa-chevron-circle-left"></i>Group Profile</a>
            <h1 style="width:100%; height:max-content; border-bottom:1px solid black;text-align:center;padding-bottom:6pt;margin-bottom:0;"><?php print $groupname; ?>'s Preferences</h1>
            <div class="panels" style="display: flex;flex-grow: 2;">
                <div class="leftpanel" style="width:20%;padding-top:10pt;border-right:1px solid black;">
                <?php
                    Print'<button class="active" onclick="selectIFrameTarget(event, '.$groupid.')" id="general">General Settings</button>';
                    if($privGroup == 1){
                        Print'<button onclick="selectIFrameTarget(event, '.$groupid.')" id="mreq">Member Requests</button>';
                    }
                    Print'<button onclick="selectIFrameTarget(event, '.$groupid.')" id="mems">Members</button>';
                    if($permLevel == "0"){
                        print'<button onclick="selectIFrameTarget(event, '.$groupid.')" id="delgrp">Delete Group</button>';
                    }
                    ?>
                </div>
                <div class="rightpanel" style="width:80%;">
                    <?php print'<iframe id="iframe" src="./grpGeneralSettings.php?groupid='.$groupid.'" frameborder="0"  style="width:100%;height:100%;" sandbox="allow-top-navigation allow-same-origin allow-scripts allow-popups allow-forms"></iframe>';?>
                </div>
            </div>
        </div>
    <script type="text/javascript" src="./js/groupPrefs.js"></script>
		<script type="text/javascript" src="./js/supportfunctions.js"></script>
		<script type="text/javascript" src="./js/colourMode.js"></script>
		<script type="text/javascript" src="./js/andriodCheck.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>

	</body>
</html>
