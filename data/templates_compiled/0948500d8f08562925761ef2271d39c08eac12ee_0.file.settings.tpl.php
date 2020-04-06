<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 15:20:12
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin\settings.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b2c8c642b50_77969751',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0948500d8f08562925761ef2271d39c08eac12ee' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin\\settings.tpl',
      1 => 1586179211,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8b2c8c642b50_77969751 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
$_smarty_tpl->_assignInScope('errors', "false");
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['errors'], 'error');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
?>
    <?php if ($_smarty_tpl->tpl_vars['error']->value !== false) {?>
        <?php $_smarty_tpl->_assignInScope('errors', "true");?>
    <?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['errors']->value == "true") {?> error<?php }?> <?php if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"admin/settings"),$_smarty_tpl);?>
" method="post" enctype="multipart/form-data">
    <div class="ui tabmenu secondary pointing menu">
  <a class="active item" data-tab="first">
    Allgemeines
  </a>
  <a class="item" data-tab="second">
    Zugangsdaten
  </a>
  <a class="item" data-tab="third">
    Sicherheit
  </a>
</div>
<div class="ui tab active" data-tab="first">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['pagetitle'] !== false) {?> error<?php }?>">
    <label>Seitentitel</label>
        <div class="ui input">
            <input type="text" name="pagetitle" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['pagetitle'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['cookie'] !== false) {?> error<?php }?>">
    <label>Cookie</label>
        <div class="ui input">
            <input type="text" name="cookie" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['cookie'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['adminmail'] !== false) {?> error<?php }?>">
    <label>Admin E-Mail</label>
        <div class="ui input">
            <input type="text" name="adminmail" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['adminmail'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['statecolorclosed'] !== false) {?> error<?php }?>">
        <label>Ticketstatus Farbe <i>geschlossen</i></label>
        <div class="ui selection dropdown states closecolor">
        <input type="hidden" name="statecolorclosed">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['stateopencolor'] !== false) {?> error<?php }?>">
        <label>Ticketstatus Farbe <i>geöffnet</i></label>
        <div class="ui selection dropdown states opencolor">
        <input type="hidden" name="stateopencolor">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['statedonecolor'] !== false) {?> error<?php }?>">
        <label>Ticketstatus Farbe <i>erledigt</i></label>
        <div class="ui selection dropdown states donecolor">
        <input type="hidden" name="statedonecolor">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
        </div>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['seourls']) {?> checked<?php }?> name="seourls">
            <label>SEO freundliche URLs verwenden</label>
        </div>
        <br>
        <small class="helper">dein <b>APACHE</b> Webserver muss das <i>rewrite</i> Modul unterstützen</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['emailnotifications']) {?> checked<?php }?> name="emailnotifications">
            <label>E-Mail Benachrichtigungen erlauben</label>
        </div>
        <br>
        <small class="helper">Wenn du dies deaktivierst, können Benutzer nurnoch Benachrichtigungen über das eingebaute Benachrichtigungsystem erhalten.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['externalURLFavicons']) {?> checked<?php }?> name="externalURLFavicons">
            <label>Favicon externer Links anzeigen</label>
        </div>
        <br>
        <small class="helper">Hier wird der Favicon Dienst von <a href="https://www.google.com/s2/favicons?domain=google.com" target="_blank">Google</a> verwendet, und das Bild über den internen Bilder Proxy eingebungen.<br>
        Bei vielen externen Links kann dieses Feature zu längeren Ladezeiten führen.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['useDesktopNotification']) {?> checked<?php }?> name="useDesktopNotification">
            <label>Desktop Benachrichtigungen verwenden</label>
        </div>
        <br>
        <small class="helper">Dieses Feature wird nicht auf allen Browsern unterstützt, speziell bei Mobilen Browsern ist dieses Feature weniger verfügbar.</small>
    </div>
    <div class="field<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['favicon'] !== false) {?> error<?php }?>">
        <label>Favicon der Website</label>
        <div class="ui labeled input">
            <div class="ui label" data-tooltip="aktuelles Favicon">
                <img src="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
