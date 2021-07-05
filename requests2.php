<html>
<head>
    <link rel="stylesheet" type="text/css" href="./styles/variables.css">
    <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="./styles/profile.css">
    <style>
    </style>
</head>
<body>
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
    function utf8_fopen_read($fileName,$user) {
        $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
        $handle=fopen($user."REQUESTS-2.xml", "w");
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
    $requests = simplexml_load_string($doc);
	if ($requests === false) {
    $errors = libxml_get_errors();

    foreach ($errors as $error) {
        echo display_xml_error($error, $requests);
    }

    libxml_clear_errors();
	}
 $userimgs = array();
   if($requests->count() == 0){
     print '<p style="text-align:center">No Requests<br/>To View Recommendations, Click the View All button</p>' ;
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
        Print'<a style="font-size:15pt;margin-top:3pt;" onclick="window.top.location.href=&quot;prof.php?name='.$request['sender_name'].'-'.$request['sender_id'].'&quot;"><div style="float:left;" class="profpic type4">';
        Print'	<img class="pp" style="height:18pt;width:18pt" src="'.$link.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
        Print'</div>';
        Print'<div class="userinfo">'.$request['sender_name'].'</a></div>';
        Print'<a href="friends.php?deny='.$user.'_'.$senderid.'" style="float: right;top: 25%;left: 93%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Decline</a>';
        Print'<a href="friends.php?accept='.$user.'_'.$senderid.'" style="float: right;top: 25%;left: 88%;background-color: var(--accent-colour);border: none;color: var(--text-colour);padding: 11px 20px;text-decoration: none;margin: auto;cursor: pointer;height:auto; font-size:10pt;">Accept</a>';
        Print "</div>";
    }
}
    sendtoServer($soc, $pair, "quit", "");
?>
</body>
<html>
