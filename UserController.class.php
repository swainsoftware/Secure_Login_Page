<?php
/* 	UserController.class.php
	TE FSD Assignment, Digital Skills Academy
	User login operations
	by Andrei Rjabokon 09/06/2015
	andrei.rjabokon att techelevate.net
	Student ID: D14128497
	ver1.7.1
	updated 15/06/2015
	
Includes:
	MyDb.class.php
	
Variables:
	host 							// db connection settings
	port 
	userName 
	pass 
	dbName 
	tableName 
	myQuery 	
	
Methods:
	_construct()					// creates the UserController, creates db connection
	login()							// login procedure
	logout()						// logout procedure
	registerNewUser()				// register new user procedure
	getSalt()						// generate random salt for password hashing
	hashAndSaltMyPassword()			// generate hash using bcrypt Blowfish algo, provided password and salt
	sendEmail						// send confirmation email, check SNTP settings in your php.ini
	requestPasswordUpdate			// send password update email
*/

include 'MyDb.class.php';
//echo ('<script>console.log("in controller")</script>');

$myController = new UserController();

class UserController {
																			// declarations
	private $currentUser = null;

	private $myDbHandler = null;
	private $host = 'localhost';
	private $port = '3306';
	private $userName = 'root';
	private $pass = '';
	private $dbName = 'users';
	private $tableName = 'users';
	private $myQuery = '';

	public function __construct() {
		//echo ('<script>console.log("in controller construct")</script>');
		$this->myDbHandler = new MyDb($this->host, $this->userName, $this->pass, $this->dbName, $this->port, $this->tableName);

		if(isset($_POST["userName"]) ) {													// set in index.php page
			//echo ('<script>console.log("goto login")</script>');
			$this->login();
		}

		if(isset($_POST["registerNew"]) ) {													// set in register.php page
			//echo ('<script>console.log("goto register")</script>');
			$this->registerNewUser();
		}

		if(isset($_GET["logout"]) ) {														// set in homepage.php page
			//echo ('<script>console.log("goto logout")</script>');
			$this->logout();
		}

		if(isset($_POST["forgotPass"]) ) {													// set in forgot.php page
			//echo ('<script>console.log("goto request password")</script>');
			$this->requestPasswordUpdate();
		}
	}

	public function login() {																// well...
		session_start(); 																	// start or continue the session
		//echo ('<script>console.log("in login function")</script>');
		
		$usernameInput = trim($_POST["userName"]);											// get login from user
		$passwordInput = trim($_POST["passWord"]);											// get password from user
		//echo ('<script>console.log("login entry: '.$usernameInput.'")</script>');
		//echo ('<script>console.log("password entry: '.$passwordInput.'")</script>');

		$dbData = $this->myDbHandler->getUserData($usernameInput);							// an assoc array of all user data from db
		//echo ('<script>console.log("dbData: '.var_dump($dbData).'")</script>');
		//echo ('<script>console.log("db hash: '.$dbData["hash"].'")</script>');
		//echo ('<script>console.log("db salt: '.$dbData["salt"].'")</script>');
		//echo ('<script>console.log("db name: '.$dbData["firstname"].'")</script>');

		$tempUserHash = $this->hashAndSaltMyPassword($passwordInput, $dbData["salt"]);		// an assoc array of hash and salt based on CURRENT password and salt FROM DB
		//echo ('<script>console.log("temp hash: '.$tempUserHash["hash"].'")</script>');
		//echo ('<script>console.log("temp salt: '.$tempUserHash["salt"].'")</script>');
		
		if($tempUserHash["hash"] === $dbData["hash"]) {										// if hashes are equal, then
			//echo ('demo in');
			$_SESSION["login"] =	$dbData["login"];										// setup superglobals
			$_SESSION["username"] = $dbData["firstname"];
			$result = $this->myDbHandler->setLoggedIn($usernameInput);						// bd flag loggedin=true and return number of rows affected
			//echo ('<script>console.log("rows affected: '.$result.'")</script>');
			header("location:homepage.php");												// and goto homepage.php with secure content
		} else {
			//echo('keep digging');
			$_SESSION["message"] = "Wrong login or password, try again!";
			header("location:index.php");													// else back to index.php and display message
		}
	}

	public function logout() {
		session_start(); 																	// start or continue the session
		$usernameInput = $_SESSION["login"];												// get login from user
		$result = $this->myDbHandler->setLoggedOut($usernameInput);							// bd flag loggedin=flase and return number of rows affected
		//echo ('<script>console.log("rows affected: '.$result.'")</script>');
		$_SESSION["message"] = "User '".$usernameInput."' successfully logged out";			// prepare the message
		header("location:index.php");														// goto index.php and display message
	}	
	
