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
	<h5>Login recovery</h5>
	
	<div id="loginDiv">
		<fieldset><legend>Login recovery</legend>
			<form action="UserController.class.php" method="POST">
				<input type="text" name="loginName" placeholder="Username" size="25" maxlength="25" pattern=".{4,}" title="Minimum of 4 symbols" required></input>
				<input type="submit" name="forgotPass" value="Request update">
			</form>
		</fieldset>

		<br/>
		<div id="aDiv"><a href="index.php">Back</a></div>

	</div>

</body>
</html>