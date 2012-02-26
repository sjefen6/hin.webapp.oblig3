<?php /* Smarty version Smarty-3.1.8, created on 2012-02-26 14:55:40
         compiled from "./templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19281955084f4a34e815a745-56611737%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0360d049dff10f364dfc53ba2cc3958abf6ee6d' => 
    array (
      0 => './templates/index.tpl',
      1 => 1330264538,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19281955084f4a34e815a745-56611737',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_4f4a34e81cf162_10648535',
  'variables' => 
  array (
    'title' => 1,
    'subtitle' => 0,
    'menu' => 0,
    'menuItem' => 0,
    'articles' => 0,
    'article' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f4a34e81cf162_10648535')) {function content_4f4a34e81cf162_10648535($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
</head>
<body>
	<header>
		<hgroup>
			<h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
			<h2><?php echo $_smarty_tpl->tpl_vars['subtitle']->value;?>
</h2>
		</hgroup>
	</header>
	<nav>
		<?php  $_smarty_tpl->tpl_vars['menuItem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menuItem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menuItem']->key => $_smarty_tpl->tpl_vars['menuItem']->value){
$_smarty_tpl->tpl_vars['menuItem']->_loop = true;
?>
		<a href="<?php echo $_smarty_tpl->tpl_vars['menuItem']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['menuItem']->value['name'];?>
</a>
		<?php } ?>
	</nav>
	<section id="articles">
		<?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['articles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value){
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>
		<article>
			<h1><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</h1>
			<section class="articleContent">
			<?php echo $_smarty_tpl->tpl_vars['article']->value['content'];?>

			</section>
		</article>
		<?php } ?>
	</section>
</body>
</html><?php }} ?>