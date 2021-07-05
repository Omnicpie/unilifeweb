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
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Android | UniLife</title>
		<?//Icons for URL/tab/bookmark area?>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
	</head>
	<body style="text-align:center">
		<?php//Header with link to homepage, register and loging?>
		<header>
            <a class="logo" style="float:left;"href="index.php">
                <img class="imagelogo"src="./content/tempLogo.png" alt="UniLife">
            </a>
        </header>
        <hr style="margin:0 0 60pt 0">
		<?php//Form for logging into the site.?>
      <h1>We've deteteched that you are running on an Android Device</h1>
      <a style="font-size:12em;color:var(--accent-colour);" href="./extra/UniLife.apk"><i class="fa fa-android"></i></a>
      <h3>Click the Android logo to download our app for the best possible experience</h3>
	</body>
</html>