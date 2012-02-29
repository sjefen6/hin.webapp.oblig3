<?php
class menu{
	private $menuItems;

	function __construct() {
		$menuItems = array();
	}

	function addItem(menuItem $item) {
		$this->menuItems[] = $item;
	}

// 	function addArray(array $items) {
// 		array_push($this->menuItems, $items)
// 	}

	function getMenuArray() {
		$tempArray = array();

		foreach ($this->menuItems as $menuItem){
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
		$this->url = $url;
	}

	function getName() {
		return $name;
	}

	function getUrl() {
		return $url;
	}

	function getAsArray(){
		return array("name" => $this->name, "url" => $this->url);
	}
}

?>