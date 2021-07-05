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
        </style>
	</head>
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
  $groupid = (string) $_GET['groupid'];
 	sendtoServer($soc, $pair, "getgroup", $groupid, $user);
  $groupRead = serverRead($soc, $pair);
  $groupInfo = simplexml_load_string($groupRead);
  if ($groupInfo === false) {
      $errors = libxml_get_errors();
      foreach ($errors as $error) {
          echo display_xml_error($error, $groupInfo);
      }
      libxml_clear_errors();
	    }
  foreach($groupInfo->children() as $infoItem){
      $groupname = $infoItem['group_name'];
      $groupDes = $infoItem['group_description'];
      $groupPerm = $infoItem['default_permission'];
      $groupPriv = $infoItem['private_group'];
    }
	?>
	<body>
      <form action="grpupload.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="groupid" value=<?php print'"'.$_GET['groupid'].'"';?>>
          <p style="margin-top:8pt;">Group Name:</p>
             <?php Print'<textarea class="thintext" name="groupname" maxlength="50" required="required">'.$groupname.'</textarea><br/><br/>';?>
			    <p>Group Description:</p>
             <?php Print'<textarea class="fattext" name="groupdesc" maxlength="1000" required="required" >'.$groupDes.'</textarea><br/><br/>';?>
         <p>Private Group:</p>
         <div class="genSlect">
         <?php
             if($groupPriv == "0"){
                 Print '	<input type="radio" id="0" name="private" required value="0" checked><label for="0">Public</label><input type="radio" id="1" name="private" required value="1"><label for="1">Private</label>';
             }
             if($groupPriv == "1"){
                 Print '	<input type="radio" id="0" name="private" required value="0"><label for="0">Public</label><input type="radio" id="1" name="private" required value="1" checked><label for="1">Private</label>';
             }
         ?>
    			</div><br/><br/>
          <p>Default Group Permission:</p>
          <select name="perm" required="required">
          <?php
          if($groupPerm == "1"){
              Print'<option value="" disabled hidden>Select Permission Level</option>';
              print'<option value="1" selected>Admin User</option>';
              print'<option value="2" >Read and Write Posts</option>';
              print'<option value="3" >Read Post Only</option>';
          }
          else if($groupPerm == "2"){
              Print'<option value="" disabled hidden>Select Permission Level</option>';
              print'<option value="1">Admin User</option>';
              print'<option value="2" selected>Read and Write Posts</option>';
              print'<option value="3" >Read Post Only</option>';
          }
          else if($groupPerm == "3"){
              Print'<option value="" disabled hidden>Select Permission Level</option>';
              print'<option value="1">Admin User</option>';
              print'<option value="2" >Read and Write Posts</option>';
              print'<option value="3" selected>Read Post Only</option>';
          }
          else{
              Print'<option value="" selected disabled hidden>Select Permission Level</option>';
              print'<option value="1">Admin User</option>';
              print'<option value="2" >Read and Write Posts</option>';
              print'<option value="3">Read Post Only</option>';
          }
          ?>
          </select>
          <br/><br/>
          <h2 style="text-align:center; color:#fff">Select image to upload as group pic:</h2>
          <input hidden type="file" name="grouppic" id="grouppic" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
          <label for="grouppic"><span></span><strong><p style="margin:7pt 0;">Choose a file&hellip;</p></strong></label>
          <h2 style="text-align:center; color:#fff">Select image to upload as group banner</h2>
          <input hidden type="file" name="groupban" id="groupban" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
          <label for="groupban"><span></span><strong><p style="margin:7pt 0;">Choose a file&hellip;</p></strong></label>
          <input type="submit" value="Update Group" name="submit">
      </form>
		  <?php sendtoServer($soc, $pair, "quit", ""); ?>
	    <script type="text/javascript" src="./js/supportfunctions.js"></script>
	    <script type="text/javascript" src="./js/colourMode.js"></script>
	    <script type="text/javascript" src="./js/andriodCheck.js"></script>
		<script src="js/custom-file-input.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
	</body>
</html>