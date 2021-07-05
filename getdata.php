<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/regstyle.css">
		<link rel="stylesheet" type="text/css" href="./styles/edpr.css">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Edit Profile | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
        <style>      
          .groupbutt{
              margin-left:5%;
              height: 35pt;
              border: 2px ridge var(--lighter-head);
              display: block;
              width: 100pt;
              border-collapse: collapse;
              margin-top: 5pt;
              font-size:1em;
              text-align:center;
          }
          #myInput {
            background-image: url('https://www.w3schools.com/css/searchicon.png');
            background-position: 7pt 4pt;
            background-repeat: no-repeat;
            background-color:#fff;
            display:inline;
            margin:0;
            color:black;
            text-align:left;
            font-weight:normal;
            width: 30%;
            font-size: 10pt;
            padding: 6pt 20pt 6pt 40pt;
            border: 1px solid #ddd;
            margin-top: 1.5pt;
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
	//ALL THIS IS TO GET THE USER'S FIRST NAME
 error_reporting(E_ALL);
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	sendtoServer($soc, $pair, "getname", $user);
	$name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0];
  sendtoServer($soc, $pair, "userinfo", $user);
  $userRead = serverRead($soc, $pair);
  $userInfo = simplexml_load_string($userRead);
  if ($userInfo == false) {
      $errors = libxml_get_errors();
      #echo print_r($errors);
      foreach ($errors as $error) {
          echo display_xml_error($error, $userInfo);
      }
      libxml_clear_errors();
	    }
  foreach($userInfo->children() as $infoItem){
      $fname      = $infoItem['first_name'];
      #echo $fname, '<br/>';
      $lname      = $infoItem['last_name'];
      #echo $lname, '<br/>';
      $gend       = $infoItem['gender'];
      #echo $gend, '<br/>';
      $priv       = $infoItem['private_profile'];
      #echo $priv, '<br/>';
      $courseID   = $infoItem['course_id'];
      #echo $courseID, '<br/>';
      $studytype  = $infoItem['study_type'];
      #echo $studytype, '<br/>';
      $cstart     = $infoItem['course_start'];
      #echo $cstart, '<br/>';
      $cend       = $infoItem['course_end'];
      #echo $cend, '<br/>';
    }
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
        <p> <span style="colour:red;font-weight:bolder;">WARNING:</span> This WILL take some time to compile. It will be sent to the email entered when ready</p>
		    <form action="getdata.php" method="POST">
            <h3>Input an email for the data to be sent to...</h3>
            <input type="text" name="email" placeholder="Enter Email">
            <input type="submit" value="Request Data">
       </form>      
		<?php sendtoServer($soc, $pair, "quit", ""); ?>
	    <script type="text/javascript" src="./js/supportfunctions.js"></script>
	    <script type="text/javascript" src="./js/colourMode.js"></script>
	    <script type="text/javascript" src="./js/andriodCheck.js"></script>
		<script src="js/custom-file-input.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
	</body>
</html>
<?php 
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$soc = setupServer();
	$pair = sendAES($soc);
   sendtoServer($soc, $pair, "getdata", $user, $_POST['email']);
   sendtoServer($soc, $pair, "quit", "");
   }
