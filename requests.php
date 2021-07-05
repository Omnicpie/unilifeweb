<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/userhome.css">
   		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Friend Requests | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
		<link rel="stylesheet" type="text/css" href="styles/profile.css">
		<style>
	        .type4 {
	            display: inline-block;
	            width: 18pt;
	            height: 18pt;
	            background: #6d6d6d;
	            border: 2pt solid #313030;
	            }
	    </style>
	</head>
	<?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
		header("location:index"); // redirects if user is not logged in
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
		<h2 align="center">Friend Requests</h2>
		<div class="posts">
			<?php
                function utf8_fopen_read($fileName,$user) {
                    $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
                    $handle=fopen($user."-2.xml", "w");
                    fwrite($handle, $fc);
                    fseek($handle, 0);
                    return $handle;
                }
				function checkArray($array, $id){
					$out = "";
					if(array_key_exists($id, $array)){
						$out = "yes";
					}
					else{
						$out = "no";
					}
					return $out;
				}
				ob_start();
				sendtoServer($soc, $pair, "getfndrequests", $user);
				$doc = serverRead($soc, $pair);
			    $requests = simplexml_load_string($doc );// or die("Failed to load");
				$userimgs = array();
        if($requests -> count() == 0){
            print '<p>no requests to view</p>';
        }
        else{
			    foreach($requests->children() as $request){
					$senderid = $request['sender_id'];
					settype($senderid, "int");
                    $pic = "";
                    if($request['has_profile_pic'] == "1"){
                        $inPicArray = checkArray($userimgs, $senderid);
                        $pic = "";
                        if($inPicArray == "yes"){
                            $pic = $userimgs[$senderid];
                        }
                        if($inPicArray == "no"){
                            sendtoServer($soc, $pair, "getpic", $senderid);
							$pic = serverRead($soc, $pair);
                            $userimgs += [$senderid => $pic];
                        }
                    }
					$link = 'data:image/jpg;base64,'.$pic;
					Print '<div align="center" class="post">';
					Print'<a style="font-size:15pt;margin-top:3pt;" href="prof.php?name='.$request['sender_name'].'-'.$request['sender_id'].'"><div style="float:left;" class="profpic type4">';
					Print'	<img class="pp" style="height:18pt;width:18pt" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
					Print'</div>';
					Print'<div class="userinfo">'.$request['sender_name'].'</a></div>';
          Print'<a href="friends.php?deny='.$user.'_'.$senderid.'" style="float: right;top: 25%;left: 93%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Decline</a>';
					Print'<a href="friends.php?accept='.$user.'_'.$senderid.'" style="float: right;top: 25%;left: 88%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Accept</a>';
					Print "</div>";
			    }
       }
				ob_end_flush();
			?>
        </div>
        <hr style="width:40%;margin:1px auto; color:var(--lighter-head);"/>
        <h3 style="text-align:center;"> Friend Recommendations</h3>
        <div class="posts">
            <?php
				ob_start();
				sendtoServer($soc, $pair, "friendrec", $user);
				$doc = serverRead($soc, $pair);
			    $requests = simplexml_load_string($doc );// or die("Failed to load");
				$userimgs = array();
        if($requests -> count() == 0){
            print '<p>no requests to view</p>';
        }
        else{
			    for($i =0; $i<5; $i++){
    		      $senderid = $requests->friend_recommendation[$i]['sender_id'];
              $sendername = $requests->friend_recommendation[$i]['sender_name'];
              $haspp = $requests->friend_recommendation[$i]['has_profile_pic'];
    					settype($senderid, "int");
                        $pic = "";
                        if($haspp == "1"){
                            $inPicArray = checkArray($userimgs, $senderid);
                            $pic = "";
                            if($inPicArray == "yes"){
                                $pic = $userimgs[$senderid];
                            }
                            if($inPicArray == "no"){
                                sendtoServer($soc, $pair, "getpic", $senderid);
    							$pic = serverRead($soc, $pair);
                                $userimgs += [$senderid => $pic];
                            }
                        }
    					$link = 'data:image/jpg;base64,'.$pic;
    					Print '<div align="center" class="post">';
    					Print'<a style="font-size:15pt;margin-top:3pt;" href="prof.php?name='.$sendername.'-'.$senderid.'"><div style="float:left;" class="profpic type4">';
    					Print'	<img class="pp" style="height:18pt;width:18pt" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
    					Print'</div>';
    					Print'<div class="userinfo">'.$sendername.'</a></div>';
              Print'<a href="friends.php?send='.$user.'_'.$senderid.'" style="float: right;top: 25%;left: 93%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Send Request</a>';
    					Print "</div>";
			    }
       }
				ob_end_flush();
		        sendtoServer($soc, $pair, "quit", "");
			?>
        </div>
		<script type="text/javascript" src="./js/supportfunctions.js"></script>
		<script type="text/javascript" src="./js/colourMode.js"></script>
		<script type="text/javascript" src="./js/andriodCheck.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
    </body>
</html>
