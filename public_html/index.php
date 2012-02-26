<?php
error_reporting(E_ALL);
/*
 * Oblig 1
* @package Example-application
*/

require('libs/Smarty.class.php');
require('menu.class.php');

$smarty = new Smarty;

// $smarty->force_compile = true;
// $smarty->debugging = true;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

$menu = new menu();

$smarty->assign("title","Dette er overskriften",true);
$smarty->assign("subtitle","Dette er overskriften",true);

$smarty->assign('menu',$menu->getMenuArray());



$smarty->display('index.tpl');
?>