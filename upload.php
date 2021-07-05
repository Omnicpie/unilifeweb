<?php
session_start();
include 'servercom.php';
$soc = setupServer();
$pair = sendAES($soc);
$user = $_SESSION['user']; //assigns user value
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
if(isset($_POST['submit'])){
    $ppstatus ="success";
    $banstatus ="success";
    if(!isset($_POST['coursestart'])){
        $_POST['coursestart'] = "0000-00-00";
    }
    if(!isset($_POST['courseend'])){
        $_POST['courseend'] = "0000-00-00";
    }
    if(!isset($_POST['studylevel'])){
        $_POST['studylevel'] = "null";
    }
    if(!isset($_POST['course'])){
        $_POST['course'] = "1";
    }
    sendtoServer($soc, $pair, "updateuser", $user, $_POST['fname'], $_POST['sname'], $_POST['studylevel'], $_POST['gender'], $_POST['course'], $_POST['private'], $_POST['coursestart'], $_POST['courseend']);
    if(is_uploaded_file($_FILES['profpic']['tmp_name'])){
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profpic"]["name"]);
        $uploadOk = 1;
        $mime = '';
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profpic"]["tmp_name"]);
        if($check !== false) {

            $mime = $check["mime"];
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["profpic"]["size"] > 5000000) {
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
            $perc = (1-($_FILES["profpic"]["size"] / 5000000))*100;
            if ($perc < 20){
                $perc = 20;
            }

            if (compressImage($_FILES['profpic']['tmp_name'],$target_file,$perc)) {
                $upload = data_uri($target_file, 'image/jpg');
                unlink($target_file);
                sendtoServer($soc, $pair, "profpic", $user, $upload);
                $d = serverRead($soc, $pair);
                echo $d;
                $ppstatus = "success";
            } else {
                $ppstatus ="fail";
            }
        }
    }
    if(is_uploaded_file($_FILES['profban']['tmp_name'])){
        $target_dir = "uploads/";
        $target_file_BAN = $target_dir . basename($_FILES["profban"]["name"]);
        $uploadOk = 1;
        $mime = '';
        $imageFileType = strtolower(pathinfo($target_file_BAN,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profban"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $mime = $check["mime"];
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["profban"]["size"] > 5000000) {
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
            $perc = (1-($_FILES["profban"]["size"] / 5000000))*100;
            if ($perc < 20){
                $perc = 20;
            }

            if (compressImage($_FILES['profban']['tmp_name'],$target_file_BAN,$perc)) {
                $upload = data_uri($target_file_BAN, 'image/jpg');
                unlink($target_file_BAN);
                sendtoServer($soc, $pair, "profban", $user, $upload);
                $doc = serverRead($soc, $pair);
                echo $doc;
                $banstatus ="success";
            } else {
                $banstatus ="fail";
            }
        }
    }
    sendtoServer($soc, $pair, "quit", "");
    if($banstatus == "success" && $ppstatus == "success"){
        header('location:editprof.php?status=Success');
    }
    else{
        header('location:editprof.php?status='.$banstatus.$ppstatus);
    }
}
?>
