<?php
// Used to add a new post on UniLife.
// File uploads developed with code from W3schools.
// https://www.w3schools.com/php/php_file_upload.asp
// accessed March 25th
	session_start();
	include 'servercom.php';
	if($_SESSION['user']){
	}
	else{
		//header("location:index");
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
		$details = $_POST['details'];
		$decision ="0";
		$ok = false;

		foreach($_POST['public'] as $each_check) //gets the data from the checkbox
 		{
 			if($each_check !=null ){ //checks if the checkbox is checked
 				$decision = "1"; //sets teh value
 			}
 		}
		if (preg_match('/[a-zA-Z0-9]/', $_POST['details'])) {
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
                    sendtoServer($soc, $pair, "post", $user, $sanit, $decision, $upload);
      			        sendtoServer($soc, $pair, "quit", "");
                    header("location: home.php?");
                } else {
                    header("location: home.php?");
                }
            }
        }
        else{
      			$soc = setupServer();
      			$pair = sendAES($soc);
      			$sanit = addslashes($details);
      			sendtoServer($soc, $pair, "post", $user, $sanit, $decision, "NULL");
      			sendtoServer($soc, $pair, "quit", "");
      			header("location: home.php");
      }
		}
		else {
			Print '<script>alert("A post must contrain text!");</script>'; //Prompts the user
			Print '<script>window.location.assign("home.php");</script>'; // redirects to home
		}
	}
	else
	{
		header("location:home.php"); //redirects back to hom
	}
?>
