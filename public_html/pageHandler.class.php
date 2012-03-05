<?php

class pageHandler{
	private $pageArray;
	private $filename;

	function __construct($filename) {
		$this->pageArray = array();
		$this -> filename = $filename;
		$this -> readFile();
	}

	private function readFile(){
		$xml = simplexml_load_file($this -> filename);

		foreach ($xml->page as $page) {
			$this->pageArray[] = new page(utf8_decode($page->id), utf8_decode($page->title), utf8_decode($page->time), utf8_decode($page->description));
		}
	}

		public function getPage($id) {
			/* Hent ut post med $id og overf¿r den til Smarty  */
			foreach ($this->pageArray as $page) {
				if ($id == $page->getId()) {
					$pageArray = array('title' => $page->getTitle(),
					'time' => date("r", $page->getTime()),
					'desc' => $page->getDesc());
					return $pageArray;
				}
			}
			return false;	
		}

	public function addToMenu($menu){
		foreach ($this->pageArray as $page){
			$menu->addItem($page->getMenuItem());
		}
		return $menu;
	}
	
	public function addPage($id, $title, $desc){
		$this->pageArray[] = new page($id, $title, time(), $desc);
		$this->save();
	}
	
	public function save() {
	
		$xml_ny = "<pages>";
		foreach ($this->pageArray as $page) {
			$xml_ny .=  "<page>\n".
		                    "<id>" . utf8_encode($page->getId()). "</id>\n" .
		                    "<title>" .utf8_encode($page->getTitle()). "</title>\n" .
		                    "<time>" .utf8_encode($page->getTime()). "</time>\n" .
		                    "<description><![CDATA[" .utf8_encode($page->getDesc()). "]]></description>\n" . 
		                    "</page>\n";
		}
		$xml_ny .= "</pages>";
	
		$xml = simplexml_load_string($xml_ny);
	
		// Lagre endrede XML data til fil, skrivekasess til fil nødvendig for apache web tjener
		file_put_contents($this->filename,$xml->asXML());
	}
}

class page{
	private $id;
	private $title;
	private $time;
	private $description;

	function __construct($id, $title, $time, $desc) {
		$this->id = $id;
		$this->title = $title;
		$this->time = $time;
		$this->description = $desc;
	}

	public function getId(){
		return $this->id;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getTime(){
		return $this->time;
	}
	
	public function getDesc(){
		return $this->description;
	}
	
	public function getMenuItem(){
		return new menuItem("?page=" . $this->id, $this->title);
	}
}
?>