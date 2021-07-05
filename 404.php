<?php
/*
	AUTHOR: Ryan Anderson
	404 page
*/
?>
<html>
	<head>
         <link rel="stylesheet" type="text/css" href="https://unilife.ddns.net/styles/variables.css">
        <link rel="stylesheet" type="text/css" href="https://unilife.ddns.net//styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>404 | UniLife</title>
        <link rel="icon" href="https://unilife.ddns.net/content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="https://unilife.ddns.net/content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="https://unilife.ddns.net/content/favicon-32x32.png">
	</head>
	<body>
	<?php
    error_reporting(E_ERROR);
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
		$user = $_SESSION['user']; //assigns user value
		//ALL THIS IS TO GET THE USER'S FIRST NAME
		include 'https://unilife.ddns.net/servercom.php';
        $soc = setupServer();
    	$pair = sendAES($soc);
		sendtoServer($soc, $pair, "getname", $user);
		$name = serverRead($soc, $pair);
		$split = explode(" ", $name);
		$fname = $split[0];
   sendtoServer($soc, $pair, "numreq", $user);
 		$i = serverRead($soc, $pair);
		$d=  intval($i);
		print'<header>
          	<a class="logo" style="float:left;"href="https://unilife.ddns.net/home.php">
          		<img class="imagelogo"src="https://unilife.ddns.net//content/tempLogo.png" alt="UniLife">
          	</a>
          	<input type="text" id="myInput" placeholder="Search.."  onchange="search()">
          	<a style="float:right;padding-top:13pt;margin-right:3pt;" href="https://unilife.ddns.net/logout.php">Logout</a>
          	<a style="float:right;padding-top:13pt;margin:0 3pt 0 0;" <?php href="https://unilife.ddns.net/prof.php?name='.$name.'-'.$user.'">'.$fname.' |</a>  <!--Displays users name-->
          	<div id="navigation">
          		<div class="navigation-sub" onclick="dropIt();"><i class="fa fa-bell"></i><span style="background:var(--other-accent); padding: 0 2pt;border-radius:50%;font-size:10pt;">';
              sendtoServer($soc, $pair, "numreq", $user);
          		$i = serverRead($soc, $pair);
          		print intval($i);
			print'</span></div>';
          	print'</div>
          	<div id="requests" class="navigation-dropdown hide"style="height:200px;background:url(\'https://unilife.ddns.net/content/loading.gif\') no-repeat center var(--background-colour); background-size: 50%;">
          		<iframe src="https://unilife.ddns.net/requests2.php" style="width:99%;height:84%;" frameborder="0"></iframe>
          		<a href="https://unilife.ddns.net/requests.php" style="background-color: var(--accent-colour);width:100%;display:block;height:20pt;padding:5pt 0 0px 0pt;font-size: 10pt;">View All</a>
          	</div>
           <div id="navigation">
          		<div id="navigation-sub" onclick="downIt();"><i class="fa fa-envelope"></i></div>
          	</div>
          	<div id="messages" class="navigation-dropdown hide" style="height:200px;background:url(\'https://unilife.ddns.net/content/loading.gif\') no-repeat center var(--background-colour); background-size: 50%;">
                  <iframe src="https://unilife.ddns.net/messagesSmall.php" style="width:99%;height:84%;" frameborder="0"></iframe>
          		    <a href="https://unilife.ddns.net/messages.php" style="background-color: var(--accent-colour);width:100%;display:block;height:20pt;padding:5pt 0 0px 0pt;font-size: 10pt;">View All</a>
            </div>
          	<a href="https://unilife.ddns.net/home.php" style="float: right;margin-top: 8pt;height: max-content;font-size: 1em;"><i class="fa fa-home"></i></a>
          	<div id="navigation">
          		<div id="navigation-sub" onclick="foldIt();"><i class="fa fa-caret-down"></i></div>
          	</div>
          	<div id="system" class="navigation-dropdown hide" style="/*height:200px;background:url(\'https://unilife.ddns.net/content/loading.gif\') no-repeat center var(--background-colour);*/left:90.5%; background-size: 50%;">
          		<a href="https://unilife.ddns.net/preferences.php" style="background-color: var(--background-colour);width:100%;display:block;padding:5pt;font-size: 10pt;">Settings</a>
          	</div>
        </header>';
	}
	else{
		Print'<header>';
        Print    '<a class="logo" style="float:left;"href="https://unilife.ddns.net/index.php">';
        Print       ' <img class="imagelogo"src="https://unilife.ddns.net/content/tempLogo.png" alt="UniLife">';
        Print    '</a>';
        Print'    <a style="float:right;padding-top:13pt;margin-right:3pt;" href="https://unilife.ddns.net/login.php">Login</a>';
        Print'    <a style="float:right;padding-top:13pt;margin-right:5pt;" href="https://unilife.ddns.net/register.php">Register |</a>';
        Print'</header>';
	}
 sendtoServer($soc, $pair, "quit");
	?>

        <hr style="margin:0 0 60pt 0">
        <h1 style="text-align:center;color:#fff">404: Page not found</h1>
		<img src="https://unilife.ddns.net/content/404panda.png" style="display: block;margin:auto; height: 30%;" alt="404panda">
		<h2 style="text-align:center;color:#fff"> It seems that page does not exist<br> if you belive this is a mistake, please contact a web admin!</h1>
	</body>
	<script type="text/javascript" src="https://unilife.ddns.net/js/supportfunctions.js"></script>
	<script type="text/javascript" src="https://unilife.ddns.net/js/colourMode.js"></script>
	<script type="text/javascript" src="https://unilife.ddns.net/js/andriodCheck.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>
