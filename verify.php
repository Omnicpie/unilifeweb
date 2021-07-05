<?php
/*
	AUTHOR: Ryan Anderson
	Confirm email page
*/
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/regstyle.css">
		<title>Verify | UniLife</title>
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
                <img class="imagelogo" src="./content/tempLogo.png" alt="UniLife">
            </a>
            <a style="float:right;padding-top:13pt;margin-right:3pt;" href="login.php">Login</a>
            <a style="float:right;padding-top:13pt;margin-right:5pt;" href="register.php">Register |</a>
        </header>
		<hr style="margin:0 0 60pt 0">
   <h1>Verification code should be in your email. This is valid for 24hrs</h1>
		<form action="verify.php" method="post">
			<p>Email</p><input name="email" type="text"><br/><br/><br/>
			<p>Code</p><input name="code" type="text"><br/>
			<input type="submit" value="Register"/>
		</form>
    </body>
</html>
<?php
include_once 'servercom.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$soc = setupServer();
	$pair = sendAES($soc);
	$email = $_POST['email'];
	$code = $_POST['code'];
	sendtoServer($soc, $pair, "verify", $email, $code);
	$result = serverRead($soc, $pair);
	if($result == "DONE"){
		Print '<script>alert("Successfully Verified!");</script>'; // Prompts the user
		sendtoServer($soc, $pair, "userid", $email);
		$id = serverRead($soc, $pair);
		sendtoServer($soc, $pair, "quit", "");
    setcookie("FIRST_LOGIN", "1", time()+ (10 * 365 * 24 * 60 * 60));
		Print '<script>window.location.assign("login.php");</script>'; // redirects to register
	}
	else{
		sendtoServer($soc, $pair, "quit", "");
		Print '<script>alert("Verification code Wrong or used after expiration");</script>'; //Prompts the user
	}
}
