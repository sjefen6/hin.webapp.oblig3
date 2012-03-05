<?php
/*
 * Oblig 1
* @package Example-application
*/

require('libs/Smarty.class.php');
require('menu.class.php');
require('pageHandler.class.php');
require('postHandler.class.php');
require('userHandler.class.php');

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
$posts = new postHandler();
$posts->readFile("../blogg.xml");
$pages = new pageHandler();
$pages->readFile("../pages.xml");
$menu = $pages->addToMenu($menu);

$smarty->assign('menu',$menu->getMenuArray());

/*
 * Login subutine
 */
$users = new userHandler();
$users->readFile("../users.xml");
if (isset($_GET["login"])){
	if ($_GET["login"] == "in"){
		$temp = $users->verifyLogin($_POST["userId"], $_POST["password"]);
		$smarty->assign("signedIn", $temp);
		if (!$temp){
			$smarty->assign("failed", true);
		}
	}
}



/*
 * Main content switch
 */
if (isset($_GET["page"])) {
	$temp = $pages->getPage($_GET["page"]);
	if ($temp != false){
		$smarty->assign("mode","page");
		$smarty->assign("page", $temp);
	} else {
		header("Status: 404 Not Found");
	}
} else if (isset($_GET["post"])) {
	$temp = $posts->getPost($_GET["post"]);
	if ($temp != false){
		$smarty->assign("mode","post");
		$smarty->assign("post", $temp);
	} else {
		header("Status: 404 Not Found");
	}
} else {
	$temp = $posts->getPosts(0, 10);
	if ($temp != false){
		$smarty->assign("mode","bloglist");
		$smarty->assign("articles", $temp);
	} else {
		header("Status: 404 Not Found");
	}
}

$smarty->display('index.tpl');
?>