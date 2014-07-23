<?php /* Smarty version Smarty-3.1.15, created on 2014-04-03 11:09:21
         compiled from ".\view\master_template.html" */ ?>
<?php /*%%SmartyHeaderCode:18374533cf6bdcb80b9-11551529%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86e30f8a918099d722857a5441f1d05dcb3b6120' => 
    array (
      0 => '.\\view\\master_template.html',
      1 => 1396516064,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18374533cf6bdcb80b9-11551529',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_533cf6bddc4c93_77228787',
  'variables' => 
  array (
    'basedomain' => 0,
    'admin' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_533cf6bddc4c93_77228787')) {function content_533cf6bddc4c93_77228787($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("meta.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<body>
	<!-- RUNNING GLOBAL VAR -->
	<script>
		var basedomain = "<?php echo $_smarty_tpl->tpl_vars['basedomain']->value;?>
";
	</script>
	
	<!-- HEADER -->
    <div id="header">
		<?php if ($_smarty_tpl->tpl_vars['admin']->value) {?>
		<?php echo $_smarty_tpl->getSubTemplate ("menu.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php }?>
	</div>
	
	
	<!-- CONTENT -->
	<div id="body" class="home">
		<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		
	</div>
	
	
	<!-- FOOTER -->
    <footer id="footer">
		
		<?php echo $_smarty_tpl->getSubTemplate ("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	</footer>
	
</body> 
</html><?php }} ?>
