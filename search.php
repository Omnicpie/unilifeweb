<?php
/*
	AUTHOR: Ryan Anderson
	Th search page, uses GET for a search term from anywhere on the site, and displays some people, groups and posts relevant to that term
*/
?>
<html>
	<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="./styles/userhome.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="styles/profile.css">
        <title>Search | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
        <style>
        .join{
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: auto;
            width: 20%;
            text-align: center;
            float:left;
            margin: auto 0;
        }
        .joinbutton {
            background-color: var(--accent-colour);
            border-radius: 10px;
            padding: 5pt;
            width: max-content;
            height: max-content;
            margin: auto;
        }
        #verified {
            background-color: var(--other-accent);
            margin-left: 1pt;
            border-radius: 45%;
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
	//ALL THIS IS TO GET THE USER'S FIRST NAME
	include 'servercom.php';
  $soc = setupServer();
	$pair = sendAES($soc);
	sendtoServer($soc, $pair,"getname", $user);
    $name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0];
   sendtoServer($soc, $pair, "search", $_GET['term'], $user);
   $req = serverRead($soc, $pair);
   $page = "search.php?term=".$_GET['term'];
   $query = simplexml_load_string($req);
   $friends = $query->friend;
   $people = $query->friend_recommendation;
   $posts = $query->post;
   $groups = $query->group;
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
	?>
	<body style="display: flex;flex-direction: column;height: 100%;">
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
        <hr style="margin:0 0 28pt 0">
        <h1>Search Results</h1>
        <div class="posts" id="people">
        <?php
        $userimgs = array();
        if($friends->count() != 0){
                print '
            <h3 style="text-align:center">Friends</h3>
            <hr style="color:var(--lighter-head);width:60%;margin:auto;margin:bottom:5pt;">';
            }
            if($friends->count() >= 5){
                for($i = 0; $i < 5; $i++){
                    $friend = $friends[$i];
                    $userid = $friend['user_id'];
                    settype($userid, "int");
                    $pic = "";
                    if($friend['has_profile_pic'] == "1"){
                        $inPicArray = checkArray($userimgs, $userid);
                        $pic = "";
                        if($inPicArray == "yes"){
                            $pic = $userimgs[$userid];
                        }
                        if($inPicArray == "no"){
                            sendtoServer($soc, $pair, "getpic", $userid);
                        	  $pic = serverRead($soc, $pair);
                            $userimgs += [$userid => $pic];
                        }
                    }
                    $link = 'data:image/jpg;base64,'.$pic;
                    Print '<div align="center" class="post">';
                    Print'<a style="font-size:15pt;margin-top:3pt;" href="prof.php?name='.$friend['user_name'].'-'.$friend['user_id'].'"><div style="float:left;" class="profpic type4">';
                    Print'	<img class="pp" style="height:18pt;width:18pt" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
                    Print'</div>';
                    Print'<div class="userinfo">'.$friend['user_name'].'</a></div>';
                    Print'<a href="friends.php?remove='.$user.'_'.$userid.'" style="float: right;top: 25%;left: 93%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Unfriend</a>';
                    Print "</div>";               
                }
            }
            else{
                $number = $friends->count();
                for ($i = 0; $i < $number; $i++){
                    $friend = $friends[$i];
                    $userid = $friend['user_id'];
                    settype($userid, "int");
                    $pic = "";
                    if($friend['has_profile_pic'] == "1"){
                        $inPicArray = checkArray($userimgs, $userid);
                        $pic = "";
                        if($inPicArray == "yes"){
                            $pic = $userimgs[$userid];
                        }
                        if($inPicArray == "no"){
                            sendtoServer($soc, $pair, "getpic", $userid);
                        	  $pic = serverRead($soc, $pair);
                            $userimgs += [$userid => $pic];
                        }
                    }
                    $link = 'data:image/jpg;base64,'.$pic;
                    Print '<div align="center" class="post">';
                    Print'<a style="font-size:15pt;margin-top:3pt;" href="prof.php?name='.$friend['user_name'].'-'.$friend['user_id'].'"><div style="float:left;" class="profpic type4">';
                    Print'	<img class="pp" style="height:18pt;width:18pt" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
                    Print'</div>';
                    Print'<div class="userinfo">'.$friend['user_name'].'</a></div>';
                    Print'<a href="friends.php?remove='.$user.'_'.$userid.'" style="float: right;top: 25%;left: 93%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Unfriend</a>';
                    Print "</div>";               
                }
            }
            ?>
        </div>
        <div class="posts" id="people">
        <?php
        if($people->count() != 0){
                print '
            <h3 style="text-align:center">People Recommended for You</h3>
            <hr style="color:var(--lighter-head);width:60%;margin:auto;margin:bottom:5pt;">';
            }
            if($people->count() >= 5){
                for($i = 0; $i < 5; $i++){
                    $person = $people[$i];
                    $senderid = $person['sender_id'];
                    settype($senderid, "int");
                    $pic = "";
                    if($person['has_profile_pic'] == "1"){
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
                    Print'<a style="font-size:15pt;margin-top:3pt;" href="prof.php?name='.$person['sender_name'].'-'.$person['sender_id'].'"><div style="float:left;" class="profpic type4">';
                    Print'	<img class="pp" style="height:18pt;width:18pt" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
                    Print'</div>';
                    Print'<div class="userinfo">'.$person['sender_name'].'</a></div>';
                    Print'<a href="friends.php?send='.$user.'_'.$senderid.'" style="float: right;top: 25%;left: 93%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Add Friend</a>';
                    Print "</div>";               
                }
            }
            else{
                $number = $people->count();
                for ($i = 0; $i < $number; $i++){
                    $person = $people[$i];
                    $senderid = $person['sender_id'];
                    settype($senderid, "int");
                    $pic = "";
                    if($person['has_profile_pic'] == "1"){
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
                    Print'<a style="font-size:15pt;margin-top:3pt;" href="prof.php?name='.$person['sender_name'].'-'.$person['sender_id'].'"><div style="float:left;" class="profpic type4">';
                    Print'	<img class="pp" style="height:18pt;width:18pt" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
                    Print'</div>';
                    Print'<div class="userinfo">'.$person['sender_name'].'</a></div>';
                    Print'<a href="friends.php?send='.$user.'_'.$senderid.'" style="float: right;top: 25%;left: 93%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Add Friend</a>';
                    Print "</div>";  
                }
            }
            ?>
        </div>
        <div class="posts" id="groups">
        <?php
            if($groups->count() != 0){
                print '
            <h3 style="text-align:center">Groups</h3>
            <hr style="color:var(--lighter-head);width:60%;margin:auto;margin:bottom:5pt;">';
            }
            if($groups->count() >= 5){
                for($j = 0; $j < 5; $j++){
                    $group = $groups[$j];
                    Print'<div class="post" style="display:flex">';
                    print'    <div style="width: 70%;margin-left: 10%;display:inline-block;float:left;">';
                    print'        <a href="group.php?id='.$group['id'].'">';
                    print'            <h2 id="name">'.$group['group_name'].'';
                    if($group['verified_group'] == 1){
                        print'<i id="verified" class="fa fa-check"></i>';
                    }
                    print'</h2>';
                    print'        </a>';
                    print'        <p>'.htmlspecialchars_decode($group['group_description'], ENT_QUOTES).'</p>';
                    print'    </div>';
                    print'    <div class="join">';
                    if ($group['user_in_group'] == "0"){
                        if ($group['private_group'] == "1"){
                                Print'        <a href="joingroup.php?groupid='.$group['id'].'" class="joinbutton">Request to Join</a>';
                        }
                        else{
                            Print'        <a href="joingroup.php?groupid='.$group['id'].'" class="joinbutton">Join group</a>';
                        }
                    }
                    else{
                        Print'        <a href="leaveGroup.php?groupid='.$group['id'].'" class="joinbutton">Leave Group</a>';
                    }
                    print'    </div>';
                    print'</div>';              
                }
            }
            else{
                $number = $groups->count();
                for ($j = 0; $j < $number; $j++){
                    $group = $groups[$j];
                    Print'<div class="post" style="display:flex">';
                    print'    <div style="width: 70%;margin-left: 10%;display:inline-block;float:left;">';
                    print'        <a href="group.php?id='.$group['id'].'">';
                    print'            <h2 id="name">'.$group['group_name'].'';
                    if($group['verified_group'] == 1){
                        print'<i id="verified" class="fa fa-check"></i>';
                    }
                    print'</h2>';
                    print'        </a>';
                    print'        <p>'.htmlspecialchars_decode($group['group_description'], ENT_QUOTES).'</p>';
                    print'    </div>';
                    print'    <div class="join">';
                    if ($group['user_in_group'] == "0"){
                        if ($group['private_group'] == "1"){
                                Print'        <a href="joingroup.php?groupid='.$group['id'].'" class="joinbutton">Request to Join</a>';
                        }
                        else{
                            Print'        <a href="joingroup.php?groupid='.$group['id'].'" class="joinbutton">Join group</a>';
                        }
                    }
                    else{
                        Print'        <a href="leaveGroup.php?groupid='.$group['id'].'" class="joinbutton">Leave Group</a>';
                    }
                    print'    </div>';
                    print'</div>';  
                }
            }
            ?>
        </div>
        <div class="posts" id="post">
            <?php
            if($posts->count() != 0){
                print '
            <h3 style="text-align:center">Posts You Might Enjoy</h3>
            <hr style="color:var(--lighter-head);width:60%;margin:auto;margin:bottom:5pt;">';
            }
             if($posts->count() >= 5){
               for($k = 0; $k < 5; $k++){
                   $post = $posts[$k];
       $id = $post['user_id'];
       settype($id, "int");
       $pic = "";
       if($post['has_profile_pic'] == "1"){
           $inPicArray = checkArray($userimgs, $id);
           $pic = "";
           if($inPicArray == "yes"){
               $pic = $userimgs[$id];
           }
           if($inPicArray == "no"){
               sendtoServer($soc, $pair, "getpic", $id);
               $pic = serverRead($soc, $pair);
               $userimgs += [$id => $pic];
           }
       }
       $link = 'data:image/jpg;base64,'.$pic;
       Print '<div align="center" class="post">';
           print '<div style="display:flex;">';
           Print '<a style="font-size:15pt;margin-top:3pt;cursor:pointer" onclick="window.location.href=&quot;prof.php?name='.$post['user_name'].'-'.$post['user_id'].'&quot;"><div style="float:left;" class="profpic type4">';
               Print '<img class="pp" style="height:18pt;width:18pt;" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
           Print '</div>';
           Print '<div align="center" class="userinfo">'. $post['user_name'] .'</a></div>';
           if($user == $post['user_id']){
                       Print'<button class="accordion" id="navigationbutt" style="margin-left:auto">&#9776;</button>';
                       Print '<div class="panel" id="mobpanel">
                           <ul id="mobilenav">
                               <li class="editdiv"> <a style="font-size:15pt;" href="" onclick="window.top.location.href=&quot;edit.php?postid='. $post['id'] .'&quot;">edit</a></li>
                               <li class="editdiv"> <a style="font-size:15pt;" href="#delpost" onclick="myFunction('.$post['id'].')">delete</a></li>
                           </ul>
                       </div>';
                   }
           print '</div>';
           Print '<div align="center" class="dateinfo">'. $post['date_posted']."</div>";

           Print '<div align="center" class="details"><p style="overflow-wrap: break-word;">'. html_entity_decode(stripslashes($post['content'])) . "</p></div>";
           print '<div>';
     Print '<div align="center" class="dateinfo" id="likes">'.$post['likes'].' Likes</div>';
           if($post['public_post'] == "1"){
               Print '<div align="center" class="dateinfo" id="ppost">Public</div>';
           }
               if ($post['user_liked'] == "1"){
                   $likebutt = '<i class="fa fa-heart"></i>';
               }
               else{
                   $likebutt = '<i class="fa fa-heart-o"></i>';
               }
               Print '<br/><div class="deletediv">';
               print ' <button style="background: none;border: none;color: var(--other-accent);cursor:pointer;" onclick="window.location.href=&quot;like.php?userid='.$user.'&amp;postid='.$post['id'].'&amp;group=0&amp;cur='.urlencode($page).'&quot;">'.$likebutt.'</div>';
               print' <div class="deletediv">';
               print'<i style="cursor:pointer;" onclick="comments('.$post['id'].', 0, 0)" class="fa fa-comment"></i>';
               print'</div></div>';
       Print '</div><div style="height:0;border: 1px solid var(--lighter-head);margin-bottom:20pt;border-top:0" class="comments" id="'.$post['id'].' 0" ></div>';
           }
}
            else{
                $number = $posts->count();
                for ($k = 0; $k < $number; $k++){
                    $post = $posts[$k];
       $id = $post['user_id'];
       settype($id, "int");
       $pic = "";
       if($post['has_profile_pic'] == "1"){
           $inPicArray = checkArray($userimgs, $id);
           $pic = "";
           if($inPicArray == "yes"){
               $pic = $userimgs[$id];
           }
           if($inPicArray == "no"){
               sendtoServer($soc, $pair, "getpic", $id);
               $pic = serverRead($soc, $pair);
               $userimgs += [$id => $pic];
           }
       }
       $link = 'data:image/jpg;base64,'.$pic;
       Print '<div align="center" class="post">';
           print '<div style="display:flex;">';
           Print '<a style="font-size:15pt;margin-top:3pt;cursor:pointer" onclick="window.location.href=&quot;prof.php?name='.$post['user_name'].'-'.$post['user_id'].'&quot;"><div style="float:left;" class="profpic type4">';
               Print '<img class="pp" style="height:18pt;width:18pt;" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
           Print '</div>';
           Print '<div align="center" class="userinfo">'. $post['user_name'] .'</a></div>';
           if($user == $post['user_id']){
                       Print'<button class="accordion" id="navigationbutt" style="margin-left:auto">&#9776;</button>';
                       Print '<div class="panel" id="mobpanel">
                           <ul id="mobilenav">
                               <li class="editdiv"> <a style="font-size:15pt;" href="" onclick="window.top.location.href=&quot;edit.php?postid='. $post['id'] .'&quot;">edit</a></li>
                               <li class="editdiv"> <a style="font-size:15pt;" href="#delpost" onclick="myFunction('.$post['id'].')">delete</a></li>
                           </ul>
                       </div>';
                   }
           print '</div>';
           Print '<div align="center" class="dateinfo">'. $post['date_posted']."</div>";

           Print '<div align="center" class="details"><p style="overflow-wrap: break-word;">'. html_entity_decode(stripslashes($post['content'])) . "</p></div>";
           print '<div>';
     Print '<div align="center" class="dateinfo" id="likes">'.$post['likes'].' Likes</div>';
           if($post['public_post'] == "1"){
               Print '<div align="center" class="dateinfo" id="ppost">Public</div>';
           }
               if ($post['user_liked'] == "1"){
                   $likebutt = '<i class="fa fa-heart"></i>';
               }
               else{
                   $likebutt = '<i class="fa fa-heart-o"></i>';
               }
               Print '<br/><div class="deletediv">';
               print ' <button style="background: none;border: none;color: var(--other-accent);cursor:pointer;" onclick="window.location.href=&quot;like.php?userid='.$user.'&amp;postid='.$post['id'].'&amp;group=0&amp;cur='.urlencode($page).'&quot;">'.$likebutt.'</div>';
               print' <div class="deletediv">';
               print'<i style="cursor:pointer;" onclick="comments('.$post['id'].', 0, 0)" class="fa fa-comment"></i>';
               print'</div></div>';
       Print '</div><div style="height:0;border: 1px solid var(--lighter-head);margin-bottom:20pt;border-top:0" class="comments" id="'.$post['id'].' 0" ></div>';
                }
            }
            sendtoServer($soc, $pair, "quit");
            ?>
        </div>
  </body>
  <script type="text/javascript" src="./js/supportfunctions.js"></script>
	<script type="text/javascript" src="./js/colourMode.js"></script>
	<script type="text/javascript" src="./js/andriodCheck.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>