<?php

class settings {
	private $filename;

	private $name;
	private $dbhost;
	private $dbuser;
	private $dbpw;
	private $dbname;
	private $dbprefix;

	private $database;

	function __construct($filename) {
		$this -> filename = $filename;

		$this -> readFile();

		$database = new PDO('mysql:host=' . $this -> dbhost . ';dbname=' . $this -> dbname, $this -> dbuser, $this -> dbpw);
	}

	private function readFile() {
		$xml = simplexml_load_file($this -> filename);

		$name = utf8_decode($xml -> settings -> name);
		$dbhost = utf8_decode($xml -> database -> host);
		$dbuser = utf8_decode($xml -> database -> user);
		$dbpw = utf8_decode($xml -> database -> password);
		$dbname = utf8_decode($xml -> database -> name);
		$dbprefix = utf8_decode($xml -> database -> prefix);
	}

	public function getDatabae() {
		return $this -> database;
	}

	public function getDbPrefix() {
		return $this -> dbprefix;
	}

	public function createSettings() {
		$xml_ny = "<settings\n" . "\t<name>$this->name</name>\n" . "\t<database>\n" . "\t\t<host>$this->dbhost</host>\n" . "\t\t<user>$this->dbuser</user>\n" . "\t\t<password>$this->dbpw</password>\n" . "\t\t<name>$this->dbname</name>\n" . "\t\t<prefix>$this->dbprefix</prefix>\n" . "\t</database>\n" . "\t</settings>";

		$xml = simplexml_load_string($xml_ny);

		// Lagre endrede XML data til fil, skrivekasess til fil n¿dvendig for apache web tjener
		file_put_contents($this -> filename, $xml -> asXML());
	}

}
?>