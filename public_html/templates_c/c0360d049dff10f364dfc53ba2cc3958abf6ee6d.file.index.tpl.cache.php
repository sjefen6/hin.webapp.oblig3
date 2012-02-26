<?php /* Smarty version Smarty-3.1.8, created on 2012-02-26 04:12:11
         compiled from "./templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4841633624f4999a2d3c5e4-98263113%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0360d049dff10f364dfc53ba2cc3958abf6ee6d' => 
    array (
      0 => './templates/index.tpl',
      1 => 1330225928,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4841633624f4999a2d3c5e4-98263113',
  'function' => 
  array (
  ),
  'cache_lifetime' => 120,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_4f4999a2f421e9_66436754',
  'variables' => 
  array (
    'title' => 1,
    'menu' => 0,
    'menuItem' => 0,
  ),
  'has_nocache_code' => true,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f4999a2f421e9_66436754')) {function content_4f4999a2f421e9_66436754($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
<title><?php echo '/*%%SmartyNocache:4841633624f4999a2d3c5e4-98263113%%*/<?php echo $_smarty_tpl->tpl_vars[\'title\']->value;?>
/*/%%SmartyNocache:4841633624f4999a2d3c5e4-98263113%%*/';?>
</title>
</head>
<body>
	<nav>
		<?php  $_smarty_tpl->tpl_vars['menuItem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menuItem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menuItem']->key => $_smarty_tpl->tpl_vars['menuItem']->value){
$_smarty_tpl->tpl_vars['menuItem']->_loop = true;
?>
		<a href="<?php echo $_smarty_tpl->tpl_vars['menuItem']->value->url;?>
"><?php echo $_smarty_tpl->tpl_vars['menuItem']->value->name;?>
</a> |
		<?php } ?>
	</nav>
</body>
</html>
<?php }} ?>