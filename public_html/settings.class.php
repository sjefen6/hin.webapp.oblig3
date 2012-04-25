<?php

class settings {
	private static $filename;

	private static $name;
	private static $tagline;
	private static $dbhost;
	private static $dbuser;
	private static $dbpw;
	private static $dbname;
	private static $dbprefix;

	private static $database;

	function __construct($filename_, $name_=null, $tagline_=null, $dbhost_=null, $dbuser_=null, $dbpw_=null, $dbname_=null, $dbprefix_=null) {
		
		self::$filename = $filename_;
		
		if (!file_exists(self::$filename)) {
			if ($name_ == NULL || $tagline_ == NULL || $dbhost_ == null || $dbuser_ == null || $dbpw_ == null || $dbname_ == null || $dbprefix_ == null){
				die("Something is wrong, time to quit!");
			}
			self::$name = $name_;
			self::$tagline = $tagline_;
			self::$dbhost = $dbhost_;
			self::$dbuser = $dbuser_;
			self::$dbpw = $dbpw_;
			self::$dbname = $dbname_;
			self::$dbprefix = $dbprefix_;
			
			$this -> createSettings();
		}

		$this -> readFile();

		self::$database = new PDO('mysql:host=' . self::$dbhost . ';dbname=' . self::$dbname, self::$dbuser, self::$dbpw);
	}

	private function readFile() {
		$xml = simplexml_load_file(self::$filename);

		self::$name = utf8_decode($xml -> name);
		self::$tagline = utf8_decode($xml -> tagline);
		self::$dbhost = utf8_decode($xml -> database -> host);
		self::$dbuser = utf8_decode($xml -> database -> user);
		self::$dbpw = utf8_decode($xml -> database -> password);
		self::$dbname = utf8_decode($xml -> database -> name);
		self::$dbprefix = utf8_decode($xml -> database -> prefix);
	}

	public static function getName() {
		return self::$name;
	}

	public static function getTagline() {
		return self::$tagline;
	}
	
	public static function getDatabase() {
		return self::$database;
	}

	public static function getDbPrefix() {
		return self::$dbprefix;
	}

	private function createSettings() {
		if (!file_exists(self::$filename)) {
		$xml_ny = "<settings>\n" . 
			"\t<name>" . self::$name . "</name>\n" .
			"\t<tagline>" . self::$tagline . "</tagline>\n" .
			"\t<database>\n" .
				"\t\t<host>" . self::$dbhost . "</host>\n" .
				"\t\t<user>" . self::$dbuser . "</user>\n" .
				"\t\t<password>" . self::$dbpw . "</password>\n" .
				"\t\t<name>" . self::$dbname . "</name>\n" .
				"\t\t<prefix>" . self::$dbprefix . "</prefix>\n" .
			"\t</database>\n" .
			"</settings>";

		$xml = simplexml_load_string($xml_ny);

		// Lagre endrede XML data til fil, skrivekasess til fil n¿dvendig for apache web tjener
		file_put_contents(self::$filename, $xml -> asXML());
		} else {
			die("This is not even possible!");
		}
	}

}
?>