data/favicon.<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['faviconextension'];?>
" style="width: 15px!important; height:16px!important;">
            </div>
            <input type="text" placeholder="Klicke um eine Datei zu wählen..." readonly>
            <input type="file" accept="image/*" name="favicon">
        </div>
        <small>Lädst du hier eine Datei hoch, wird das aktuelle Favicon permanent überschrieben.<br>
        Dieses Bild wird auch, wenn aktiviert als Icon für Desktiop Benachrichtigungen verwendet.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['externalURLTitle']) {?> checked<?php }?> name="externalURLTitle">
            <label>Titel externer Links statt URL anzeigen</label>
        </div>
        <br>
        <small class="helper">Dieses Feature kann bei vielen Links zu einem erhöhten Traffic und zu längeren Seiteladezeiten führen.<br>
        Dieses Feature hat leider keine 100%-tige Erfolgswahrscheinlichkeit.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['proxyAllImages']) {?> checked<?php }?> name="proxyAllImages">
            <label>externe Bilder über den internen Bilder Proxy anzeigen</label>
        </div>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['externalURLWarning']) {?> checked<?php }?> name="externalURLWarning">
            <label>auf externe Links hinweisen</label>
        </div>
        <br>
        <small>Bevor der Benutzer deine Website verlässt, wird ihm ein Fenster angezeigt, in welchem er bestätigen muss, dass er deine Website verlassen möchte.</small>
    </div>
</div>
<div class="ui tab" data-tab="second">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['databasedatabase'] !== false) {?> error<?php }?>">
    <label>Datenbank</label>
        <div class="ui input">
            <input type="text" name="databasedatabase" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['databaseaccess']['database'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['databasehost'] !== false) {?> error<?php }?>">
    <label>Datenbank Host</label>
        <div class="ui input">
            <input type="text" name="databasehost" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['databaseaccess']['host'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['databaseport'] !== false) {?> error<?php }?>">
    <label>Datebank Port</label>
        <div class="ui input">
            <input type="number" name="databaseport" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['databaseaccess']['port'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['databaseport'] !== false) {?> error<?php }?>">
    <label>Datenbank Benutzer</label>
        <div class="ui input">
            <input type="text" name="databaseuser" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['databaseaccess']['user'];?>
">
        </div>
    </div>
    <div class="field<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['databasepassword'] !== false) {?> error<?php }?>">
    <label>Datenbank Passwort</label>
        <div class="ui input">
            <input type="password" name="databasepassword" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['databaseaccess']['password'];?>
">
        </div>
    </div>
    <div class="ui divider"></div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['smtphost'] !== false) {?> error<?php }?>">
    <label>SMTP Host</label>
        <div class="ui input">
            <input type="text" name="smtphost" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['mail']['host'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['smtpport'] !== false) {?> error<?php }?>">
    <label>SMTP Port</label>
        <div class="ui input">
            <input type="number" name="smtpport" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['mail']['port'];?>
">
        </div>
    </div>
    <div class="field<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['smtpauth'] !== false) {?> error<?php }?>">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['mail']['auth']) {?> checked<?php }?> name="smtpauth" onchange="if(this.checked) { document.getElementById('smtpauthdata').style.display = 'block'; } else { document.getElementById('smtpauthdata').style.display = 'none'; }">
            <label>SMTP Login</label>
        </div>
        <br>
        <small class="helper">Hat der SMTP Server einen Login?</small>
    </div>
    <div id="smtpauthdata"<?php if (!$_smarty_tpl->tpl_vars['site']->value['config']['mail']['auth']) {?> style="display: none"<?php }?>>
        <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['smtpusername'] !== false) {?> error<?php }?>">
        <label>SMTP Benutzername</label>
            <div class="ui input">
                <input type="text" name="smtpusername" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['mail']['username'];?>
">
            </div>
        </div>
        <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['smtppassword'] !== false) {?> error<?php }?>">
        <label>SMTP Passwort</label>
            <div class="ui input">
                <input type="password" name="smtppassword" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['mail']['password'];?>
">
            </div>
        </div>
    </div>
    <br>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['smtpfrom'] !== false) {?> error<?php }?>">
    <label>SMTP Sender</label>
        <div class="ui input">
            <input type="email" name="smtpfrom" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['mail']['from'];?>
">
        </div>
        <small class="helper">meistens ist dies gleich zu dem Benutzernamen</small>
    </div>
</div>
<div class="ui tab" data-tab="third">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['recaptchause'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>">
        <div class="ui checkbox">
            <input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['use']) {?> checked<?php }?> name="recaptchause" onchange="if(this.checked) { document.getElementById('recaptchadata').style.display = 'block'; } else { document.getElementById('recaptchadata').style.display = 'none'; }">
            <label>reCaptcha aktivieren</label>
        </div>
    </div>
    <div id="recaptchadata"<?php if (!$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['use']) {?> style="display: none"<?php }?>>
        <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['recaptchaversion'] !== false) {?> error<?php }?>">
            <label>reCaptcha Typ</label>
            <div class="ui selection dropdown recaptchaversion">
            <input type="hidden" name="recaptchaversion">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
            </div>
        </div>
        <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['recaptchapublic'] !== false) {?> error<?php }?>">
        <label>reCaptcha öffentlicher Schlüssel</label>
            <div class="ui input">
                <input type="text" name="recaptchapublic" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['public'];?>
