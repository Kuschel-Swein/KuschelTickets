<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 12:22:58
  from 'C:\xampp\htdocs\kuscheltickets\templates\accessdenied.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b03028323b0_77564756',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '44414cefe814c96aff883ad51284a15a5a8ae0ab' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\accessdenied.tpl',
      1 => 1585640615,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b03028323b0_77564756 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"403 - Zugriff verweigert"), 0, false);
?>
<div class="ui negative message">
    <div class="header">
        <h2>Du darfst diese Seite nicht aufrufen.</h2>
    </div>
    Du hast nicht die erforderliche berechtigung diese Seite aufzurufen. Wenn du denkst dies sei ein Fehler, wende dich bitte an <?php echo $_smarty_tpl->tpl_vars['__KT']->value['adminmail'];?>
.
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
