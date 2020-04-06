<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 14:31:15
  from 'C:\xampp\htdocs\kuscheltickets\templates\Index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b2113d98da5_73097402',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b979f6dc561e06311fe37b629341d43d6e8fdbce' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\Index.tpl',
      1 => 1586089353,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b2113d98da5_73097402 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),1=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Startseite"), 0, false);
?>
<h1>Willkommen, <?php echo $_smarty_tpl->tpl_vars['__KT']->value['user']->getUserName();?>
</h1>
<div class="ui divider"></div>
<h3>deine letzten 10 Tickets</h3>
<table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Kategorie</th>
            <th>Datum</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tickets']->value, 'ticket');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ticket']->value) {
?>
        <tr>
            <td data-label="ID"><?php echo $_smarty_tpl->tpl_vars['ticket']->value->ticketID;?>
</td>
            <td data-label="Titel"><a href="<?php echo smarty_function_link(array('url'=>"ticket-".((string)$_smarty_tpl->tpl_vars['ticket']->value->ticketID)),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['ticket']->value->getTitle();?>
</a></td>
            <td data-label="Kategorie"><span class="ui label <?php echo $_smarty_tpl->tpl_vars['ticket']->value->getColor();?>
"><?php echo $_smarty_tpl->tpl_vars['ticket']->value->getCategory();?>
</span></td>
            <td data-label="Datum"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ticket']->value->getTime(),"%d.%m.%Y");?>
, <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ticket']->value->getTime(),"%H:%M");?>
 Uhr</td>
            <td data-label="Status"><div class="ui <?php echo $_smarty_tpl->tpl_vars['ticket']->value->getFormattedState("color");?>
 label"><?php echo $_smarty_tpl->tpl_vars['ticket']->value->getFormattedState("name");?>
</div></td>
        </tr>
        <?php
}
} else {
?>
        <tr>
            <td colspan="5">
                <div class="ui info message">
                    <ul class="list">
                        <li>Du hast noch keine Tickets erstellt.</li>
                    </ul>
                </div>
            </td>
        </tr>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </tbody>
</table>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
