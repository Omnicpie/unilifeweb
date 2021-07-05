<?php
/*
	AUTHOR: Ryan Anderson
	Template page NOT LOGGED IN
*/
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<title>template not logged | UniLife</title>
		<?//Icons for URL/tab/bookmark area?>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
	</head>
	<body>
		<?php
			//Header with link to homepage, register and loging
		?>
		<header>
            <a class="logo" style="float:left;"href="index.php">
                <img class="imagelogo"src="./content/tempLogo.png" alt="UniLife">
            </a>
            <a style="float:right;padding-top:13pt;margin-right:3pt;" href="login.php">Login</a>
            <a style="float:right;padding-top:13pt;margin-right:5pt;" href="register.php">Register |</a>
        </header>
		<hr style="margin:0 0 60pt 0">
		<?php
			//CONTENT HERE!
		?>
    </body>
	<script type="text/javascript" src="./js/supportfunctions.js"></script>
	<script type="text/javascript" src="./js/colourMode.js"></script>
	<script type="text/javascript" src="./js/andriodCheck.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js"></script>
</html>
