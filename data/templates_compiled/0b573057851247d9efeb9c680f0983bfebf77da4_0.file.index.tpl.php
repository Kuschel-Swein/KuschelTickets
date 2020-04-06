<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 11:52:17
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8afbd1a61451_83682801',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b573057851247d9efeb9c680f0983bfebf77da4' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin\\index.tpl',
      1 => 1585938271,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8afbd1a61451_83682801 (Smarty_Internal_Template $_smarty_tpl) {
?><h3>Willkommen im KuschelTickets ACP, <?php echo $_smarty_tpl->tpl_vars['__KT']->value['user']->getUserName();?>
</h3>
<p>Hier kannst du KuschelTickets konfigurieren, ebenfalls kannst du hier deine Konfigurationsdatei auf einer schönen Oberfläche verwalten.</p>
<p></p>
<?php if ($_smarty_tpl->tpl_vars['site']->value) {?>
<div class="ui warning message">
    <ul class="list">
        <li>Du verwendest eine veraltete Version von KuschelTickets! Die neuste Version findest du immer auf dem GitHub des Projekts.</li>
    </ul>
</div>
<?php }
}
}
