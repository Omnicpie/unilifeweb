<?php
/*
	AUTHOR: Ryan Anderson
	Page provides users with their groups, some recommended and a way to make new groups
*/
?>
<html>
	<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="./styles/findgroupstyle.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Groups | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
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
        <div class="flex">
            <div class="left">
                <br style="height:10pt;"/>
                <a class="createGroup" href="creategroup.php"><i id="side" class="fa fa-plus"></i>Create Group</a>
                <hr style="color:var(--header-colour);opacity:0.2;"/>
                <h2 style="margin-left:5%;">Your Groups</h2>
                <div class="personalgroups">
                    <?php
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
                        $usersgroups = [];
                        sendtoServer($soc, $pair, "getusersgroups", $user);
                        $userGroups = serverRead($soc, $pair); 
    			              $uGroups = simplexml_load_string($userGroups) ;
                        #Print $userGroups;
                        if ($uGroups === false) {
                            $errors = libxml_get_errors();
                            foreach ($errors as $error) {
                                echo display_xml_error($error, $uGroups);
                            }
                            libxml_clear_errors();
                      	}
                        foreach($uGroups->children() as $usersgroup){
                            array_push($usersgroups, intval($usersgroup['id']));
                            sendtoServer($soc, $pair, "getgroup", $usersgroup['id'], $user);
                            $groupRead = serverRead($soc, $pair);
                            $groupInfo = simplexml_load_string($groupRead);
                            #Print $userGroups;
                            if ($groupInfo === false) {
                                $errors = libxml_get_errors();
                                foreach ($errors as $error) {
                                    echo display_xml_error($error, $groupInfo);
                                }
                                libxml_clear_errors();
                      	    }
                            foreach($groupInfo->children() as $infoItem){
                                $groupname = $infoItem['group_name'];
                                $groupdesc = $infoItem['group_desc'];
                                $verified  = intval($infoItem['verified_group']);
                                print '<a href="group.php?id='.$usersgroup['id'].'">';
                                print '    <div class="group">';
                                print '        <h3 id="name">'.$groupname;
                                if($verified == 1){
                                    print '<i id="verified" class="fa fa-check"></i>';
                                }
                                print '</h3>';
                                print '    </div>';
                                print '</a>';
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="right">
                <h2 align="center">Groups you might enjoy</h2>
                <div class="recommendgroup">
                <?php
                    sendtoServer($soc, $pair, "getgroups", $user);
                    $allGroups = serverRead($soc, $pair);
                    $allGroupsXML = simplexml_load_string($allGroups);
                    #Print $userGroups;
                    if ($allGroupsXML === false) {
                        $errors = libxml_get_errors();
                        foreach ($errors as $error) {
                            echo display_xml_error($error, $allGroupsXML);
                        }
                        libxml_clear_errors();
              	    }
                    foreach($allGroupsXML->children() as $groupXML){
                        if(!in_array(intval($groupXML['id']), $usersgroups)){
                            Print'<div class="groupRec">';
                            print'    <div class="info">';
                            print'        <a href="group.php?id='.$groupXML['id'].'">';
                            print'            <h2 id="name">'.$groupXML['group_name'].'';
                            if($groupXML['verified_group'] == 1){
                                print'<i id="verified" class="fa fa-check"></i>';
                            }
                            print'</h2>';
                            print'        </a>';
                            print'        <p>'.htmlspecialchars_decode($groupXML['group_description'], ENT_QUOTES).'</p>';
                            print'    </div>';
                            print'    <div class="join">';
                            if ($groupXML['private_group'] == "1"){
                                    Print'        <a href="joingroup.php?groupid='.$groupXML['id'].'" class="joinbutton">Request to Join</a>';
                            }
                            else{
                                Print'        <a href="joingroup.php?groupid='.$groupXML['id'].'" class="joinbutton">Join group</a>';
                            }
                            print'    </div>';
                            print'</div>';
                        }
                    }
                    sendtoServer($soc, $pair, "quit");
                    ?>
                </div>
    	      </div>
       </div>
	</body>
	<script type="text/javascript" src="./js/supportfunctions.js"></script>
	<script type="text/javascript" src="./js/colourMode.js"></script>
	<script type="text/javascript" src="./js/andriodCheck.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>
