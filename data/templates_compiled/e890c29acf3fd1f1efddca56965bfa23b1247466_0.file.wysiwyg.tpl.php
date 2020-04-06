<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 15:52:48
  from 'C:\xampp\htdocs\kuscheltickets\templates\wysiwyg.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b3430ced027_04456952',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e890c29acf3fd1f1efddca56965bfa23b1247466' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\wysiwyg.tpl',
      1 => 1586180821,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8b3430ced027_04456952 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
/assets/tinymce/tinymce.min.js" referrerpolicy="origin"><?php echo '</script'; ?>
>
<?php if ($_smarty_tpl->tpl_vars['templates']->value == null) {?>
  <?php $_smarty_tpl->_assignInScope('templates', "true");
}
echo '<script'; ?>
>
tinymce.init({
    selector: "<?php echo $_smarty_tpl->tpl_vars['selector']->value;?>
",
    height: 300,
    language: "de",
    plugins: [
      "advlist autolink link image lists charmap preview hr anchor<?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.editor.templates") && $_smarty_tpl->tpl_vars['templates']->value == "true") {?> template<?php }?>",
      "searchreplace wordcount visualblocks code fullscreen media",
      "table paste help tabfocus toc"
    ],
    templates: KT.userTemplates,
    default_link_target: "_blank"
});
<?php echo '</script'; ?>
><?php }
}
