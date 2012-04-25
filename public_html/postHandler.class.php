<?php
class postHandler{
	private $postArray;

	/** Se userHAndler.class.php*/
	function __construct() {
		/*
		 * SQL Query 
		 */
		$sql = "SELECT * FROM " . settings::getDbPrefix() . "posts ORDER BY time DESC";

		/*
		 * Prepare and execute the sql query 
		 */
		$stmt = settings::getDatabase() -> prepare($sql);
		$stmt->execute();

		/*
		 * Fetch into the userArray 
		 */
		$this -> postArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'post');
	}

//	private function readFile(){
//		$xml = simplexml_load_file($this->filename);
//
//		foreach ($xml->post as $post) {
//			$this->postArray[] = new post(utf8_decode($post->id), utf8_decode($post->title), utf8_decode($post->time), utf8_decode($post->description));
//		}
//	}

	//TODO: fix
	public function getPost($id, $comments, $users){
		/* Hent ut post med $id og overfï¿½r den til Smarty  */
		foreach ($this->postArray as $post) {
			if ($id == $post->getUrlId()) {
				$postArray = array('title' => $post->getTitle(),
					'time' => date("r", $post->getTime()),
					'desc' => $post->getContent(),
				'comments' => $comments->getCommentsForPage($post -> getId()));
				return $postArray;
			}
		}
		return false;
	}

	public function getPosts($from, $to){
		/* Hent ut post med $id og overf¿r den til Smarty  */
		
		$returnArray = array();

		if ($from > $to){
			return false;
		}
		$counter = 0;
		foreach ($this->postArray as $post) {
			if ($counter < $from){
				// We are not yet at $from
				;
			} else if ($counter > $to) {
				// We have passed $to
				return $postArray;
			} else {
				$returnArray[] = array('id' => $post->getUrlId(),
						'title' => $post->getTitle(),
						'time' => date("r", $post->getTime()),
						'desc' => $post->getContent());
			}
			$counter++;
		}
		// There are no more posts available
		return $returnArray;
	}
	
	public function addPost($title, $url_id, $author_id, $content){
		$this->postArray[] = new post($title, $url_id, time(), $author_id, $content);
		return true;
	}
}

class post{
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
	
	private function save($new = false){
		/*** The SQL SELECT statement ***/
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "posts " . 
			"(title, url_id, time, author_id, content) " . 
			"VALUES (:title, :url_id, :time, :author_id, :content);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "posts " .
			"SET title=:title, url_id=:url_id, time=:time, author_id=:author_id, content=:content " . 
        	"WHERE id=:id";
		}
		
		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase()->prepare($sql);

		/*** fetch into the animals class ***/
		if ($new){
			$stmt -> execute(array(':title' => $this -> title,
								':url_id' => $this -> url_id,
								':time' => $this -> time,
								':author_id' => $this -> author_id,
								':content' => $this -> content));
		} else {
			$stmt -> execute(array(':title' => $this -> title,
								':url_id' => $this -> url_id,
								':time' => $this -> time,
								':author_id' => $this -> author_id,
								':content' => $this -> content,
								':id' => $this -> id));
		}
	}
}
?>