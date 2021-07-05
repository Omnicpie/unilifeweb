<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/userhome.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="styles/profile.css">
<script type="text/javascript" src="./js/supportfunctions.js"></script>
<script type="text/javascript" src="./js/colourMode.js"></script>
<script type="text/javascript" src="./js/andriodCheck.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
    </head>
    <body>
<?php
session_start(); //starts the session

libxml_use_internal_errors(true);
$user = $_SESSION['user']; //assigns user value
include 'servercom.php';
$soc = setupServer();
$pair = sendAES($soc);
$page = "getfeed.php";
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
    function display_xml_error($error, $xml)
    {
    $return  = $xml[$error->line - 1] . "\n";
    $return .= str_repeat('-', $error->column) . "^\n";

    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
         case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }

    $return .= trim($error->message) .
               "\n  Line: $error->line" .
               "\n  Column: $error->column";

    if ($error->file) {
        $return .= "\n  File: $error->file";
    }

    return "$return\n\n--------------------------------------------\n\n";
}
    ob_start();
	sendtoServer($soc, $pair, "feed", $user);
	$doc = serverRead($soc, $pair);
	$posts = simplexml_load_string($doc);
	if ($posts === false) {
    $errors = libxml_get_errors();

    foreach ($errors as $error) {
        echo display_xml_error($error, $posts);
    }

    libxml_clear_errors();
	}
    $userimgs = array();
    foreach($posts->children() as $post){
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
            Print '<a style="font-size:15pt;margin-top:3pt;" href="?" onclick="window.top.location.href=&quot;prof.php?name='.$post['user_name'].'-'.$post['user_id'].'&quot;"><div style="float:left;" class="profpic type4">';
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
            if($post['image'] != "NULL"){                
                $postpiclink = 'data:image/jpg;base64,'.$post['image'];
                print'<div class="pic" style="max-width:100%;max-height:200pt;">';
                Print '<img class="postpic" style="object-fit:scale;max-width:100%;max-height:200pt;" src="'.$postpiclink.'"  onerror="this.onerror=null;this.src=\'content/userph.png\';" />';
                print'</div>';
            }
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

    ob_end_flush();
	sendtoServer($soc, $pair, "quit", "");
?>
</body>
</html>
