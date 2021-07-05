<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/creategroupstyle.css">
		<link rel="stylesheet" type="text/css" href="./styles/edpr.css">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Edit Profile | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
        <style>
        textarea{
          width:60%;
        }
        select{
            width:40%;
        }
        .request {
            width: 50%;
            margin: auto;
            border: 1px ridge var(--lighter-head);
        }
        #proflink {
            width: max-content;
            display: inline-block;
            height: max-content;
            margin-right: 50%;
        }
        #accept {
            padding: 2pt;
            background-color: var(--accent-colour);
            border-radius: 2px;
            margin-right: 8pt;
        }
        #deny {
            padding: 2pt;
            background-color: var(--other-accent);
            border-radius: 2px;
        }
        </style>
	</head>
 <body>
     <h1>Join Requests</h1>
     <div class="requests">
    <?php
	session_start(); //starts the session
	if($_SESSION['user']){ //checks if user is logged in
	}
	else{
	##	header("location:index"); // redirects if user is not logged in
	}
	$user = $_SESSION['user']; //assigns user value
	//ALL THIS IS TO GET THE USER'S FIRST NAME
	include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
	sendtoServer($soc, $pair, "getname", $user);
	$name = serverRead($soc, $pair);
	$split = explode(" ", $name);
	$fname = $split[0]; 
  if(isset($_GET['groupid'])){
     $groupid = (string) $_GET['groupid'];
 }
 else{
     $groupid = (string) $_POST['gid'];
 }
  sendtoServer($soc, $pair, "memberrequests", $groupid);
  $reqs = serverRead($soc, $pair);
   #Print $userGroups;
   $userRequests = simplexml_load_string($reqs);
   if ($userRequests === false) {
       $errors = libxml_get_errors();
       foreach ($errors as $error) {
           echo display_xml_error($error, $userRequests);
       }
       libxml_clear_errors();
	    }
   if ($userRequests->count() == 0){
     print '<p>no requests</p>';
   }
   else{
   foreach($userRequests->children() as $userRequest){
       $userid = $userRequest['user_id'];
       $username = $userRequest['user_name'];
       $requestdate  = $userRequest['date_sent'];
       print '<div class="request">';
       print '<a href="" id="proflink" onclick="window.top.location.href=&quot;prof.php?name='.$username.'-'.$userid.'&quot;">';
       print '    <div class="group">';
       print '        <h3 id="name">'.$username.'</h3>';
       print '    </div>';
       print '        <h4 id="sent">Sent: '.$requestdate.'</h4>';
       print '</a>';
       print '<a id="accept" href="grouprequests.php?op=a&groupid='.$groupid.'&userid='.$userid.'">Accept</a>';
       print '<a id="deny" href="grouprequests.php?op=d&groupid='.$groupid.'&userid='.$userid.'">Deny</a>';
       print '</div>'; 
   }
   }
  sendtoServer($soc, $pair, "quit");
	?>
 </div>
 </body>
</html>