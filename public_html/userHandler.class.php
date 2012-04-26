<?php

class userHandler {
	private $userArray;

	function __construct() {
		/*
		 * SQL Query 
		 */
		$sql = "SELECT * FROM " . settings::getDbPrefix() . "users";

		/*
		 * Prepare and execute the sql query 
		 */
		$stmt = settings::getDatabase() -> prepare($sql);
		$stmt->execute();

		/*
		 * Fetch into the userArray 
		 */
		$this -> userArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'user');
	}
	
	public function getCurrentUser(){
		if (isset($_GET["login"])) {
			if ($_GET["login"] == "out"){
				$this -> logout();
			}
		} else if (isset($_GET["user"]) && isset($_GET["vkey"])){
			$user = $this -> getUser($_GET["user"]);
			if ($user != null && $user -> getUsermode() >= -1 && $user -> verifyValidationkey($_GET["vkey"])){
				return $user;
			} else {
				return "failed";
			}
		} else if (isset($_POST["username"])){
			$user = $this -> getUser($_POST["username"]);
			if ($user != null && $user -> getUsermode() <= 1 && $user -> verifyPasword($_POST["password"])){
				return $user;
			} else {
				return "failed";
			}
		} else if (isset($_COOKIE["username"])){
			$user = $this -> getUser($_COOKIE["username"]);
			if ($user != null && $user -> verifySessionCookie($_COOKIE["session_cookie"])){
				return $user;
			}
		}
		
		return null;
	}
	
	private function getUser($username){
		foreach ($this->userArray as $user) {
			if ($user -> getUsername() == $username) {
				return $user;
			}
		}
		return null;
	}
	
	public function getUserById($id){
		foreach ($this->userArray as $user) {
			if ($user -> getId() == $id) {
				return $user;
			}
		}
		return null;
	}

	public function addUser($username, $email, $firstname, $lastname, $password, $userlevel, $usermode) {
			if ($_POST["confirmPassword"] === $password){
				if ($this -> getUser($_POST["userName"]) == NULL){
					$this -> userArray[] = new user($username, $email, $firstname, $lastname, $password, $userlevel, $usermode);
					return true;
				}
			}
		return false;
	}

	public function logout() {
		setcookie("username", "", time() + 1);
		setcookie("session_cookie", "", time() + 1);
	}

}

class user {
	private $id;
	private $username;
	private $email;
	private $firstname;
	private $lastname;
	private $password;
	private $salt;
	private $validationkey;
	private $session_cookie;
	private $usermode;
	private $userlevel;

	function __construct($username = null, $email = null, $firstname = null, $lastname = null, $password = null, $userlevel = null, $usermode = null) {
		if ($username != null || $email != null || $firstname != null || $lastname != null || $password != null || $userlevel != null || $usermode != null) {
			// Lets fill thows fields that needs some random stuff
			$this -> salt = $this -> random_gen(30);
			$this -> session_cookie = $this -> random_gen(30);
			$this -> validationkey = $this -> random_gen(30);

			$this -> username = $username;
			$this -> email = $email;
			$this -> firstname = $firstname;
			$this -> lastname = $lastname;
			$this -> setPassword($password);
			$this -> userlevel = $userlevel;
			$this -> usermode = $usermode;
			
			$this->sendRegisterValidation();

			$this -> save(true);
		}
	}

	public function getUserlevel() {
		return $this -> userlevel;
	}
	
	public function getUsermode() {
		return $this -> usermode;
	}
	
	public function getId() {
		return $this -> id;
	}
	
	public function getUsername() {
		return $this -> username;
	}

	private function setPassword($password) {
		$this -> password = sha1($password . $this -> salt);
	}

	public function verifyPasword($password) {
		if ($this -> password === sha1($password . $this -> salt)) {
			$this -> session_cookie = $this -> random_gen(30);
			$this -> save();
			setcookie("username", $this -> username);
			setcookie("session_cookie", $this -> session_cookie);
			return true;
		}
		return false;
	}

	public function verifyValidationkey($validationkey) {
		if ($this -> validationkey === $validationkey) {
			if ($this -> usermode < 0) {
				$this -> usermode = 1;
				$this -> save();
			}
		}
		return false;
	}

	public function verifySessionCookie($session_cookie) {
		if ($this -> session_cookie === $session_cookie) {
			return true;
		}
		return false;
	}
	
	private function sendRegisterValidation(){
		$to  = $this->email;
		$url = $_SERVER['HTTP_HOST']."/?user=" . $this -> username . "&vkey=" . $this->validationkey;
		
		$subject = "E-post validering kc blogg";
		
		$message = "Open this url to validate your e-mail adress: " . $url;
		
		$headers = 'From: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
		'Reply-To: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
		mail($to, $subject, $message, $headers);
	}

	private function random_gen($length) {
		// Source: http://deepakssn.blogspot.com/2006/06/php-random-string-generator-function.html

		$random = "";
		srand((double)microtime() * 1000000);
		$char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$char_list .= "abcdefghijklmnopqrstuvwxyz";
		$char_list .= "1234567890";
		// Add the special characters to $char_list if needed
		for ($i = 0; $i < $length; $i++) {
			$random .= substr($char_list, (rand() % (strlen($char_list))), 1);
		}
		return $random;
	}

	private function save($new = false) {
		/*** The SQL SELECT statement ***/
		if ($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "users " .
			"(username, email, firstname, lastname, " .
			"password, salt, validationkey, session_cookie, " .
			"usermode, userlevel) " .
			"VALUES (:username, :email, :firstname, :lastname, " .
			":password, :salt, :validationkey, :session_cookie, " .
			":usermode, :userlevel);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "users " .
			"SET username = :username, " .
			"email = :email, " .
			"firstname = :firstname, " .
			"lastname = :lastname, " .
			"password = :password, " .
			"salt = :salt, " .
			"validationkey = :validationkey, " .
			"session_cookie = :session_cookie, " .
			"usermode = :usermode, " .
			"userlevel = :userlevel " .
			"WHERE id = :id";
		}

		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase() -> prepare($sql);

		/*** run the query ***/
		if ($new) {
			$stmt -> execute(array(':username' => $this -> username,
					':email' => $this -> email,
					':firstname' => $this -> firstname,
					':lastname' => $this -> lastname,
					':password' => $this -> password,
					':salt' => $this -> salt,
					':validationkey' => $this -> validationkey,
					':session_cookie' => $this -> session_cookie,
					':usermode' => $this -> usermode,
					':userlevel' => $this -> userlevel));
		} else {
			$stmt -> execute(array(':username' => $this -> username,
					':email' => $this -> email,
					':firstname' => $this -> firstname,
					':lastname' => $this -> lastname,
					':password' => $this -> password,
					':salt' => $this -> salt,
					':validationkey' => $this -> validationkey,
					':session_cookie' => $this -> session_cookie,
					':usermode' => $this -> usermode,
					':userlevel' => $this -> userlevel,
					':id' => $this -> id));
		}
	}

}
?>