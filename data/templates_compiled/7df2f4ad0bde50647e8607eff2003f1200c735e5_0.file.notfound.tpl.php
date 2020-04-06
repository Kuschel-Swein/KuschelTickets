<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 15:45:13
  from 'C:\xampp\htdocs\kuscheltickets\templates\notfound.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b32690f89e6_28963911',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7df2f4ad0bde50647e8607eff2003f1200c735e5' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\notfound.tpl',
      1 => 1585601068,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b32690f89e6_28963911 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"404 - Nicht gefunden"), 0, false);
?>
<div class="ui negative message">
    <div class="header">
        <h2>Diese Seite wurde nicht gefunden</h2>
    </div>
    Diese Seite wurde leider nicht gefunden. Wenn du denkst dies sei ein Fehler, wende dich bitte an <?php echo $_smarty_tpl->tpl_vars['__KT']->value['adminmail'];?>
.
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
