<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 12:09:38
  from 'C:\xampp\htdocs\kuscheltickets\templates\codeeditor_smarty.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8affe2edda07_20539328',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e3a0ac77c55ca078ab8f050f67f33caa1f4ccfdd' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\codeeditor_smarty.tpl',
      1 => 1586104359,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8affe2edda07_20539328 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/codemirror/codemirror.css">
<style>
.CodeMirror {
  border: 1px solid #ddd;
}
</style>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/codemirror/codemirror.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/codemirror/smarty.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
var editor = CodeMirror.fromTextArea(document.querySelector("<?php echo $_smarty_tpl->tpl_vars['selector']->value;?>
"), {
  lineNumbers: true,
  mode: {
    name: "smarty",
    baseMode: "text/html",
    version: 3
  },
  indentWithTabs: true,
});
<?php echo '</script'; ?>
><?php }
}
