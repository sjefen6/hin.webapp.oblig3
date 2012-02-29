<?php
/*
 * Oblig 1
* @package Example-application
*/

require('libs/Smarty.class.php');
require('menu.class.php');
require('pageHandler.class.php');

$admin = false;

$smarty = new Smarty;

// $smarty->force_compile = true;
// $smarty->debugging = true;
$smarty->caching = false;
$smarty->cache_lifetime = 120;
$smarty->assign("mode","default");
$smarty->assign("admin",$admin);

/*
 * Litt info om nettsiden
 */
$smarty->assign("title","Dette er overskriften",true);
$smarty->assign("subtitle","Dette er under-overskriften",true);

/*
 * Opprett Menyen
 */
$menu = new menu();
$menu->addItem(new menuItem("/", "Home"));

/*
 * Les inn alle sidene slik at vi kan generere menyen
 */
$pages = new pageHandler();
$pages->readFile("../pages.xml");
$menu = $pages->addToMenu($menu);

$smarty->assign('menu',$menu->getMenuArray());

if (isset($_GET["page"])) {
	 $temp = $pages->getPage($_GET["page"]);
	 if ($temp != false){
	 	$smarty->assign("page", $temp);
	 	$smarty->assign("mode","page");
	 } else {
	 	header("Status: 404 Not Found");
	 }
} 

$smarty->display('index.tpl');
?>