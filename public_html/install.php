<?php
if (!file_exists("../settings.xml")) {
	header('Content-Type: text/html; charset=utf-8');
	require ('libs/Smarty.class.php');
	$smarty = new Smarty;
	
	if (isset($_POST["user"]) && isset($_POST["pw"]) && isset($_POST["blogname"])
		&& isset($_POST["dbhost"]) && isset($_POST["dbname"])
		&& isset($_POST["dbuser"]) && isset($_POST["dbpw"])
		&& isset($_POST["dbprefix"])) {
			
			
			
			$smarty->assign("message","Yep, it funk!");

	} else 	if (isset($_POST["user"]) || isset($_POST["pw"]) || isset($_POST["blogname"])
		|| isset($_POST["dbhost"]) || isset($_POST["dbname"])
		|| isset($_POST["dbuser"]) || isset($_POST["dbpw"])
		|| isset($_POST["dbprefix"])) {
			$smarty->assign("message","Fill ALL fields (and get a html5 compadible browser)!");

	}else {
			$smarty->assign("message","Fill all fields!");
	}

	$smarty -> display('install.tpl');

} else {
	exit ;
}
?>