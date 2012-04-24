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

	function __construct($filename, $name=null, $tagline=null, $dbhost=null, $dbuser=null, $dbpw=null, $dbname=null, $dbprefix=null) {
		self::$filename = $filename;
		
		if (!file_exists(self::$filename)) {
			if ($name == NULL || $tagline == NULL || $dbhost == null || $dbuser == null || $dbpw == null || $dbname == null || $dbprefix == null){
				die("Something is wrong, time to quit!");
			}
			self::$name = $name;
			self::$tagline = $tagline;
			self::$dbhost = $dbhost;
			self::$dbuser = $dbuser;
			self::$dbpw = $dbpw;
			self::$dbname = $dbname;
			self::$dbprefix = $dbprefix;
			
			$this -> createSettings();
		}

		$this -> readFile();

		self::$database = new PDO('mysql:host=' . self::$dbhost . ';dbname=' . self::$dbname, self::$dbuser, self::$dbpw);
	}

	private function readFile() {
		$xml = simplexml_load_file(self::$filename);

		$name = utf8_decode($xml -> settings -> name);
		$dbhost = utf8_decode($xml -> database -> host);
		$dbuser = utf8_decode($xml -> database -> user);
		$dbpw = utf8_decode($xml -> database -> password);
		$dbname = utf8_decode($xml -> database -> name);
		$dbprefix = utf8_decode($xml -> database -> prefix);
	}

	public static function getDatabase() {
		return self::$database;
	}

	public static function getDbPrefix() {
		return self::$dbprefix;
	}

	private function createSettings() {
		$xml_ny = "<settings>\n" . 
			"\t<name>$this->name</name>\n" .
			"\t<tagline>$this->name</tagline>\n" .
			"\t<database>\n" .
				"\t\t<host>$this->dbhost</host>\n" .
				"\t\t<user>$this->dbuser</user>\n" .
				"\t\t<password>$this->dbpw</password>\n" .
				"\t\t<name>$this->dbname</name>\n" .
				"\t\t<prefix>$this->dbprefix</prefix>\n" .
				"\t</database>\n" .
			"\t</settings>";

		$xml = simplexml_load_string($xml_ny);

		// Lagre endrede XML data til fil, skrivekasess til fil n¿dvendig for apache web tjener
		file_put_contents(self::$filename, $xml -> asXML());
	}

}
?>