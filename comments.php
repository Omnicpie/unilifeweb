<?php
session_start(); //starts the session

libxml_use_internal_errors(true);
$user = $_SESSION['user']; //assigns user value
include 'servercom.php';
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $soc = setupServer();
    $pair = sendAES($soc);
    if($_POST['replyid'] == ""){
        sendtoServer($soc, $pair, "comment", $_POST['postid'], $_POST['group'], $user, $_POST['comment'], $_POST['isgroup']);
    }
    else{
        sendtoServer($soc, $pair, "comment", $_POST['postid'], $_POST['group'], $user, $_POST['comment'], $_POST['isgroup'], $_POST['replyid']);
    }
    sendtoServer($soc, $pair, "quit");
}
?>
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
    <div class="comments" style="height:129pt;overflow-y:auto;">
<?php
$soc = setupServer();
$pair = sendAES($soc);
$postid =  $_REQUEST['postid'];
$group =  $_REQUEST['group'];
$isgroup = $_REQUEST['isgroup'];
sendtoServer($soc, $pair, "getcomments", $postid, $isgroup);
$comments = serverRead($soc, $pair);
sendtoServer($soc, $pair, "quit");
$commentsXML = simplexml_load_string($comments);
if($commentsXML -> count() == 0){
    print'<p>No Comments yet</p>';
}
else{
    foreach($commentsXML -> children() as $comment){
        print '<div class="comment">';
        print '<p style="margin: 5px 0;padding-left:5pt;">'.$comment['user_name'].'<span style="font-size:9pt;padding-left:5pt">'.$comment['date_posted'].'</span></p>';
        print '<p style="margin-bottom:1px">'.$comment['content'].'</p>';
        print '<button style="background:0; border:0; color:var(--other-accent); margin:2px; cursor:pointer;" onclick="reply(&quot;'.$comment['id'].'&quot;,&quot;'.$comment['user_name'].'&quot;,&quot;'.$comment['date_posted'].'&quot;)"><i class="fa fa-reply"></i></button>';
        if ($user == $comment['user_id']){
            print '<button style="background:0; border:0; color:var(--other-accent); margin:2px; cursor:pointer;" onclick="window.location.href=&quot;delcomment.php?id='.$comment['id'].'&isgroup='.$isgroup.'&post='.$postid.'&quot;"><i class="fa fa-trash"></i></button>';
        }
        print '</div><hr style="color:var(--lighter-head);margin:0;"/>';
    }
}
?>
    </div>
    <div class="make" style="height:20pt;">
        <form action="comments.php" method="post" name="makeComment" style="height:20pt;width:60%;margin:auto;text-align:center">
            <input type="hidden" name="replyid">
            <?php
                print'<input type="hidden" name="postid" value="'.$postid.'"><input type="hidden" name="isgroup" value="'.$isgroup.'"><input type="hidden" name="group" value="'.$group.'">';
            ?>
            <textarea name="comment" style="height:20pt; display:inline-block; margin:auto"placeholder="Write a Comment..."></textarea>
            <button type="submit" style="background:0; border:0; font-size:20pt;color:var(--other-accent); cursor:pointer;"><i class="fa fa-send"></i></button>
        </form>
    </div>
<body>
</html>