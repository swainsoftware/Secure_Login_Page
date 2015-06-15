<!DOCTYPE html>
<?php
	include_once 'UserController.class.php';		// UserController.class.php includes User and DB classes
	session_start(); 								// start or continue the session
	if(isset($_SESSION["message"])) {				// check if there are any errors
		$message = $_SESSION["message"];			// get the message
		$_SESSION["message"] = null;				// clear out global variable
		echo ('<script type="text/javascript">alert("'.$message.'") </script>');
	}
?>
<html>
<head>
	<title>FSD Assignment PHP OO app ver1.7</title>
	<!-- by Andrei Rjabokon 09/06/2015 -->
	<!-- andrei.rjabokon att techelevate.net -->
	<!-- TE FSD Assignment, Digital Skills Academy -->
	<!-- Student ID: D14128497 -->
	<!-- updated 13/06/2015 -->
	<!-- ver1.7 -->
	<link rel="stylesheet" href="myCss.css">
</head>

<body>
	<h4>FSD Assignment PHP OO app ver1.7</h4>
	<h5>Register new user details</h5>

	<div id="registerDiv">

		<fieldset><legend>Register new user</legend>
			<form action="UserController.class.php" method="POST">	  
				<input type="text" name="newUserName" placeholder="Username *" size="25" maxlength="25" pattern="[A-Za-z0-9]{4,}" title="Can contain A-Z or a-z letters and digits, should be at least 6 characters" required></input>
				<input type="password" name="newPassWord" placeholder="Password *" size="25" maxlength="25" pattern=".{4,}" title="Minimum of 4 characters" required></input>
				<input type="password" name="confirmPassword" placeholder="Confirm Password *" size="25" maxlength="25" pattern=".{4,}" title="Minimum of 4 characters" required></input>
				<input type="email" name="newEmail" placeholder="Email *" size="25" maxlength="35" pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9.-]+\.[a-z]{2,3}$" title="Email could look like 'Some.Name_123@somewhere.com'" required></input>
				<input type="text" name="newName" placeholder="First Name" size="25"></input>
				<input type="text" name="newSecondName" placeholder="Second Name" size="25"></input>
				<label id="requiredLabel">* - Reguired fields</label>
				<br/>
				<input type="submit" name="registerNew" value="Register">
			</form>
		</fieldset>
		
		<br/>
		
		<div id="aDiv"><a href="index.php">Back</a></div>	
		
	</div>
</body>
</html>