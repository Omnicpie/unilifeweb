<!DOCTYPE html>
<html>
	<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/userhome.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Edit Post | UniLife</title>
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
   if(isset($_REQUEST['postid'])){
       $postid = $_REQUEST['postid'];
   }
   if(isset($_REQUEST['groupid'])){
       if($_REQUEST['groupid'] == "1"){
           $groupid = "1";
       }
       else{
           $groupid = "0";
       }
   }
   else{
       echo "groupnotset";
       $groupid = "0";
   }
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
        <hr style="margin:0 0 60pt 0">
		<div class="magic">
    <?php print '<form class="newpost postactive" action="edit.php?postid='.$postid.'" method="POST">'; ?>
			  <p class="postBut">Edit Post</p>
        <div class="formcont">
        <?php
        sendtoServer($soc, $pair, "getpost", $postid, $user, $groupid);
        $post = serverRead($soc, $pair);
        echo $post;
        print '<input type="hidden" name="postid" value="'.$postid.'">';
        print '<input type="hidden" name="groupid" value="'.$groupid.'">';
        print '<input type="hidden" name="HTTP_REFERER" value="'.$_SERVER['HTTP_REFERER'].'">';
        $postinfo = simplexml_load_string($post);
        foreach ($postinfo as $info){
            print'<textarea name="content" maxlength="500" >'.$info['content'].'</textarea><br/>';
			      if($info['public_post'] == "1"){
                 Print '	<input type="radio" id="1" name="private" required value="1" checked><label for="1">Public</label><input type="radio" id="0" name="private" required value="0"><label for="0">Private</label>';
             }
             if($info['public_post'] == "0"){
                 Print '	<input type="radio" id="1" name="private" required value="1"><label for="1">Public</label><input type="radio" id="0" name="private" required value="0" checked><label for="0">Private</label>';
             }
         }
         sendtoServer($soc, $pair, "quit");
         ?>
			  <input type="submit" value="Post"/>
        </div>
		</form>
 </body>
<script type="text/javascript" src="./js/supportfunctions.js"></script>
<script type="text/javascript" src="./js/colourMode.js"></script>
<script type="text/javascript" src="./js/andriodCheck.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>
<?php 
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $soc = setupServer();
    $pair = sendAES($soc);
    sendtoServer($soc, $pair, "editpost", $_POST['postid'], $_POST['content'], $_POST['private'], $_POST['groupid']);
    sendtoServer($soc, $pair, "quit");
    header("location: editpost.php?postid=" . $_REQUEST['postid']);
}