	public function registerNewUser() {														// as it sais
		//echo ('<script>console.log("in register function")</script>');
		$newLogin = trim($_POST["newUserName"]);											// get variables from superglobals
		$newPassword = trim($_POST["newPassWord"]);
		$confirmPassword = trim($_POST["confirmPassword"]);
		$newFirstName = trim($_POST["newName"]);
		$newSecondName = trim($_POST["newSecondName"]);
		$newEmail = trim($_POST["newEmail"]);
		//echo ("<script>console.log('new login: ".$newLogin."')</script>");

		if ($newPassword != $confirmPassword) {												// check if passwords match
			session_start(); 																// start or continue the session
			$_SESSION["message"] = "Passwords do not match, please try again!";
			header("location:register.php");												// go back to register.php and display message 
		
		} else {
			$salt = $this->getSalt();														// generate random salt
			$hash = $this->hashAndSaltMyPassword($newPassword, $salt);						// generate hash based on password and salt
			//echo ("<script>console.log('new hash: ".$hash["hash"]."')</script>");
			//echo ("<script>console.log('new salt: ".$salt."')</script>");
		
			$dbData = $this->myDbHandler->getUserData($newLogin);							// get all user data from db
			//var_dump($dbData);
			//echo ("<script>console.log('dbdata: ".var_dump($dbData)."')</script>");
		
			if ($dbData === null) {															// check if such login exists. If not, then
				$result = $this->myDbHandler->registerUser($newLogin, $hash["hash"], $salt, $newFirstName, $newSecondName, $newEmail);  // create new user in db
				//echo ("<script>console.log('registerUser result: ".var_dump($result)."')</script>");

				session_start();															// start or continue the session
				$_SESSION["message"] = "Papers are al gud! Login now!";
			
				$message = "User '".$newEmail."' registered. Hello ".$newFirstName."!";						// create confirmation email body
				$subject = "Registration succsessful";														// create email subject
				$emailSent = $this->sendEmail($newEmail, $subject, $message);								// send a confirmation email if php.ini SMTP is ok!
				//echo ('<script>console.log("email email: '.$emailSent["email"].'")</script>');
				//echo ('<script>console.log("email login: '.$emailSent["login"].'")</script>');
				//echo ('<script>console.log("email message: '.$emailSent["message"].'")</script>');
				//echo ('<script>console.log("email sent: '.$emailSent["done"].'")</script>');
				header("location:index.php");																// goto index.php and display message
	
			} else {																						// if login is in use
				$_SESSION["message"] = "Login '".$newLogin."' is in use, please try another!";
				header("location:register.php");															// go back to register.php and display error message
			}
		}		
	}
	
	public function getSalt() {																// generate random salt for hashing 
		$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);									// creates an initialization vector from a 22 characters and /dev/random
		$salt = base64_encode($salt);
		$salt = str_replace('+', '.', $salt);
		return $salt;
	}

	public function hashAndSaltMyPassword($password, $salt) {								// generate hash
		$cost = '12';																		// base-2 logarithm of the iteration count
		$hash = crypt($password, '$2y$'.$cost.'$'.$salt.'$');								// bcrypt password with Blowfish algorythm
		$returnArray = array("hash" => $hash, "salt" => $salt);								// return an array of hash and salt
		//echo ('<script>console.log("hash: '.var_dump($returnArray).'")</script>');
		return $returnArray;
	}

	public function sendEmail($email, $subject, $message) {									// generate and send confirmation email
																							// !!! make sure your SMPT is setup in php.ini !!!
		$headers = 'From: webmaster@swain.atwebpages.com' . "\r\n" .
					'Reply-To: webmaster@swain.atwebpages.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		$done = mail($email, $subject, $message, $headers);									// Send email, returns boolean sent/not sent

		$returnArray = array("email" => $email, "login" => $login, "message" => $message, "done" =>$done);		// return an array for confirmaion
		return $returnArray;
	}

	public function requestPasswordUpdate() {
		session_start();
		$login = $_POST["loginName"];
		
		$dbData = $this->myDbHandler->getUserData($login);									// get all user data from db
		//echo ('<script>console.log("db name: '.$dbData["firstname"].'")</script>');
		
		if ($dbData != null) {																// if login exists then
			$requestEmail = $dbData["email"];
			$message = "User '".$login."' requested password";								// request password update email body.
			
																							// since I used one-way password hashing bcrypt/Blowfish algo,
																							// the passwords are not stored in db and can not be recovered.
																							// Instead, a link to dynamically generated webpage with 
																							// request to create NEW password for this user
																							// is inserted here, so NEW hash and salt are stored in db.
																							// This feature is coming in ver2.0 

			$subject = "Password recovery";													// email subject
			$emailSent = $this->sendEmail($requestEmail, $subject, $message);				// send a email if php.ini SMTP is ok!
			//echo ('<script>console.log("email email: '.$emailSent["email"].'")</script>');
			//echo ('<script>console.log("email login: '.$emailSent["login"].'")</script>');
			//echo ('<script>console.log("email message: '.$emailSent["message"].'")</script>');
			//echo ('<script>console.log("email sent: '.$emailSent["done"].'")</script>');
			$_SESSION["message"] = "Password update form sent to ".$requestEmail.", check your mailbox.";
			header("location:index.php");
		} else {
			$_SESSION["message"] = "Username ".$login." not found, please try again.";
			header("location:forgot.php");													// go back to forgot.php and display message
		}
	}
}	
?>