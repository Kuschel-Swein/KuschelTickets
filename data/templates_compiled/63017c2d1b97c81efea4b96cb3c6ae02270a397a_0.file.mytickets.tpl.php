<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 13:22:52
  from 'C:\xampp\htdocs\kuscheltickets\templates\mytickets.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b110c93aca6_38960496',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '63017c2d1b97c81efea4b96cb3c6ae02270a397a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\mytickets.tpl',
      1 => 1586089360,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b110c93aca6_38960496 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),1=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Meine Tickets"), 0, false);
?>
<div class="ui container grid stackable form">
<div class="four wide column">
  <div class="field">
    <label>Suche</label>
    <input type="text" id="search_text" onchange="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 6);" onkeyup="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 6);">
  </div>
</div>
<div class="five wide column">
  <div class="field">
    <label>Typ</label>
    <div class="ui selection dropdown">
      <input type="hidden" id="search_type" onchange="utils.search(document.querySelector('#search_list'),  document.querySelector('#search_text'), this, 6);">
      <i class="dropdown icon"></i>
      <div class="default text"></div>
      <div class="menu">
        <div class="item" data-value="0">ID</div>
        <div class="item" data-value="1">Titel</div>
        <div class="item" data-value="2">Kategorie</div>
        <div class="item" data-value="3">Datum</div>
        <div class="item" data-value="4">Status</div>
      </div>
    </div>
  </div>
</div>
<div class="five wide column right floated">
  <br>
  <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.add")) {?>
    <a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"addticket"),$_smarty_tpl);?>
">Ticket erstellen</a>
  <?php }?>
</div>
</div>
<br>
<br>
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
<?php echo '<script'; ?>
>
$('.ui.selection.dropdown').dropdown();
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
