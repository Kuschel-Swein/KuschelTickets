<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 12:21:25
  from 'C:\xampp\htdocs\kuscheltickets\templates\page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b02a50670f0_84516164',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b30b210bfad261f7636698703b6fd1a872a3b0d9' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\page.tpl',
      1 => 1586109179,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b02a50670f0_84516164 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0, false);
if ($_smarty_tpl->tpl_vars['type']->value == "2") {?>
    <?php $_smarty_tpl->_subTemplateRender("string:".((string)$_smarty_tpl->tpl_vars['content']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
} else { ?>
    <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }
$_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
