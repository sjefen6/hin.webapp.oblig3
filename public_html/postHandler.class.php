<?php
class postHandler{
	private $postArray;
//	private $filename;

	/** Se userHAndler.class.php*/
	function __construct($settings) {
//		$this->postArray = array();
//		$this->filename = $filename;
//		$this->readFile();

		$sql = "SELECT * FROM " . settings::getDbPrefix(). "posts";
		
		/*** fetch into an PDOStatement object ***/
		$stmt = settings::getDatabase() -> query($sql);

		/*** fetch into the animals class ***/
		$this -> userArray = $stmt -> fetchALL(PDO::FETCH_CLASS, 'posts');
	}

//	private function readFile(){
//		$xml = simplexml_load_file($this->filename);
//
//		foreach ($xml->post as $post) {
//			$this->postArray[] = new post(utf8_decode($post->id), utf8_decode($post->title), utf8_decode($post->time), utf8_decode($post->description));
//		}
//	}

	//TODO: fix
	public function getPost($id){
		/* Hent ut post med $id og overf�r den til Smarty  */
		foreach ($this->postArray as $post) {
			if ($id == $post->getId()) {
				$postArray = array('title' => $post->getTitle(),
					'time' => date("r", $post->getTime()),
					'content' => $post->getContent());
				return $postArray;
			}
		}
		return false;
	}

	public function getPosts($from, $to){
		/* Hent ut post med $id og overf�r den til Smarty  */
		
		$returnArray = array();

		if ($from > $to){
			return false;
		}
		$counter = 0;
		while($row = mysql_fetch_array($postArray)){
			if($counter < $from){
				// Not yet at $from
				;
			} else if($counter > $to){
				// Passed $from
				return $postArray;
			} else{
				$returnArray[] = array('id'=>$post['id'], 
						'title'=>$post['title'],
						'time'=>date("r",$post['time']),
						'content'=>$post['content']);
			}
			$counter++;
		}
		// There are no more posts available
		return $returnArray;
	}
	
	public function addPost($id, $title, $desc, $a_id){
		$this->postArray[] = new post($id, $title, time(), $desc, $a_id);
	}

//	public function save() {
//		$this->sortPosts();
//		
//		$xml_ny = "<blogposts>";
//		foreach ($this->postArray as $post) {
//			$xml_ny .=  "<post>\n".
//	                    "<id>" . utf8_encode($post->getId()). "</id>\n" .
//	                    "<title>" .utf8_encode($post->getTitle()). "</title>\n" .
//	                    "<time>" .utf8_encode($post->getTime()). "</time>\n" .
//	                    "<description><![CDATA[" .utf8_encode($post->getDesc()). "]]></description>\n" . 
//	                    "</post>\n";
//		}
//		$xml_ny .= "</blogposts>";
//
//		$xml = simplexml_load_string($xml_ny);
//
//		// Lagre endrede XML data til fil, skrivekasess til fil n�dvendig for apache web tjener
//		file_put_contents($this->filename,$xml->asXML());
//	}
	
	public function sortPosts(){
		usort($this -> postArray, array($this, 'sortByTime'));
	}
	
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

class post{
	private $url_id;
	private $title;
	private $time;
	private $content;
	private $author_id;

	function __construct($url_id, $title, $time, $desc, $a_id) {
		$this->url_id = $url_id;
		$this->title = $title;
		$this->time = $time;
		$this->content = $desc;
		$this->author_id = $a_id;
		
		$this->save(true);
	}

	public function getId(){
		return $this->url_id;
	}

	public function getTitle(){
		return $this->title;
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
			$sql = "INSERT INTO " . settings::getDbPrefix() . "posts " . 
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