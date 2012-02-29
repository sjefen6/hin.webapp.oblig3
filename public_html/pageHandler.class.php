<?php

class pageHandler{
	private $pageArray;

	function __construct() {
		$this->pageArray = array();
	}

	public function readFile($filename){
		$xml = simplexml_load_file($filename);

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