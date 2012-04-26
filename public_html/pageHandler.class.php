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

	public function getPage($id, $comments, $users) {
		/* Hent ut post med $id og overf�r den til Smarty  */
		foreach ($this->pageArray as $page) {
			if ($id == $page->getUrlId()) {
				return $page->getSmarty($comments, $users);
			}
		}
		return false;	
	}
	
	public function getRealPage($id) {
		/* Hent ut post med $id og overf�r den til Smarty  */
		foreach ($this->pageArray as $page) {
			return $page;
		}
		return false;	
	}

	public function addToMenu($menu){
		foreach ($this->pageArray as $page){
			$menu->addItem($page->getMenuItem());
		}
		return $menu;
	}
	
	public function addPage($title, $id, $author_id, $desc){
		$this->pageArray[] = new page($title, $id, time(), $author_id, $desc);
		return true;
	}
}

class page{
	private $id;
	private $title;
	private $url_id;
	private $time;
	private $author_id;
	private $content;

	function __construct($title=null, $url_id=null, $time=null, $author_id=null, $content=null) {
		if ($title != null || $url_id != null || $time != null || $author_id != null || $content != null) {
			$this->title = $title;
			$this->url_id = $url_id;
			$this->time = $time;
			$this->author_id = $author_id;
			$this->content = $content;
		
			$this->save(true);
		}
	}

	public function getId(){
		return $this->id;
	}
	
	public function getUrlId(){
		return $this->url_id;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getTime(){
		return $this->time;
	}
	
	public function getAuthorId(){
		return $this->author_id;
	}
	
	public function getContent(){
		return $this->content;
	}
	
	public function getMenuItem(){
		return new menuItem("?page=" . $this->url_id, $this->title);
	}
	
	public function getSmarty($comments, $users){
		$user = $users->getUserById($this->author_id);
		return array('id' => $this -> getId(),
					'url_id' => $this -> url_id,
					'title' => $this->title,
					'time' => date("r", $this->time),
					'content' => $this->content,
					'author' => $user->getFirstname() . " " . $user->getLastname(),
					'no_comments' => count($comments->getCommentsForPage($this -> getId(), $users)),
					'comments' => $comments->getCommentsForPage($this -> getId(), $users));
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