<?php
class postHandler{
	private $postArray;
	private $filename;

	function __construct($filename) {
		$this->postArray = array();
		$this->filename = $filename;
		$this->readFile();
	}

	private function readFile(){
		$xml = simplexml_load_file($this->filename);

		foreach ($xml->post as $post) {
			$this->postArray[] = new post(utf8_decode($post->id), utf8_decode($post->title), utf8_decode($post->time), utf8_decode($post->description));
		}
	}

	public function getPost($id){
		/* Hent ut post med $id og overf¿r den til Smarty  */
		foreach ($this->postArray as $post) {
			if ($id == $post->getId()) {
				$postArray = array('title' => $post->getTitle(),
					'time' => date("r", $post->getTime()),
					'desc' => $post->getDesc());
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
				$returnArray[] = array('id' => $post->getId(),
						'title' => $post->getTitle(),
						'time' => date("r", $post->getTime()),
						'desc' => $post->getDesc());
			}
			$counter++;
		}
		// There are no more posts available
		return $returnArray;
	}
	
	public function addPost($id, $title, $desc){
		$this->postArray[] = new post($id, $title, time(), $desc);
		$this->save();
	}

	public function save() {
		$this->sortPosts();
		
		$xml_ny = "<blogposts>";
		foreach ($this->postArray as $post) {
			$xml_ny .=  "<post>\n".
	                    "<id>" . utf8_encode($post->getId()). "</id>\n" .
	                    "<title>" .utf8_encode($post->getTitle()). "</title>\n" .
	                    "<time>" .utf8_encode($post->getTime()). "</time>\n" .
	                    "<description><![CDATA[" .utf8_encode($post->getDesc()). "]]></description>\n" . 
	                    "</post>\n";
		}
		$xml_ny .= "</blogposts>";

		$xml = simplexml_load_string($xml_ny);

		// Lagre endrede XML data til fil, skrivekasess til fil nødvendig for apache web tjener
		file_put_contents($this->filename,$xml->asXML());
	}
	
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
}
?>