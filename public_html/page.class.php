<?php

class page{
	private $file_name;
	private $xml;
	private $smarty;

	function __construct($filename, $smarty) {
		$this->file_name = $filename;
		$this->smarty = $smarty;
		$this->xml = simplexml_load_file($this->file_name);
	}

	public function printPost($id) {
		$mine_hilsner = array();
		$i=0;
		/* Hent ut info om hver hilsen, lagre dette i assosiativ tabell og overfør til Smarty  */
		foreach ($this->xml->pages->page as $page) {
			if ($id == $page->id) {
				$this->smarty->assign('title', utf8_decode($page->title));
				$this->smarty->assign('time', date("r", $page->time));
				$this->smarty->assign('description', utf8_decode($page->description));
				break;
			}
		}
		$this->smarty->display('page.tpl');
	}
}

?>