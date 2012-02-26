<?php
class menu{
	private $menuItems;

	function __construct() {
		$menuItems = array();
	}

	function getMenuArray() {
		$tempArray = array();
		
		foreach ($menuItems as $menuItem){
			$tempArray[] = $menuItem->getAsArray();
		}
		
		return $tempArray;
	}
}

class menuItem{
	private $name;
	private $url;

	function __construct($url, $name) {
		$this->name = $name;
		$this->$url = $url;
	}

	function getName() {
		return $name;
	}

	function getUrl() {
		return $url;
	}

	function getAsArray(){
		return array("name" => $name);
	}
}

?>