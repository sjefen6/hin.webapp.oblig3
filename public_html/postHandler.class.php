<?php

class postHandler{
	private $postArray;

	function __construct() {
		$this->postArray = array();
	}

	public function readFile($filename){
		$xml = simplexml_load_file($filename);

		foreach ($xml->post as $post) {
			$this->postArray[] = new post(utf8_decode($post->id), utf8_decode($post->title), utf8_decode($post->time), utf8_decode($post->description));
		}
	}

		public function getPost($id) {
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
	
	public function getMenuItem(){
		return new menuItem("?post=" . $this->id, $this->title);
	}
}
?>