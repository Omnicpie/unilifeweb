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
?>
<html>
    <head>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="./styles/userhome.css">
        <link rel="stylesheet" type="text/css" href="./styles/profile.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="info" <?php
        sendtoServer($soc, $pair, "getban",$_GET['id']);
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
                <!--<img class="pp" src="./content/userph.png" alt="pp">-->
                <?php
                sendtoServer($soc, $pair, "getpic",$_GET['id']);
                $pic = serverRead($soc, $pair);
                sendtoServer($soc, $pair, "userinfo", $_GET['id']);
		            $doc = serverRead($soc, $pair);
        $usersdet = simplexml_load_string($doc);// or die("Failed to load");
        if ($usersdet === false) {
        $errors = libxml_get_errors();

        foreach ($errors as $error) {
            echo display_xml_error($error, $usersdet);
        }

        libxml_clear_errors();
    	}
        foreach($usersdet->children() as $userinf){
            $name = $userinf['first_name']. ' ' .$userinf['last_name'];
            $uni = $userinf['university'];
            $coursename = $userinf['course'];
            $dob = $userinf['date_of_birth'];
            $gender = $userinf['gender'];
            $study = ucfirst($userinf['study_type']);
            $datejoin = $userinf['date_joined'];
            $private = $userinf['private_profile'];
        }
                $link = 'data:image/jpg;base64,'.$pic;
                $alt = '"./content/userph.png"';

                Print '<img class="pp" src="'.$link.'"  onerror="this.onerror=null;this.src=\'./content/userph.png\';" />';
                ?>
            </div>
            <h1 class="name">
                <?php print $name; ?>
            <span class="joindate">
                <?php print $uni . '<br/>';?>
            </span>
            <span class="courseinfo">
                <?php print $coursename . ', '.$study.'<br style="margin-bottom:7pt;"/>';?>
            </span>
            <span class="joindate" style="font-size:9pt;margin-left:25pt">
                <?php print'Joined: '.$datejoin;?>
            </span>
            </h1>
            <?php
                if($user == $_GET['id']){
                    Print '<a onclick="window.top.location.href=&quot;./editprof.php&quot;" style="float: right;top: 73%;position: absolute;left: 79%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Edit Profile</a>';
                }
                else{
                    sendtoServer($soc, $pair, "friendstatus", $user, $_GET['id']);
                    $friendship = serverRead($soc, $pair);
                    if ($friendship == "1"){
                        Print '<a href="./friends.php?remove='.$user.'_'.$_GET['id'].'" style="float: right;top: 73%;position: absolute;left: 79%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Friends</a>';
                    }
                    elseif ($friendship == "2"){
                        Print '<a href="./friends.php?cancel='.$user.'_'.$_GET['id'].'" style="float: right;top: 73%;position: absolute;left: 79%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Request Sent</a>';
                    }
                    elseif ($friendship == "3"){
                        Print '<a href="./friends.php?accept='.$user.'_'.$_GET['id'].'" style="float: right;top: 73%;position: absolute;left: 72%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Accept</a>';
                        Print '<a href="./friends.php?deny='.$user.'_'.$_GET['id'].'" style="float: right;top: 73%;position: absolute;left: 79%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Decline</a>';
                    }
                    elseif ($friendship == "4"){
                        Print '<a href="./friends.php?send='.$user.'_'.$_GET['id'].'" style="float: right;top: 73%;position: absolute;left: 79%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Add Friend</a>';
                    }
                }
                sendtoServer($soc, $pair, "quit", "");
            ?>
        </div>
    </body>
</html>
