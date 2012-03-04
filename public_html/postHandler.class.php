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
		$postArray = array();

		if ($from > $to){
			return false;
		}
		$counter = 0;
		foreach ($this->postArray as $post) {
			if ($counter < $from){
				// 				We are not yet at $from
				;
			} else if ($counter > $to) {
				// 				We have passed $to
				return $postArray;
			} else {
				$postArray[] = array('id' => $post->getId(),
						'title' => $post->getTitle(),
						'time' => date("r", $post->getTime()),
						'desc' => $post->getDesc());
			}
			$counter++;
		}
		// There are no more posts available
		return $postArray;
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