<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 13:36:11
  from 'C:\xampp\htdocs\kuscheltickets\templates\notifications.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b142b71f769_22105034',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c42233e8cc72632839689d872c1512a5f5c419d6' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\notifications.tpl',
      1 => 1586094791,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 2,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b142b71f769_22105034 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),1=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
if ($_smarty_tpl->tpl_vars['subpage']->value == "index") {?>
    <?php $_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Benachrichtigungen"), 0, false);
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
            <div class="item" data-value="1">Nachricht</div>
            <div class="item" data-value="2">Datum</div>
        </div>
        </div>
    </div>
    </div>
    <div class="five wide column right floated">
    <br>
    <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.notifications.settings")) {?>
        <a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"notifications/settings"),$_smarty_tpl);?>
">Benachrichtigungseinstellungen</a>
    <?php }?>
    </div>
    </div>
    <br>
    <br>
    <table class="ui celled table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nachricht</th>
                <th>Datum</th>
                <th>gelesen</th>
            </tr>
        </thead>
        <tbody id="search_list">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['__KT']->value['user']->getNotifications(), 'notification');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['notification']->value) {
?>
            <tr>
                <td data-label="ID"><?php if (!$_smarty_tpl->tpl_vars['notification']->value->isDone()) {?><b><?php }
echo $_smarty_tpl->tpl_vars['notification']->value->notificationID;
if (!$_smarty_tpl->tpl_vars['notification']->value->isDone()) {?></b><?php }?></td>
                <td data-label="Nachricht"><?php if (!$_smarty_tpl->tpl_vars['notification']->value->isDone()) {?><b><?php }?><a href="<?php echo $_smarty_tpl->tpl_vars['notification']->value->getLink();?>
"><?php echo $_smarty_tpl->tpl_vars['notification']->value->getMessage();?>
</a><?php if (!$_smarty_tpl->tpl_vars['notification']->value->isDone()) {?></b><?php }?></td>
                <td data-label="Datum"><?php if (!$_smarty_tpl->tpl_vars['notification']->value->isDone()) {?><b><?php }
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['notification']->value->getTime(),"%d.%m.%Y");?>
, <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['notification']->value->getTime(),"%H:%M");?>
 Uhr<?php if (!$_smarty_tpl->tpl_vars['notification']->value->isDone()) {?></b><?php }?></td>
                <td data-label="gelesen"><?php if ($_smarty_tpl->tpl_vars['notification']->value->isDone()) {?><span data-tooltip="Ja"><i class="icon check"></i></span><?php } else { ?><a data-id="<?php echo $_smarty_tpl->tpl_vars['notification']->value->notificationID;?>
" class="pointer" onclick="notifications.doneList(this);" data-tooltip="Nein, jetzt als gelesen markieren"><i class="icon times"></i></a><?php }?></td>
            </tr>
            <?php
}
} else {
?>
            <tr>
                <td colspan="4">
                    <div class="ui info message">
                        <ul class="list">
                            <li>Du hast noch keine Benachrichtigungen erhalten.</li>
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
<?php } elseif ($_smarty_tpl->tpl_vars['subpage']->value == "settings") {?>
    <?php $_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Benachrichtigungseinstellungen"), 0, true);
?>
    <?php $_smarty_tpl->_assignInScope('erroroccured', "false");?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notificationreasons']->value, 'reason');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['reason']->value) {
?>
        <?php if ($_smarty_tpl->tpl_vars['errors']->value[$_smarty_tpl->tpl_vars['reason']->value['identifier']] !== false) {?>
            <?php $_smarty_tpl->_assignInScope('erroroccured', "true");?>
        <?php }?>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <form class="ui form <?php if ($_smarty_tpl->tpl_vars['errors']->value['token'] !== false || $_smarty_tpl->tpl_vars['erroroccured']->value == "true") {?> error<?php }
if ($_smarty_tpl->tpl_vars['success']->value !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"notifications/settings"),$_smarty_tpl);?>
" method="post">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notificationreasons']->value, 'reason');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['reason']->value) {
?>
            <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value[$_smarty_tpl->tpl_vars['reason']->value['identifier']] !== false) {?> error<?php }?>">
                <label><?php echo $_smarty_tpl->tpl_vars['reason']->value['display'];?>
</label>
                <div class="ui selection dropdown notificationoptions" id="optionsdropdown<?php echo $_smarty_tpl->tpl_vars['reason']->value['identifier'];?>
">
                    <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['reason']->value['identifier'];?>
">
                    <i class="dropdown icon"></i>
                    <div class="default text"></div>
                    <div class="menu">
                    </div>
                </div>
            </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php echo $_smarty_tpl->tpl_vars['recaptcha']->value;?>

        <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
        <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
        <?php if ($_smarty_tpl->tpl_vars['errors']->value['token'] !== false || $_smarty_tpl->tpl_vars['erroroccured']->value == "true") {?>
            <div class="ui error message">
                <ul class="list">
                    <?php if ($_smarty_tpl->tpl_vars['errors']->value['token'] !== false) {?>
                        <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['token'];?>
</li>
                    <?php }?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notificationreasons']->value, 'reason');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['reason']->value) {
?>
                        <?php if ($_smarty_tpl->tpl_vars['errors']->value[$_smarty_tpl->tpl_vars['reason']->value['identifier']] !== false) {?>
                            <li><?php echo $_smarty_tpl->tpl_vars['errors']->value[$_smarty_tpl->tpl_vars['reason']->value['identifier']];?>
</li>
                        <?php }?>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </ul>
            </div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['success']->value !== false) {?>
            <div class="ui success message">
                <ul class="list">
                    <li><?php echo $_smarty_tpl->tpl_vars['success']->value;?>
</li>
                </ul>
            </div>
        <?php }?>
    </form>
    <?php echo '<script'; ?>
>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notificationreasons']->value, 'reason');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['reason']->value) {
?>
        $('.ui.selection.dropdown#optionsdropdown<?php echo $_smarty_tpl->tpl_vars['reason']->value['identifier'];?>
').dropdown({
            values: [
                <?php if ($_smarty_tpl->tpl_vars['__KT']->value['emailnotifications']) {?>
                    {
                    <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->getNotificationType($_smarty_tpl->tpl_vars['reason']->value['identifier']) == "email") {?>
                        selected: true,
                    <?php }?>
                    name: "E-Mail Benachrichtigung",
                    value: "email"
                    },
                <?php }?>
                {
                <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->getNotificationType($_smarty_tpl->tpl_vars['reason']->value['identifier']) == "normal" || ($_smarty_tpl->tpl_vars['__KT']->value['user']->getNotificationType($_smarty_tpl->tpl_vars['reason']->value['identifier']) == "email" && !$_smarty_tpl->tpl_vars['__KT']->value['emailnotifications'])) {?>
                    selected: true,
                <?php }?>
                name: "normale Benachrichtigung",
                value: "normal"
                },
                {
                <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->getNotificationType($_smarty_tpl->tpl_vars['reason']->value['identifier']) == "none") {?>
                    selected: true,
                <?php }?>
                name: "keine Benachrichtigung",
                value: "none"
                },
            ],
        });
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php echo '</script'; ?>
>
<?php }
$_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
