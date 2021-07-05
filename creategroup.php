<?php
/*
	AUTHOR: Jack Tomlinson & Ryan Anderson
	Form for creating a new group
*/
?>

<html>
	<head>
 <link rel="stylesheet" type="text/css" href="./styles/variables.css">
       <!--<link rel="stylesheet" type="text/css" href="./styles/regstyle.css">-->
       <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/creategroupstyle.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Create Group | UniLife</title>
       <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
       <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
       <link rel="icon" type="image/png" href="content/favicon-32x32.png">
       
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
	sendtoServer($soc, $pair,"getname", $user);
  $name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0];
	?>
	<body>
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
        <hr style="margin:0 0 60pt 0">
        <!-- form for creating a group -->
        <a class="groupbutt" href="./groups.php"><i  id="side" style="margin-top:10pt;color:var(--other-accent)" class="fa fa-chevron-circle-left"></i>Back to Groups</a>
        <form class="creategroup" action="creategroup.php" method="POST">
			<p style="margin-top:8pt;">Group Name:</p>
           <textarea class="thintext" name="groupname" maxlength="50" required="required"></textarea><br/><br/>
			<p>Group Description:</p>
           <textarea class="fattext" name="groupdesc" maxlength="1000" required="required"></textarea><br/><br/>
   <p>Private Group:</p>
     <div class="genSlect">
				<input type="radio" id="0" name="private" required value="0"><label for="0">Public</label>
				<input type="radio" id="1" name="private" required value="1"><label for="1">Private</label>
			</div><br/><br/>
      <p>Default Group Permission:</p>
      <select name="perm" required="required">
      <option value="" selected disabled hidden>Select Permission Level</option>
      <option value="1" >Admin User</option>
      <option value="2" >Read and Write Posts</option>
      <option value="3" >Read Post Only</option>
      <br/><br/>
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
        $gname = $_POST['groupname'];
        $gdesc = $_POST['groupdesc'];
        $defperm = $_POST['perm'];
        $privgroup = $_POST['private'];
        $verigroup = 0;
		$soc = setupServer();
		$pair = sendAES($soc);
        sendtoServer($soc, $pair, "creategroup",  $user, $gname, $gdesc, $defperm, $privgroup, $verigroup); //adds group to ginfo table in db
        $done = serverRead($soc, $pair);
		sendtoServer($soc, $pair, "quit", "");

        if($done == "DONE"){
            Print '<p>Group Created</p>';
        }
    }
 ?>
