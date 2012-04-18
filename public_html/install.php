<?php
if (!file_exists ("../settings.xml")){
	header('Content-Type: text/html; charset=utf-8');
	require('libs/Smarty.class.php');
	$smarty = new Smarty;
	$smarty->display('install.tpl');
} else {
	exit;
}
?>
