<?php
/*
 * Oblig 1
* @package Example-application
*/
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Europe/Berlin");

require('libs/Smarty.class.php');
require('menu.class.php');
require('pageHandler.class.php');
require('postHandler.class.php');
require('userHandler.class.php');

$smarty = new Smarty;

// $smarty->force_compile = true;
// $smarty->debugging = true;
//$smarty->caching = false;
//$smarty->cache_lifetime = 120;
$smarty->assign("mode","default");

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
$posts = new postHandler("../blogg.xml");
$pages = new pageHandler("../pages.xml");
$menu = $pages->addToMenu($menu);

$smarty->assign('menu',$menu->getMenuArray());

/*
 * Login subutine
*/
$admin = false;
$failed = false;
$users = new userHandler("../users.xml");

if ($users -> verifySession()){
	$admin = true;
}

if (isset($_GET["login"])){
	if ($_GET["login"] == "in"){
		$admin = $users->verifyLogin($_POST["userId"], $_POST["password"]);
		if (!$admin){
			$failed =  true;
		}
	} else {
		$users -> logout();
		$admin = false;
	}
}
$smarty->assign("failed", $failed);
$smarty->assign("signedIn", $admin);

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
} else if (isset($_GET["admin"])) {
	if ($_GET["admin"] == "addPage" && $admin){
		if (isset($_POST["title"])){
			if ($pages->addPage($_POST["id"],$_POST["title"],$_POST["desc"])){
				$smarty->assign("mode","added");
			} else {
				$smarty->assign("mode","notAdded");
			}
		} else {
			$smarty->assign("mode","addPage");
		}
	} else if ($_GET["admin"] == "addPost" && $admin){
		if (isset($_POST["title"])){
			if ($posts->addPost($_POST["id"],$_POST["title"],$_POST["desc"])){
				$smarty->assign("mode","added");
			} else {
				$smarty->assign("mode","notAdded");
			}
		} else {
			$smarty->assign("mode","addPost");
		}
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