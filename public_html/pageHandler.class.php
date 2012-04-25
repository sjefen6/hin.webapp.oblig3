<?php

class pageHandler{
	private $pageArray;

	function __construct() {
		/*
		 * SQL Query 
		 */
		$sql = "SELECT * FROM " . settings::getDbPrefix() . "pages";

		/*
		 * Prepare and execute the sql query 
		 */
		$stmt = settings::getDatabase() -> prepare($sql);
		$stmt->execute();

		/*
		 * Fetch into the userArray 
		 */
		$this -> pageArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'page');
	}

//	private function readFile(){
//		$xml = simplexml_load_file($this -> filename);
//
//		foreach ($xml->page as $page) {
//			$this->pageArray[] = new page(utf8_decode($page->id), utf8_decode($page->title), utf8_decode($page->time), utf8_decode($page->description));
//		}
//	}
	
	//TODO: fix?
	public function getPage($id) {
		/* Hent ut post med $id og overf¿r den til Smarty  */
		foreach ($this->pageArray as $page) {
			if ($id == $page->getId()) {
				$pageArray = array('title' => $page->getTitle(),
				'time' => date("r", $page->getTime()),
				'content' => $page->getContent());
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
	}
	
//	public function save() {
//	
//		$xml_ny = "<pages>";
//		foreach ($this->pageArray as $page) {
//			$xml_ny .=  "<page>\n".
//		                    "<id>" . utf8_encode($page->getId()). "</id>\n" .
//		                    "<title>" .utf8_encode($page->getTitle()). "</title>\n" .
//		                    "<time>" .utf8_encode($page->getTime()). "</time>\n" .
//		                    "<description><![CDATA[" .utf8_encode($page->getDesc()). "]]></description>\n" . 
//		                    "</page>\n";
//		}
//		$xml_ny .= "</pages>";
//	
//		$xml = simplexml_load_string($xml_ny);
//	
//		// Lagre endrede XML data til fil, skrivekasess til fil nødvendig for apache web tjener
//		file_put_contents($this->filename,$xml->asXML());
//	}
}

class page{
	private $post_id;
	private $url_id;
	private $time;
	private $author_id;
	private $content;

	function __construct($post_id, $url_id, $time, $author_id, $desc) {
		$this->id = $post_id;
		$this->url_id = $url_id;
		$this->time = $time;
		$this->author_id = $author_id;
		$this->content = $desc;
		
		$this->save(true);
	}

	public function getId(){
		return $this->id;
	}
	
	public function getPageId(){
		return $this->url_id;
	}
	
	public function getTime(){
		return $this->time;
	}
	
	public function getAuthorId(){
		return $this->author_id;
	}
	
	public function getContent(){
		return $this->description;
	}
	
	//TODO: fix
	public function getMenuItem(){
		return new menuItem("?page=" . $this->id, $this->title);
	}
	
	private function save($new = false){
		/*** The SQL SELECT statement ***/
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "pages " . 
			"(title, url_id, time, author_id, content) " . 
			"VALUES (:title, :url_id, :time, :author_id, :content);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "users " .
			"SET title=:title, url_id=:url_id, time=:time, author_id=:author_id, content=:content " . 
        	"WHERE id=:id";
		}
		
		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase()->prepare($sql);

		/*** fetch into the animals class ***/
		if ($new){
			$stmt -> execute(array(':title'=>$this -> title,
								':url_id'=>$this -> url_id,
								':time'=>$this -> time,
								':author_id'=>$this -> author_id,
								':content'=>$this -> content));
		} else {
			$stmt -> execute(array(':id'=>$this -> id,
								':title'=>$this -> title,
								':url_id'=>$this -> url_id,
								':time'=>$this -> time,
								':author_id'=>$this -> author_id,
								':content'=>$this -> content));
		}
	}
}
?>