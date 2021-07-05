<html>
<head>
        <link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="./styles/userhome.css">
        <link rel="stylesheet" type="text/css" href="./styles/profile.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php
session_start(); //starts the session
if($_SESSION['user']){ //checks if user is logged in
}
else{
    header("location:../index.php"); // redirects if user is not logged in
}
$user = $_SESSION['user']; //assigns user value
include 'servercom.php';
$soc = setupServer();
$pair = sendAES($soc);
$page = "profPosts.php?curruser=".$_GET['curruser']."&profuser=".$_GET['profuser'];
sendtoServer($soc, $pair, "userposts", $_GET['curruser'], $_GET['profuser']);
$doc = serverRead($soc, $pair);
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
	$posts = simplexml_load_string($doc);
	if ($posts === false) {
    $errors = libxml_get_errors();

    foreach ($errors as $error) {
        echo display_xml_error($error, $posts);
    }

    libxml_clear_errors();
	}
   if($posts->count() == "0"){
       echo '<p>No Posts to display</p>';
   }
   else{
            foreach($posts->children() as $post){
                Print '<div align="center" class="post">';
                    print '<div style="display:flex;">';
                    Print '<div align="center" class="userinfo">'. $post['user_name'] .'</div>';
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
                        Print '<br/><div class="deletediv"><button style="background: none;border: none;color: var(--other-accent);cursor:pointer;" onclick="window.location.href=&quot;like.php?userid='.$user.'&amp;postid='.$post['id'].'&amp;group=0&amp;cur='.urlencode($page).'&quot">'.$likebutt.'</div> <div class="deletediv">';
                        print'<i style="cursor:pointer;" onclick="comments('.$post['id'].', 0, 0)" class="fa fa-comment"></i>';
                print'</div></div>';
        Print '</div><div style="height:0;border: 1px solid var(--lighter-head);margin-bottom:20pt;border-top:0" class="comments" id="'.$post['id'].' 0" ></div>';
            }
            }
            sendtoServer($soc, $pair, "quit");
            ?>
</body>
<script type="text/javascript" src="./js/supportfunctions.js"></script>
<script type="text/javascript" src="./js/colourMode.js"></script>
<script type="text/javascript" src="./js/andriodCheck.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>