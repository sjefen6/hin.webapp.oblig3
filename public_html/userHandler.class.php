<?php

class userHandler {
	private $userArray;

	function __construct($settings) {
		/*** The SQL SELECT statement ***/
		$sql = "SELECT * FROM " . settings::getDbPrefix() . "users";

		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase() -> query($sql);

		/*** fetch into the animals class ***/
		$this -> userArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'users');
	}

	// private function readFile() {
	// $xml = simplexml_load_file($this -> filename);
	//
	// foreach ($xml->user as $user) {
	// $this -> userArray[] = new user(utf8_decode($user -> userId), utf8_decode($user -> password), utf8_decode($user -> lolSessionCookie));
	// }
	// }

	public function verifyLogin($userId, $password) {
		foreach ($this->userArray as $user) {
			if ($user -> getUserId() == $userId) {
				return $user -> verifyPasword($password);
			}
		}
		return false;
	}

	public function verifySession() {
		if (isset($_COOKIE["userId"]) && isset($_COOKIE["SessionCookie"])) {
			$userId = $_COOKIE["userId"];
			$sessionCookie = $_COOKIE["SessionCookie"];
			foreach ($this->userArray as $user) {
				if ($user -> getUserId() == $userId) {
					return $user -> verifySessionCookie($sessionCookie);
				}
			}
		}
		return false;
	}

	public function addUser() {
		setcookie("userId", "", time() + 1);
		setcookie("SessionCookie", "", time() + 1);
	}

	public function logout() {
		setcookie("userId", "", time() + 1);
		setcookie("SessionCookie", "", time() + 1);
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

	function __construct($username, $email, $firstname, $lastname, $password, $userlevel) {
		// Lets fill thows fields that needs some random stuff
		$this -> salt = random_gen(30);
		$this -> session_cookie = random_gen(30);
		$this -> validationkey = random_gen(30);
		
		$this -> username = $username;
		$this -> email = $email;
		$this -> firstname = $firstname;
		$this -> lastname = $lastname;
		$this -> setPassword($password);
		$this -> userlevel = $userlevel;
	}

	public function getUserId() {
		return $this -> userId;
	}

	private function setPassword($password) {
		$this -> password = sha1($password . $this -> salt);
	}

	public function verifyPasword($password) {
		if ($this -> password === sha1($password . $this -> salt)) {
			$this -> session_cookie = random_gen(30);
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
	
	private function save($new = false){
		/*** The SQL SELECT statement ***/
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "users " . 
			"(username, email, firstname, lastname, password, salt, validationkey, session_cookie, usermode, userlevel) " . 
			"VALUES (:username, :email, :firstname, :lastname, :password, :salt, :validationkey, :session_cookie, :usermode, :userlevel);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "users " .
			"SET username=:username, email=:email, firstname=:firstname, lastname=:lastname, " . 
			"password=:password, salt=:salt, validationkey=:validationkey, session_cookie=:session_cookie, " . 
			"usermode=:usermode, userlevel=:userlevel " . 
        	"WHERE id=:id";
		}
		
		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase()->prepare($sql);

		/*** fetch into the animals class ***/
		$$stmt -> execute(array(':id'=>$this -> id,
								':username'=>$this -> username,
								':email'=>$this -> email,
								':firstname'=>$this -> firstname,
								':lastname'=>$this -> lastname,
								':password'=>$this -> password,
								':salt'=>$this -> salt,
								':username'=>$this -> username,

								':title'=>$title));

		
	}

}
?>