<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./styles/variables.css">
        <link rel="stylesheet" type="text/css" href="./styles/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="./styles/regstyle.css">
		<title>Register | UniLife</title>
        <link rel="icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="content/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" href="content/favicon-32x32.png">
	</head>
	<body>
		<header>
            <a class="logo" style="float:left;"href="index.php">
                <img style="height:40pt;"src="./content/tempLogo.png" alt="UniLife">
            </a>
            <a style="float:right;padding-top:13pt;margin-right:3pt;" href="login.php">Login</a>
            <a style="float:right;padding-top:13pt;margin-right:5pt;" href="register.php">Register |</a>
        </header>
        <hr style="margin:0 0 60pt 0">
		<form class="entry" action="register.php" method="post">
			<p>First Name:</p> <input type="text" name="fname" required="required" autocomplete="on" placeholder="Please enter your Firstname"/> <br/>
			<p>Surname:</p> <input type="text" name="sname" required="required" autocomplete="on" placeholder="Please enter your Surname"/> <br/>
			<p>Email:</p> <input type="email" name="email" required="required" autocomplete="on" placeholder="exmaple@university.ac.uk"/><br/>
			<p>University:</p> 
      <select name="uni" required="required">
      <option value="" selected disabled hidden>Select a University</option>
      <?php
      	include 'servercom.php';
      		$soc1 = setupServer();
      		$pair1 = sendAES($soc1);
          sendtoServer($soc1, $pair1, "unis", "");
          $uniunprep = serverRead($soc1, $pair1);
          sendtoServer($soc1, $pair1, "quit", "");
          $unis = simplexml_load_string($uniunprep);
          foreach($unis->children() as $uni){
              Print '<option value="'.$uni['uni_id'].'">'.$uni['uni_name'].'</option>';
          }
      ?>
      </select><br/>
			<p>Date of Birth:</p> <input type="date" name="dob" autocomplete="on" required="required"/> <br/>
			<p>Gender</p>
			<div class="genSlect">
				<input type="radio" id="m" name="gender" value="m"><label for="m">Male</label>
				<input type="radio" id="f" name="gender" value="f"><label for="f">Female</label>
				<input type="radio" id="o" name="gender" value="o"><label for="o">Other</label>
				<input type="radio" id="p" name="gender" value="n"><label for="p">Prefer not to say</label>
			</div>
			<br/>
			<p>Enter Password:</p> <input type="password" name="password" required="required" /> <br/>
			<p>Confirm Password:</p> <input type="password" name="password2" required="required" /> <br/>
			<input type="submit" value="Register"/>
		</form>
	</body>
		<script type="text/javascript" src="./js/andriodCheck.js"></script>
</html>

<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$soc = setupServer();
		$pair = sendAES($soc);
		$email = $_POST['email'];
		$fname = $_POST['fname'];
		$gender = $_POST['gender'];
		$sname = $_POST['sname'];
		$uni = $_POST['uni'];
		$dob = $_POST['dob'];
		$date = date("Y-m-d");//date
		$password = $_POST['password'];
	    $bool = true;
		//Checks for a University Email
		$at = '@';
		$dom = '.ac.uk';
		if(!(strpos($email, $at) && strpos($email, $dom))){
			$bool = false;
			Print '<script>alert("Email is invalid:\nPlease use your University Email.");</script>'; //Prompts the user
			//Print '<script>window.location.assign("register");</script>'; // redirects to register
		}
		if ($_POST['password'] !== $_POST['password2']) {
			$bool = false;
			Print '<script>alert("Passwords do not match.");</script>'; //Prompts the user
		}
		sendtoServer($soc, $pair, "emailcheck", $email);
        $emailout = serverRead($soc, $pair);
		if($emailout == "Y"){
			$bool = false;
			Print '<script>alert("That email is already registered!");</script>'; //Prompts the user
		}
		if($bool) // checks if bool is true
		{
    		sendtoServer($soc, $pair, "reguser", $email, $fname, $sname, $dob, $password, $gender, $uni);

			$_SESSION['user'] = $email;
            sendtoServer($soc, $pair, "quit", "");
			Print '<script>alert("Please Verify registraion\nwith link sent to email.");</script>'; // Prompts the user
			Print '<script>window.location.assign("verify.php");</script>'; // redirects to register
		}

	}
?>