">
            </div>
        </div>
        <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['recaptchaprivate'] !== false) {?> error<?php }?>">
        <label>reCaptcha geheimer Schlüssel</label>
            <div class="ui input">
                <input type="text" name="recaptchaprivate" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['secret'];?>
">
            </div>
        </div>
        <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['recaptchacases'] !== false) {?> error<?php }?>">
        <label>reCaptcha Verwendungen</label>
            <div class="ui multiple selection dropdown recaptchacases">
                <input type="hidden" name="recaptchacases">
                <i class="dropdown icon"></i>
                <div class="default text"></div>
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<button type="submit" name="submit" class="ui blue submit button">Absenden</button>
<input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
<?php if ($_smarty_tpl->tpl_vars['errors']->value == "true") {?>
    <div class="ui error message">
        <ul class="list">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['errors'], 'error');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
?>
                <?php if ($_smarty_tpl->tpl_vars['error']->value !== false) {?>
                    <li><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</li>
                <?php }?>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </ul>
    </div>
<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?>
    <div class="ui success message">
        <ul class="list">
            <li><?php echo $_smarty_tpl->tpl_vars['site']->value['success'];?>
</li>
        </ul>
    </div>
<?php }?>
</form>
<?php echo '<script'; ?>
>
$("input:text").click(function() {
    $(this).parent().find("input:file").click();
});
    
$('input:file', '.ui.labeled.input').on('change', function(e) {
    var name = e.target.files[0].name;
    $('input:text', $(e.target).parent()).val(name);
});
$('.menu .item').tab();
$('.ui.selection.dropdown.states.closecolor').dropdown({
    values: [
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['colors'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>
        {
          <?php if ($_smarty_tpl->tpl_vars['site']->value['config']['state_colors']['closed'] == $_smarty_tpl->tpl_vars['color']->value['value']) {?>
            selected: true,
          <?php }?>
          name: "<?php echo $_smarty_tpl->tpl_vars['color']->value['name'];?>
",
          value: "<?php echo $_smarty_tpl->tpl_vars['color']->value['value'];?>
"
        },
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    ],
});
$('.ui.selection.dropdown.states.opencolor').dropdown({
    values: [
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['colors'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>
        {
            <?php if ($_smarty_tpl->tpl_vars['site']->value['config']['state_colors']['open'] == $_smarty_tpl->tpl_vars['color']->value['value']) {?>
            selected: true,
            <?php }?>
            name: "<?php echo $_smarty_tpl->tpl_vars['color']->value['name'];?>
",
            value: "<?php echo $_smarty_tpl->tpl_vars['color']->value['value'];?>
"
        },
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    ],
});
$('.ui.selection.dropdown.states.donecolor').dropdown({
    values: [
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['colors'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>
        {
            <?php if ($_smarty_tpl->tpl_vars['site']->value['config']['state_colors']['done'] == $_smarty_tpl->tpl_vars['color']->value['value']) {?>
            selected: true,
            <?php }?>
            name: "<?php echo $_smarty_tpl->tpl_vars['color']->value['name'];?>
",
            value: "<?php echo $_smarty_tpl->tpl_vars['color']->value['value'];?>
"
        },
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    ],
});
$('.ui.selection.dropdown.recaptchaversion').dropdown({
    values: [
        {
            <?php if ($_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['version'] == "2") {?>
            selected: true,
            <?php }?>
            name: "Version 2 (Kästchen)",
            value: "2"
        },
        {
            <?php if ($_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['version'] == "3") {?>
            selected: true,
            <?php }?>
            name: "Version 3 (unsichtbar)",
            value: "3"
        }
    ],
});
$('.ui.selection.dropdown.recaptchacases').dropdown({
    useLabels: true,
    values: [
        {
            <?php if (in_array("login",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Login",
            value: "login"
        },
        {
            <?php if (in_array("registration",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Registrierung",
            value: "registration"
        },
        {
            <?php if (in_array("passwordreset",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Passwortzurücksetzung",
            value: "passwordreset"
        },
        {
            <?php if (in_array("addticket",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Ticket erstellen",
            value: "addticket"
        },
        {
            <?php if (in_array("ticketanswer",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Ticket Antwort",
            value: "ticketanswer"
        },
        {
            <?php if (in_array("accountmanagement",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Accountverwaltung",
            value: "accountmanagement"
        },
        {
            <?php if (in_array("notificationsettings",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Benachrichtigunseinstellungen",
            value: "notificationsettings"
        },
        {
            <?php if (in_array("editortemplates",$_smarty_tpl->tpl_vars['site']->value['config']['recaptcha']['cases'])) {?>
            selected: true,
            <?php }?>
            name: "Editorvorlagen",
            value: "editortemplates"
        }
    ],
});
<?php echo '</script'; ?>
><?php }
}
