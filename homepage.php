<!DOCTYPE html>
<?php
	session_start(); 								// start or continue the session
	if (isset ($_SESSION["username"])) {
		$login = $_SESSION["login"];				// knowing login/userName we can get/set user info from db and show secure/personal content
		$userName = $_SESSION["username"];
	}
?>
<html>
<head>
	<title>User Homepage</title>
	<!-- by Andrei Rjabokon -->
	<!-- andrei.rjabokon att techelevate.net -->
	<!-- TE FSD Assignment, Digital Skills Academy -->
	<!-- Student ID: D14128497 -->
	<!-- 09/06/2015 -->
	<!-- updated 13/06/2015 -->
	<!-- ver1.7 -->
	<link rel="stylesheet" href="myCss.css">
</head>

<body>
	<h4>FSD Assignment PHP OO app ver1.7</h4>
	<h5>Secure Homepage</h5>

	<div id="userLoginDiv">
	<?php
		echo ('<div id="aDiv"><a href="UserController.class.php?logout">Logout</a></div>');
		echo ("User '".$login."' securely logged in.<br/><br/>");
		echo ("Hello ".$userName."! <br/><br/>");
		echo ("<hr>");

		include 'securecontent.txt';									// here we can display secure/personal content knowing $userName

		echo ("<hr>");
	?>
	</div>

</body>
</html>