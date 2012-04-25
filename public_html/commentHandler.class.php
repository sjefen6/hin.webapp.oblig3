<?php
class commentHandler{
	private $commentArray;

	/** Se userHAndler.class.php*/
	function __construct() {
		$sql = "SELECT * FROM " . settings::getDbPrefix(). "comments";
		
		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase() -> query($sql);
		$stmt->execute();

		/*** fetch into the animals class ***/
		$this -> commentArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'comment');
	}
	
	/** Henter ut kommentarer gitt av postId */
	public function getCommentsForPost($postId){
		foreach ($this->commentArray as $comment) {
			if ($postId == $comment->getPostId() && $comment->getPageId() == NULL) {
				$commentArray = array('post_id' => $comment->getPostId(),
					'page_id' => NULL,
					'time' => date("r", $comment->getTime()),
					'author_id' => $comment->getAuthorId(),
					'content' => $comment->getContent());
			}
		}
		if($commentArray == null){
			return false;
		}else{
			return $commentArray;
		}
	}
	
	/** Henter ut kommentarer gitt av pageId */
	public function getCommentsForPage($pageId){
		foreach ($this->commentArray as $comment) {
			if ($pageId == $comment->getPageId() && $comment->getPostId() == NULL) {
				$commentArray = array('post_id' => NULL,
					'page_id' => $comment->getPageId(),
					'time' => date("r", $comment->getTime()),
					'author_id' => $comment->getAuthorId(),
					'content' => $comment->getContent());
				
			}
		}
		if($commentArray == null){
			return false;
		}else{
			return $commentArray;
		}
	}
	
	/** Legger til en ny kommentar. */
	public function addComment($postid, $page_id, $desc, $a_id){
		$this->commentArray[] = new comment($postid, $page_id, time(), $desc, $a_id);
	}

	/** Sorterer kommentarene i arrayet etter tid. */
	public function sortComments(){
		usort($this -> commentArray, array($this, 'sortByTime'));
	}
	
	/** Hjelpefunksjon for sortering. */
	private function sortByTime($a, $b) {
		if ($a->getTime() == $b->getTime()) {
			return 0;
		} else if ($a->getTime() > $b->getTime()) {
			return -1;
		} else {
			return 1;
		}
	}
}

class comment{
	private $post_id;
	private $page_id;
	private $time;
	private $content;
	private $author_id;

	function __construct($post_id, $page_id, $time, $desc, $a_id) {
		$this->post_id = $post_id;
		$this->page_id = $page_id;
		$this->time = $time;
		$this->content = $desc;
		$this->author_id = $a_id;
		
		$this->save(true);
	}

	public function getPostId(){
		return $this->post_id;
	}

	public function getPageId(){
		return $this->page_id;
	}

	public function getTime(){
		return $this->time;
	}

	public function getContent(){
		return $this->content;
	}
	
	public function getAuthorId(){
		return $this->author_id;
	}
	
	private function save($new = false){
		/*** The SQL SELECT statement ***/
		if($new) {
			$sql = "INSERT INTO " . settings::getDbPrefix() . "comments " . 
			"(page_id, post_id, time, author_id, content) " . 
			"VALUES (:post_id, :post_id, :time, :author_id, :content);";
		} else {
			$sql = "UPDATE " . settings::getDbPrefix() . "comments " .
			"SET post_id=:post_id, page_id=:page_id, time=:time, author_id=:author_id, content=:content " . 
        	"WHERE id=:id";
		}
		
		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase()->prepare($sql);

		/*** fetch into the animals class ***/
		if ($new){
			$stmt -> execute(array(':post_id'=>$this -> post_id,
								':page_id'=>$this -> page_id,
								':time'=>$this -> time,
								':author_id'=>$this -> author_id,
								':content'=>$this -> content));
		} else {
			$stmt -> execute(array(':id'=>$this -> id,
								':post_id'=>$this -> post_id,
								':page_id'=>$this -> page_id,
								':time'=>$this -> time,
								':author_id'=>$this -> author_id,
								':content'=>$this -> content));
		}
	}
}
?>