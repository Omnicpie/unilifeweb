<?php
    include 'servercom.php';
	$soc = setupServer();
	$pair = sendAES($soc);
    if(isset($_GET['send'])){
        $users = explode("_", $_GET['send']);
        echo 'send request';
        sendtoServer($soc, $pair, "friend", $users[0], $users[1]);
        sendtoServer($soc, $pair, "quit", "");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    elseif(isset($_GET['accept'])){
        $users = explode("_", $_GET['accept']);
        echo 'send request';
        sendtoServer($soc, $pair, "acceptFR", $users[0], $users[1]);
        sendtoServer($soc, $pair, "quit", "");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    elseif(isset($_GET['deny'])){
        $users = explode("_", $_GET['deny']);
        sendtoServer($soc, $pair, "denyFR", $users[0], $users[1]);
        sendtoServer($soc, $pair, "quit", "");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    elseif(isset($_GET['remove'])){
        $users = explode("_", $_GET['remove']);
        echo 'send request';
        sendtoServer($soc, $pair, "remFriend", $users[0], $users[1]);
        sendtoServer($soc, $pair, "quit", "");
        header('Location: ' . $_SERVER['HTTP_REFERER']);

    }
    elseif(isset($_GET['cancel'])){
        $users = explode("_", $_GET['cancel']);
        sendtoServer($soc, $pair, "cancelFR", $users[0], $users[1]);
        sendtoServer($soc, $pair, "quit", "");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
?>
