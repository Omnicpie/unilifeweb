<?php
session_start();
if(isset($_SESSION['user'])){
    if($_SESSION['user']){
        if($_SESSION['user'] != "")
            header("location:home.php");
    }
}
?>

<html>
	<head>
        <link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/regstyle.css">
		<link rel="stylesheet" type="text/css" href="./styles/indexstyle.css">
		<title>UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
		<script type="text/javascript" src="./js/andriodCheck.js"></script>
	</head>
	<body>
		<header>
            <a class="logo" style="float:left;"href="index.php">
                <img class="imagelogo" src="./content/tempLogo.png" alt="UniLife">
            </a>
            <a class="log" href="register.php"> Register </a>
        </header>

	</body>
	<br style="margin-bottom:11pt"/>
	<div style="display:flex;flex-direction:row;">
		<div class="bg" style="display:flex;flex-shrink:1;">
			<img class="bgim" src="./content/UL6.png">
		</div>
		<div class="centered"><h2>Connect with peers,</h2></div>
		<div class="centered" style="top: 50%;"><h2>Find societies,</h2></div>
		<div class="centered" style="top: 54%;"><h2>Make friends!</h2></div>
		<div class="loginPane" style="display:flex;flex-shrink:1;">
            <form class="entry" action="checklogin.php" method="post">
    			<p>Enter Email:</p> <input type="text" name="email" required="required"/> <br/>
    			<p>Enter Password:</p> <input type="password" name="password" required="required" /> <br/>
    			<input type="submit" value="Login"/>
    		</form>
		</div>
    </div>
</html>
