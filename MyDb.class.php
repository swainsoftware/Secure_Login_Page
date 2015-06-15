<?php
/* 	MyDb.class.php
	operations with mySQL db
	TE FSD Assignment, Digital Skills Academy 
	by Andrei Rjabokon 09/06/2015
	andrei.rjabokon att techelevate.net
	Student ID: D14128497
	ver1.7.1
	updated 15/06/2015

Variables:
db connection settings
db table fields
	
Methods:
	__construct() 						- make the connection with parameters given and test it
	getConnError() 						- error checking
	getUserData()						- check if this user is in db, return data array
	registerUser()						- register new user in db		
	createUser()						- create new user
	setLoggedIn()						- set flag user logged = true
	setLoggedOut()						- set flag user logged = false
*/

class MyDb {
																										// definitions
	private $myDbHandler;
	private $host = '';
	private $port = '';
	private $userName = '';
	private $pass = '';
	private $dbName = '';
	private $tableName = '';
	private $myQuery = '';
	private $dbConnect = '';
	private $connectionError = '';
	private $queryResult = '';
	
	private $fieldLogin = 'login';																		// field names in sql table
	private $fieldHash = 'hash';
	private $fieldSalt = 'salt';
	private $fieldFirstname = 'firstname';
	private $fieldSecondname = 'secondname';
	private $fieldEmail = 'email';
	private $fieldLogged = 'loggedin';
	
	public function __construct($host, $userName, $password, $dbName, $port, $tableName) {
		$this->host = $host;																			// setup connection details
		$this->userName = $userName;
		$this->pass = $password;
		$this->dbName = $dbName;
		$this->port = $port;
		$this->tableName = $tableName;
																										// create new connection
		$this->dbConnect = new mysqli($this->host, $this->userName, $this->pass, $this->dbName, $this->port);

		if ($this->getConnError() != 'Connection error... ') {											// check connection errors and report
			echo ($this->getConnError());	
		}
	}

	public function getConnError() {																	// check connection errors
		return $this->connectionError = "Connection error... " .mysqli_error($this->dbConnect);
	}
	
	public function registerUser($login, $hash, $salt, $firstname, $secondname, $email) {
	// INSERT INTO `users` (`login`, `hash`, `salt`, `firstname`, `secondname`, `email`) VALUES ([value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
	
																										// create prepared statement
		if ($stmt = $this->dbConnect->prepare("INSERT INTO `".$this->tableName."` (`".$this->fieldLogin."`, `".$this->fieldHash."`, `".$this->fieldSalt."`, `".$this->fieldFirstname."`, `".$this->fieldSecondname."`, `".$this->fieldEmail."`) VALUES (?,?,?,?,?,?)")) {

			$stmt->bind_param("ssssss", $login, $hash, $salt, $firstname, $secondname, $email); 		// bind parameters for markers
			$stmt->execute();																			// execute query
			$result = $stmt->affected_rows;																// keep number of affected rows in result
			$stmt->close();																				// close statement
			return $result;																				// return the result
		}
	}

	public function getUserData($login) {
	//SELECT * FROM `users` WHERE `login` = 'demo'
																										// create prepared statement
		if ($stmt = $this->dbConnect->prepare("SELECT * FROM ".$this->tableName." WHERE ".$this->fieldLogin." = ?")) {

			$stmt->bind_param("s", $login); 															// bind parameter for marker
			$stmt->execute();																			// execute query
			$result = $stmt->get_result();																// request result
			$myArray = $result->fetch_assoc();															// fetch associative array
			$stmt->close();																				// close statement
			return $myArray;																			// return the array
		}
	}

	public function setLoggedIn($login) {
		//UPDATE `users` SET `loggedin`=true WHERE `login`='demo'
																										// create a prepared statement
		if ($stmt = $this->dbConnect->prepare("UPDATE `".$this->tableName."` SET `loggedin`=true WHERE ".$this->fieldLogin." = ?")) {

			$stmt->bind_param("s", $login); 															// bind parameter for marker
			$stmt->execute();																			// execute query
			$result = $stmt->affected_rows;																// keep number of affected rows in result
			$stmt->close();																				// close statement
			return $result;																				// return the result	
		}
	}

	public function setLoggedOut($login) {
		//UPDATE `users` SET `loggedin`=false WHERE `login`='demo'
																										// create a prepared statement
		if ($stmt = $this->dbConnect->prepare("UPDATE `".$this->tableName."` SET `loggedin`=false WHERE ".$this->fieldLogin." = ?")) {

			$stmt->bind_param("s", $login); 															// bind parameter for marker
			$stmt->execute();																			// execute query
			$result = $stmt->affected_rows;																// keep number of affected rows in result
			$stmt->close();																				// close statement
			return $result;																				// return the result
		}
	}
}
?>