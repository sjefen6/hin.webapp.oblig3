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

	public function getPost($id, $comments, $users){
		/* Hent ut post med $id og overf�r den til Smarty  */
		foreach ($this->postArray as $post) {
			if ($id == $post->getUrlId()) {
				return $post->getSmarty($comments, $users);;
			}
		}
		return false;
	}
		
	public function getRealPost($id) {
		/* Hent ut post med $id og overf�r den til Smarty  */
		foreach ($this->postArray as $post) {
			return $post;
		}
		return false;	
	}
	
	public function search($needle, $comments, $users){
		/* Hent ut post med $id og overf�r den til Smarty  */
		$returnArray = array();

		foreach ($this->postArray as $post) {
			if (stristr ($post -> getTitle(), $needle) || stristr ($post -> getContent(), $needle)){
				$returnArray[] = $post->getSmarty($comments, $users);
			}
		}
		// There are no more posts available
		return $returnArray;
	}

	public function getPosts($from, $to, $comments, $users){
		/* Hent ut post med $id og overf�r den til Smarty  */
		
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
				return $returnArray;
			} else {
				$returnArray[] = $post->getSmarty($comments, $users);
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
	
	public function getArchive(){
		$returnArray = array();
		$month = date(F, current($this->postArray)->getTime());
		$year = date(Y, current($this->postArray)->getTime());
		$start = $end = $counter = 0;
		
		foreach($this->postArray as $post){
			if(date(F, $post->getTime()) == $month && date(Y, $post->getTime()) == $year){
				$end = $counter;
			} else {
				$returnArray[] = array('title' => $month . " " . $year,
						'start' => $start,
						'end' => $end);
				$month = date(F, $post->getTime());
				$year = date(Y, $post->getTime());
				$start = $end = $counter;
			}
			$counter++;
		}
		// OBOB, legg til den siste i arrayet
		$returnArray[] = array('title' => $month . " " . $year,
					'start' => $start,
					'end' => $end);
		return $returnArray;
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
	
	public function getSmarty($comments, $users){
		$user = $users->getUserById($this->author_id);
		return array('id' => $this -> getId(),
					'url_id' => $this -> url_id,
					'title' => $this->title,
					'time' => date("r", $this->time),
					'content' => $this->content,
					'author' => $user->getFirstname() . " " . $user->getLastname(),
					'comments' => $comments->getCommentsForPost($this -> getId(), $users));
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