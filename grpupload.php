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
    echo  $_POST['groupid'],'<br/>', $_POST['groupname'],'<br/>', $_POST['groupdesc'],'<br/>', $_POST['private'],'<br/>', $_POST['perm'],'<br/>';
    sendtoServer($soc, $pair, "updategroup", $_POST['groupid'], $_POST['groupname'], $_POST['groupdesc'], $_POST['private'], $_POST['perm']);
    if(is_uploaded_file($_FILES['grouppic']['tmp_name'])){
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["grouppic"]["name"]);
        $uploadOk = 1;
        $mime = '';
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["grouppic"]["tmp_name"]);
        if($check !== false) {

            $mime = $check["mime"];
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["grouppic"]["size"] > 5000000) {
            echo "Sorry, your file is too large.<br/>";
            $uploadOk = 0;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br/>";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            $perc = (1-($_FILES["grouppic"]["size"] / 5000000))*100;
            if ($perc < 20){
                $perc = 20;
            }

            if (compressImage($_FILES['grouppic']['tmp_name'],$target_file,$perc)) {
                $upload = data_uri($target_file, 'image/jpg');
                unlink($target_file);
                sendtoServer($soc, $pair, "updategrouppic", $_POST['groupid'], $upload);
                $doc = serverRead($soc, $pair);
                echo $doc;
                $ppstatus = "success";
            } else {
                $ppstatus ="fail";
            }
        }
    }
    if(is_uploaded_file($_FILES['groupban']['tmp_name'])){
        $target_dir = "uploads/";
        $target_file_BAN = $target_dir . basename($_FILES["groupban"]["name"]);
        $uploadOk = 1;
        $mime = '';
        $imageFileType = strtolower(pathinfo($target_file_BAN,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["groupban"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $mime = $check["mime"];
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["groupban"]["size"] > 5000000) {
            echo "Sorry, your file is too large.<br/>";
            $uploadOk = 0;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br/>";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            $perc = (1-($_FILES["groupban"]["size"] / 5000000))*100;
            if ($perc < 20){
                $perc = 20;
            }

            if (compressImage($_FILES['groupban']['tmp_name'],$target_file_BAN,$perc)) {
                $upload = data_uri($target_file_BAN, 'image/jpg');
                unlink($target_file_BAN);
                sendtoServer($soc, $pair, "updategroupban", $_POST['groupid'], $upload);
                $doc = serverRead($soc, $pair);
                echo $doc;
                $banstatus ="success";
            } else {
                $banstatus ="fail";
            }
        }
    }
    sendtoServer($soc, $pair, "quit", "");
    echo $ppstatus ,'<br/>';
    echo $banstatus, '<br/>';
    if($banstatus == "success" && $ppstatus == "success"){
        header('location:'.$_SERVER['HTTP_REFERER'].'?status=Success&groupid='. $_POST['groupid']);
    }
    else{
        header('location:'.$_SERVER['HTTP_REFERER'].'?status=fail&groupid='. $_POST['groupid']);
    }
}
?>
