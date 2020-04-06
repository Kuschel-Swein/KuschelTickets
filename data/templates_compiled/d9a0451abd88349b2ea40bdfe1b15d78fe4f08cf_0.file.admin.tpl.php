<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 11:33:19
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8af75fed2710_04408277',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd9a0451abd88349b2ea40bdfe1b15d78fe4f08cf' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin.tpl',
      1 => 1586113497,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8af75fed2710_04408277 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>((string)$_smarty_tpl->tpl_vars['title']->value)." - Administration"), 0, false);
?>
<div class="ui container grid stackable">
    <div class="three wide column">
        <div class="ui styled accordion">
            <div class="title">
                <i class="dropdown icon"></i>
                Allgemein
            </div>
            <div class="content">
                <p>
                    <ul class="ui list">
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.dashboard")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin"),$_smarty_tpl);?>
">Dashboard</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.settings")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/settings"),$_smarty_tpl);?>
">Einstellungen</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.cleanup")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/cleanup"),$_smarty_tpl);?>
">Aufräumarbeiten</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.errors")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/errors"),$_smarty_tpl);?>
">Fehler</a>
                        <?php }?>
                    </ul>
                </p>
            </div>
            <div class="title">
                <i class="dropdown icon"></i>
                Module
            </div>
            <div class="content">
                <p>
                    <ul class="ui list">
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.faq")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/faq"),$_smarty_tpl);?>
">FAQ</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.faqcategories")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/faqcategories"),$_smarty_tpl);?>
">FAQ Kategorien</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.pages")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/pages"),$_smarty_tpl);?>
">Seiten</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.ticketcategories")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/ticketcategories"),$_smarty_tpl);?>
">Ticket Kategorien</a>
                        <?php }?>
                    </ul>
                </p>
            </div>
            <div class="title">
                <i class="dropdown icon"></i>
                Benutzer
            </div>
            <div class="content">
                <p>
                    <ul class="ui list">
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.accounts")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/accounts"),$_smarty_tpl);?>
">Accounts</a>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("admin.acp.page.groups")) {?>
                            <a class="item" href="<?php echo smarty_function_link(array('url'=>"admin/groups"),$_smarty_tpl);?>
">Benutzergruppen</a>
                        <?php }?>
                    </ul>
                </p>
            </div>
        </div>
    </div>
    <div class="thirteen wide column">
        <div class="ui segment">
            <h2 class="ui header">
                <?php echo $_smarty_tpl->tpl_vars['title']->value;?>

            </h2>
            <?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['file']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
>
$(document).ready(function(){
    $('.ui.accordion').accordion();
});
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
