<?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
		header("location:./index.php"); // redirects if user is not logged in
	}
	$user = $_SESSION['user']; //assigns user value
	//ALL THIS IS TO GET THE USER'S FIRST NAME
	include './servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	$groupid = $_GET['id'];
	sendtoServer($soc, $pair, "getgroup", $groupid, $user);
	$groupInfo = serverRead($soc, $pair);
	$groupInfoXML = simplexml_load_string($groupInfo); #or die("Failed to load");
        $errors = libxml_get_errors();

        foreach ($errors as $error) {
            echo display_xml_error($error, $groupInfoXML);
        }
        foreach($groupInfoXML->children() as $info){
            $groupname = htmlspecialchars_decode($info['group_name'], ENT_QUOTES);
            $groupdesc = htmlspecialchars_decode($info['group_description'], ENT_QUOTES);
            $privgroup = $info['private_group'];
		}
?>
<html>
	<head>

		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="./styles/userhome.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="./styles/profile.css">
		<?php print'<title>'.$groupname.' | UniLife</title>';?>
        <link rel="icon" href="./content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="./content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="./content/favicon-32x32.png">
		<link rel="stylesheet" type="text/css" href="./styles/edpr.css">
	</head>
	<?php
	sendtoServer($soc, $pair,"getname", $user);
    $name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0];
	sendtoServer($soc, $pair,"getpermissionlevel", $user, $groupid);
   	$permLevel = intval(serverRead($soc, $pair));
	?>
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
        <hr style="margin:0 0 30pt 0">
        <div class="info" <?php
        sendtoServer($soc, $pair, "getgroupban", $groupid);
        $pic = serverRead($soc, $pair);
        if($pic === "NULL"){
            print 'style="background:url(./content/testbg.jpg) no-repeat center; background-size:cover;"';
        }
        else{
            $link = 'data:image/jpg;base64,'.$pic;
            print 'style="background:url('.$link.') no-repeat center; background-size:cover;"';
        }
        $link = 'data:image/jpg;base64,'.$pic;?>>
            <div class="profpic type3">
                <?php
                sendtoServer($soc, $pair, "getgrouppic", $groupid);
                $pic = serverRead($soc, $pair);
                $link = 'data:image/jpg;base64,'.$pic;
                $alt = '"./content/userph.png"';

                Print '<img class="pp" src="'.$link.'"  onerror="this.onerror=null;this.src=\'./content/grpico.png\';" />';
                ?>
            </div>
			<?php
            	print '<h1 class="name">'.$groupname.'</h1>';

            if ($permLevel < 2){
                        Print '<a href="./grouppreferences.php?groupid='.$groupid.'" style="float: right;top: 20%;position: absolute;left: 79%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Edit Group</a>';
                    }
            if ($permLevel < 4){
                Print '<a href="./leaveGroup.php?groupid='.$groupid.'" style="float: right;top: 20%;position: absolute;left: 86%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Leave Group</a>';
            }
            if ($permLevel == 4 && $privgroup == 0){
                Print '<a href="./joingroup.php?groupid='.$groupid.'" style="float: right;top: 20%;position: absolute;left: 86%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Join Group</a>';
            }
            if ($permLevel == 4 && $privgroup == 1){
                Print '<a href="./joingroup.php?groupid='.$groupid.'" style="float: right;top: 20%;position: absolute;left: 86%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Request Join</a>';
            }
            if ($permLevel == 5 && $privgroup == 1){
                Print '<a href="./joingroup.php?groupid='.$groupid.'" style="float: right;top: 20%;position: absolute;left: 86%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Request Sent</a>';
            }
                    ?>
        </div>
        <div class="description" style="margin-bottom:10pt;">
          <?php print '<p>'.$groupdesc.'</p>';?>
        </div>
        <?php
            if($permLevel < 3){
                Print'<div class="magic">';
                Print'<form class="newpost" action="./addgrouppost.php" method="POST" enctype="multipart/form-data">';
                Print'<p class="postBut">New Post</p> <textarea name="content" maxlength="500" required="required"></textarea><br/>';
                Print '<input type="hidden" name="groupid" value="'.$groupid.'">';
                Print '<input type="hidden" name="groupname" value="'.$groupname.'")>';
                print '
			<h4 style="text-align:center; color:#fff">You can also select an image to go with this post</h4>
			<input hidden type="file" name="postpic" id="postpic" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
			<label for="postpic" style="height:38pt;"><span></span><strong><p style="margin:7pt 0;">Choose a file&hellip;</p></strong></label>';
          			Print'<input type="submit" value="Post"/>';
          		  Print'</form>';
                Print'</div>';
           }
           sendtoServer($soc, $pair, "getmembers", $groupid);
           $reqs = serverRead($soc, $pair);
				    sendtoServer($soc, $pair, "quit", "");
           $userRequests = simplexml_load_string($reqs);
           $count = $userRequests->count();
           print'<div class="members"><h3>Members - '.$count.'</h3>';
           print '<hr/>';
           if($count <= 3){
               foreach($userRequests->children() as $userRequest){
                 $userid = $userRequest['user_id'];
                 $username = $userRequest['user_name'];
                 print '<div class="member">';
                 print '<a href="" id="proflink" onclick="window.location.href=&quot;prof.php?name='.$username.'-'.$userid.'&quot;">';
                 print '    <div class="group">';
                 print '        <p id="name" style="text-align:center;">'.$username.'</p>';
                 print '    </div></a></div>';
               }
           }
           else{
               for($j = 0; $j < 3; $j++){
                   $userid = $userRequests->member[$j]['user_id'];
                   $username = $userRequests->member[$j]['user_name'];
                    print '<div class="member">';
                    print '<a href="" id="proflink" onclick="window.location.href=&quot;prof.php?name='.$username.'-'.$userid.'&quot;">';
                    print '    <div class="group">';
                    print '        <p id="name" style="text-align:center;">'.$username.'</p>';
                    print '    </div></a></div>';
               }
               print '<p>...</p>';
           }
           print'</div>';
           print '<h2 align="center">Group Posts</h2>';
           if($privgroup == 1 && $permLevel > 3){
             print'<p style="text-align:center;">You do not have the rights to view posts in this group</p>';
           }
           else{
               print'<div class="posts" style="display: flex; flex-grow: 2;background:url(\'./content/loading.gif\') no-repeat center var(--background-colour); background-size: 10%;">';
			         print'    <iframe src="./groupfeed.php?groupid='.$groupid.'" style="width:100%; height:100%;" frameborder="0" sandbox="allow-top-navigation allow-same-origin allow-scripts allow-popups allow-forms"></iframe>';
               print'</div>';
           }
        ?>
	</body>
    <script type="text/javascript" src="./js/supportfunctions.js"></script>
    <script type="text/javascript" src="./js/colourMode.js"></script>
    <script type="text/javascript" src="./js/andriodCheck.js"></script>
		<script src="js/custom-file-input.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>
