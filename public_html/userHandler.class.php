<?php

class userHandler {
	private $userArray;

	function __construct() {
		$this -> userArray = array();
	}

	public function readFile($filename) {
		$xml = simplexml_load_file($filename);

		foreach ($xml->user as $user) {
			$this -> userArray[] = new user(utf8_decode($user -> userId), utf8_decode($user -> password), utf8_decode($user -> lolSessionCookie));
		}
	}

	public function verifyLogin($userId, $password) {
		foreach ($this->userArray as $user) {
			if ($user -> getUserId() == $userId) {
				return $user -> verifyPasword($password);
			}
		}
		return false;
	}

}

class user {
	private $userId;
	private $password;
	private $lolSessionCookie;
	private $lolHash = "justSomeStupidSalt...ThisNeedsSomeImprovements!!!!!";

	function __construct($userId, $password, $lolSessionCookie) {
		$this -> userId = $userId;
		$this -> password = $password;
		$this -> lolSessionCookie = $lolSessionCookie;
	}

	public function getUserId() {
		return $this -> userId;
	}

	private function setPassword($password) {
		$this -> password = md5($password . $lolHash);
	}

	public function verifyPasword($password) {
		if ($this -> password === md5($password . $this -> lolHash)) {
			setcookie("userId", $this -> userId);
			setcookie("SessionCookie", $this -> lolSessionCookie);
			return true;
		}
		return false;
	}

	private function verifySessionCookie($lolSessionCookie) {
		if ($this->lolSessionCookie === $lolSessionCookie) {
			return true;
		}
		return false;
	}
}
?>