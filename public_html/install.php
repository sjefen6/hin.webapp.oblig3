<?php
if (!file_exists("../settings.xml")) {
	header('Content-Type: text/html; charset=utf-8');
	require ('libs/Smarty.class.php');
	$smarty = new Smarty;
	
	$all = isset($_POST["user"]) && isset($_POST["pw"]) && isset($_POST["blogname"])
		&& isset($_POST["dbhost"]) && isset($_POST["dbname"])
		&& isset($_POST["dbuser"]) && isset($_POST["dbpw"])
		&& isset($_POST["dbprefix"]);
		
	$oneOrMore = isset($_POST["user"]) || isset($_POST["pw"]) || isset($_POST["blogname"])
		|| isset($_POST["dbhost"]) || isset($_POST["dbname"])
		|| isset($_POST["dbuser"]) || isset($_POST["dbpw"])
		|| isset($_POST["dbprefix"]);
	
	if ($all) {
			
			
			$user = mysql_real_escape_string($_POST["user"]);
			$pw = mysql_real_escape_string($_POST["pw"]);
			$blogname = mysql_real_escape_string($_POST["blogname"]);
			$dbhost = mysql_real_escape_string($_POST["dbhost"]);
			$dbname = mysql_real_escape_string($_POST["dbname"]);
			$dbuser = mysql_real_escape_string($_POST["dbuser"]);
			$dbpw = mysql_real_escape_string($_POST["dbpw"]);
			$dbprefix = mysql_real_escape_string($_POST["dbprefix"]);
			
			$createPosts = 
			"CREATE TABLE " . $dbprefix . "posts";
			
			
			
			$smarty->assign("message","Yep, it funk!");

	} else 	if ($oneOrMore) {
			$smarty->assign("message","Fill ALL fields (and get a html5 compadible browser)!");

	}else {
			$smarty->assign("message","Fill all fields!");
	}

	$smarty -> display('install.tpl');

} else {
	exit ;
}
?>