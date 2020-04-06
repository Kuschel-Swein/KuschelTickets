<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 11:33:56
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin\cleanup.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8af7849f8df7_58913786',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2fdc678cfc2bab6dcfd85868d888f17f60451499' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin\\cleanup.tpl',
      1 => 1586165631,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8af7849f8df7_58913786 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
?>
<div class="ui info message">
    <ul class="list">
        <li>Die Aufräumarbeiten können verwendet werden, wenn die Datenbank zu groß wird oder zu viele Fehler Protokolle existieren, sie löschen vorgegebene Inhalte permanent.</li>
        <li>Deine Datenbank ist aktuell <?php echo $_smarty_tpl->tpl_vars['site']->value['dbsize'];?>
 MB groß.</li>
        <li>Achte darauf, dass du deine Fehler Protokolle regelmäßig löschst, da von diesem um Speicherplatz zu sparen maximal <b>400</b> auf einmal existieren können.</li>
    </ul>
</div>
<form action="<?php echo smarty_function_link(array('url'=>"admin/cleanup"),$_smarty_tpl);?>
" method="post" class="ui form<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['sure'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>">
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="notifications"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['notifications']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
        <label>alle als gelesen markierte Benachrichtigungen löschen.</label>
        <small class="helper">ca. <?php echo $_smarty_tpl->tpl_vars['site']->value['readnotifications'];?>
 Datensätz(e)</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="tickets"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['tickets']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
        <label>nicht mehr geöffnete Tickets und deren Antworten löschen.</label>
        <small class="helper">ca. <?php echo $_smarty_tpl->tpl_vars['site']->value['closetickets'];?>
 Datensätz(e) für Tickets und ca. <?php echo $_smarty_tpl->tpl_vars['site']->value['closeanswers'];?>
 Datensätz(e) für Ticketantworten</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="banned"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['banned']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
        <label>alle gesperrten Benutzer löschen.</label>
        <small class="helper">ca. <?php echo $_smarty_tpl->tpl_vars['site']->value['bannedusers'];?>
 Datensätz(e)</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="errorlogs"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['errorlogs']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
        <label>alle Fehler Protokolle löschen.</label>
        <small class="helper">ca. <?php echo $_smarty_tpl->tpl_vars['site']->value['errorlogs'];?>
 Datei(en)</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="templatescompiled"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['templatescompiled']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
        <label>alle kompilierten Templates löschen.</label>
        <small class="helper">ca. <?php echo $_smarty_tpl->tpl_vars['site']->value['templatescompiled'];?>
 Datei(en)</small>
    </div>
</div>



<br>
<div class="field<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['sure'] !== false) {?> error<?php }?>">
    <div class="ui checkbox">
        <input type="checkbox" name="sure">
        <label>Willst du wirklich die oben ausgewählten Einträge löschen? Dies kann <b>NICHT</b> rückgängig gemacht werden.</label>
    </div>
</div>
<button type="submit" name="submit" class="ui blue submit button">Absenden</button>
<input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['sure'] !== false) {?>
<div class="ui error message">
    <ul class="list">
        <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
            <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['token'];?>
</li>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['sure'] !== false) {?>
            <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['sure'];?>
</li>
        <?php }?>
    </ul>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?>
<div class="ui success message">
    <ul class="list">
        <?php if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?>
            <li><?php echo $_smarty_tpl->tpl_vars['site']->value['success'];?>
</li>
        <?php }?>
    </ul>
</div>
<?php }?>
</form><?php }
}
