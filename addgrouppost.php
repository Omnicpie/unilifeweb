<?php
	//error_reporting(E_ERROR | E_PARSE);
	session_start();
	include 'servercom.php';
	if($_SESSION['user']){
	}
	else{
		header("location:index.php");
	}
    $user = $_SESSION['user'];
    function data_uri($file, $mime){
        $contents = file_get_contents($file);
        $base64 = base64_encode($contents);
        return $base64;
        #return('data:'.$mime.';base64,'.$base64);
    }
    function compressImage($source, $destination, $quality) {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);
        imagejpeg($image, $destination, $quality);
        return true;
    }
	if($_SERVER['REQUEST_METHOD'] = "POST") //Added an if to keep the page secured
	{
		$content = $_POST['content'];
		$ok = false;
		$groupid = $_POST['groupid'];
        $groupname = $_POST['groupname'];

		if (preg_match('/[a-zA-Z0-9]/', $content)) {
    		$ok = true;
		}
		else {
				$ok = false;
		}
		if ($ok){
     if(is_uploaded_file($_FILES['postpic']['tmp_name'])){
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["postpic"]["name"]);
            $uploadOk = 1;
            $mime = '';
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["postpic"]["tmp_name"]);
            if($check !== false) {
    
                $mime = $check["mime"];
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
            if ($_FILES["postpic"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                $perc = (1-($_FILES["postpic"]["size"] / 5000000))*100;
                if ($perc < 20){
                    $perc = 20;
                }
    
                if (compressImage($_FILES['postpic']['tmp_name'],$target_file,$perc)) {
                    $upload = data_uri($target_file, 'image/jpg');
                    unlink($target_file);
              			$soc = setupServer();
              			$pair = sendAES($soc);
              			$sanit = addslashes($details);
                    sendtoServer($soc, $pair, "grouppost", $groupid, $user, $content, $upload);
      			        sendtoServer($soc, $pair, "quit", "");
                    header("location: group.php?id=".$groupid);
                } else {
                    header("location: group.php?id=".$groupid);;
                }
            }
        }
        else{
      			$soc = setupServer();
      			$pair = sendAES($soc);
      			$sanit = addslashes($details);
      			sendtoServer($soc, $pair, "grouppost", $groupid, $user, $content, "NULL");
      			sendtoServer($soc, $pair, "quit", "");
      			header("location: group.php?id=".$groupid);
      }
		}
		else {
			Print '<script>alert("A post must contrain text!");</script>'; //Prompts the user
		}
	}
	else
	{
		header("location:home.php"); //redirects back to home
	}
?>
