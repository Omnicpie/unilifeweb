<?php
/*
	AUTHOR: Ryan Anderson
	Page for logging into the website
*/

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/loginstyle.css">
		<title>Login | UniLife</title>
		<?//Icons for URL/tab/bookmark area?>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
	</head>
	<body>
		<?php//Header with link to homepage, register and loging?>
		<header>
            <a class="logo" style="float:left;"href="index.php">
                <img class="imagelogo"src="./content/tempLogo.png" alt="UniLife">
            </a>
            <a style="float:right;padding-top:13pt;margin-right:3pt;" href="login.php">Login</a>
            <a style="float:right;padding-top:13pt;margin-right:5pt;" href="register.php">Register |</a>
        </header>
        <hr style="margin:0 0 60pt 0">
		<?php//Form for logging into the site.?>
		<form class="entry" action="checklogin.php" method="post">
			<p>Enter Email:</p> <input type="text" name="email" required="required"/> <br/>
			<p>Enter Password:</p> <input type="password" name="password" required="required" /> <br/>
			<input type="submit" value="Login"/>
		</form>
	</body>
		<script type="text/javascript" src="./js/andriodCheck.js"></script>
</html>
