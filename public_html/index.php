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
require('commentHandler.class.php');
require('settings.class.php');

$settings = new settings("../settings.xml");
$smarty = new Smarty;

// $smarty->force_compile = true;
// $smarty->debugging = true;
//$smarty->caching = false;
//$smarty->cache_lifetime = 120;
$smarty->assign("mode","default");

/*
 * Litt info om nettsiden
*/
$smarty->assign("title",settings::getName());
$smarty->assign("subtitle",settings::getTagline());

/*
 * Opprett Menyen
*/
$menu = new menu();
$menu->addItem(new menuItem("/", "Home"));

/*
 * Les inn alle sidene slik at vi kan generere menyen
*/
$posts = new postHandler();
$pages = new pageHandler();
$comments = new commentHandler();
$menu = $pages->addToMenu($menu);

$smarty->assign('menu',$menu->getMenuArray());

/*
 * Login subutine
*/
$users = new userHandler();
$user = $users -> getCurrentUser();

//var_dump($user);

$admin = false;
$failed = false;

if ($user == "failed"){
	$failed =  true;
	$smarty->assign("userLevel", 200);
} else if ($user != null && $user -> getUserlevel() < 50){
	$smarty->assign("userLevel", $user -> getUserlevel());
	$admin = true;
} else {
	$smarty->assign("userLevel", 200);
}

$smarty->assign("failed", $failed);

/*
 * Main content switch
*/
if (isset($_GET["page"])) {
	$temp = $pages->getPage($_GET["page"], $comments, $users);
	if ($temp != false){
		if (isset($_POST["comment"])){
			$comments ->addComment(null, $pages->getRealPage($_GET["page"]) -> getId(), $_POST["comment"], $user -> getId());
		}
		$smarty->assign("mode","page");
		$smarty->assign("page", $temp);
	} else {
		header("Status: 404 Not Found");
	}
} else if (isset($_GET["post"])) {
	$temp = $posts->getPost($_GET["post"], $comments, $users);
	if ($temp != false){
		if (isset($_POST["comment"])){
			$comments ->addComment($posts->getRealPost($_GET["post"]) -> getId(), null, $_POST["comment"], $user -> getId());
		}
		$smarty->assign("mode","post");
		$smarty->assign("post", $temp);
	} else {
		header("Status: 404 Not Found");
	}
} else if (isset($_GET["admin"]) && $user != null && $user -> getUserlevel() < 50) {
	if ($_GET["admin"] == "addPage"){
		if (isset($_POST["title"])){
			if ($pages->addPage($_POST["title"],$_POST["id"],$user -> getId(),$_POST["desc"])){
				$smarty->assign("mode","added");
			} else {
				$smarty->assign("mode","notAdded");
			}
		} else {
			$smarty->assign("mode","addPage");
		}
	} else if ($_GET["admin"] == "addPost"){
		if (isset($_POST["title"])){
			if ($posts->addPost($_POST["title"], $_POST["id"],$user -> getId(),$_POST["desc"])){
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
} else if (@$_GET["login"] == "register"){
	if (isset($_POST["userName"])){
		if ($users->addUser($_POST["userName"],$_POST["password"],
				$_POST["confirmPassword"],$_POST["firstName"],
				$_POST["lastName"],$_POST["email"], 100, -1)){
			$smarty->assign("mode","userAdded");
		} else {
			$smarty->assign("mode","notAdded");
		}
	} else {
		$smarty->assign("mode","newUser");
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