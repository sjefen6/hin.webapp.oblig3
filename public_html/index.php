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
$smarty->assign('archive',$posts->getArchive());

/*
 * Login subutine
*/
$users = new userHandler();
$user = $users -> getCurrentUser();

$failed = false;

if ($user == "failed"){
	$failed =  true;
	$smarty->assign("userLevel", 200);
} else if ($user != null && $user -> getUsermode() >= 1){
	$smarty->assign("userLevel", $user -> getUserlevel());
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
			if ($pages->addPage($_POST["title"],$_POST["url_id"],$user -> getId(),$_POST["content"])){
				$smarty->assign("mode","added");
			} else {
				$smarty->assign("mode","notAdded");
			}
		} else {
			$smarty->assign("mode","addPage");
		}
	} else if ($_GET["admin"] == "addPost"){
		if (isset($_POST["title"])){
			if ($posts->addPost($_POST["title"], $_POST["url_id"],$user -> getId(),$_POST["content"])){
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
		if ($users->addUser($_POST["userName"],$_POST["email"],
			$_POST["firstName"],$_POST["lastName"],
			$_POST["password"], 100, -1)){
			$smarty->assign("mode","addedUser");
		} else {
			$smarty->assign("mode","notAdded");
		}
	} else {
		$smarty->assign("mode","newUser");
	}
} else if (isset($_GET["search"])) {
	$temp = $posts->search($_GET["search"], $comments, $users);
	
	if ($temp != false){
		$smarty->assign("mode","bloglist");
		$smarty->assign("articles", $temp);
	} else {
		header("Status: 404 Not Found");
	}
} else if (@$_GET["login"] == "lostpw") {
	$users->lostpw($_POST["username"],$_POST["email"]);
	
	if (isset($_POST["username"]) && isset($_POST["email"])){
		if ($users->lostpw($_POST["username"],$_POST["email"])) {
			$smarty->assign("mode","addedUser");
		} else {
			header("Status: 404 Not Found");
		}
	} else {
		$smarty->assign("mode","lostpw");
	}
} else {
	$from = 0;
	$to = 10;
	
	if(isset($_GET["from"]) && isset($_GET["to"])){
		$from = $_GET["from"];
		$to = $_GET["to"];
	}
	
	$temp = $posts->getPosts($from, $to, $comments, $users);
	
	if ($temp != false){
		$smarty->assign("mode","bloglist");
		$smarty->assign("articles", $temp);
	} else {
		header("Status: 404 Not Found");
	}
}

$smarty->display('index.tpl');
?>