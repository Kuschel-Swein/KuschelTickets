{assign var="errors" value="false"}
{foreach from=$site['errors'] item="error"}
    {if $error !== false}
        {assign var="errors" value="true"}
    {/if}
{/foreach}
<form class="ui form{if $errors == "true"} error{/if} {if $site['success'] !== false} success{/if}" action="index.php?admin/settings" method="post">
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
    <div class="field required{if $site['errors']['pagetitle'] !== false} error{/if}">
    <label>Seitentitel</label>
        <div class="ui input">
            <input type="text" name="pagetitle" value="{$site['config']['pagetitle']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['cookie'] !== false} error{/if}">
    <label>Cookie</label>
        <div class="ui input">
            <input type="text" name="cookie" value="{$site['config']['cookie']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['adminmail'] !== false} error{/if}">
    <label>Admin E-Mail</label>
        <div class="ui input">
            <input type="text" name="adminmail" value="{$site['config']['adminmail']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['statecolorclosed'] !== false} error{/if}">
        <label>Ticketstatus Farbe <i>geschlossen</i></label>
        <div class="ui selection dropdown states closecolor">
        <input type="hidden" name="statecolorclosed">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
        </div>
    </div>
    <div class="field required{if $site['errors']['stateopencolor'] !== false} error{/if}">
        <label>Ticketstatus Farbe <i>geöffnet</i></label>
        <div class="ui selection dropdown states opencolor">
        <input type="hidden" name="stateopencolor">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
        </div>
    </div>
    <div class="field required{if $site['errors']['statedonecolor'] !== false} error{/if}">
        <label>Ticketstatus Farbe <i>erledigt</i></label>
        <div class="ui selection dropdown states donecolor">
        <input type="hidden" name="statedonecolor">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
        </div>
    </div>

</div>
<div class="ui tab" data-tab="second">
    <div class="field required{if $site['errors']['databasedatabase'] !== false} error{/if}">
    <label>Datenbank</label>
        <div class="ui input">
            <input type="text" name="databasedatabase" value="{$site['config']['databaseaccess']['database']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['databasehost'] !== false} error{/if}">
    <label>Datenbank Host</label>
        <div class="ui input">
            <input type="text" name="databasehost" value="{$site['config']['databaseaccess']['host']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['databaseport'] !== false} error{/if}">
    <label>Datebank Port</label>
        <div class="ui input">
            <input type="number" name="databaseport" value="{$site['config']['databaseaccess']['port']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['databaseport'] !== false} error{/if}">
    <label>Datenbank Benutzer</label>
        <div class="ui input">
            <input type="text" name="databaseuser" value="{$site['config']['databaseaccess']['user']}">
        </div>
    </div>
    <div class="field{if $site['errors']['databasepassword'] !== false} error{/if}">
    <label>Datenbank Passwort</label>
        <div class="ui input">
            <input type="password" name="databasepassword" value="{$site['config']['databaseaccess']['password']}">
        </div>
    </div>
    <div class="ui divider"></div>
    <div class="field required{if $site['errors']['smtphost'] !== false} error{/if}">
    <label>SMTP Host</label>
        <div class="ui input">
            <input type="text" name="smtphost" value="{$site['config']['mail']['host']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['smtpport'] !== false} error{/if}">
    <label>SMTP Port</label>
        <div class="ui input">
            <input type="number" name="smtpport" value="{$site['config']['mail']['port']}">
        </div>
    </div>
    <div class="field{if $site['errors']['smtpauth'] !== false} error{/if}">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['mail']['auth']} checked{/if} name="smtpauth" onchange="if(this.checked) { document.getElementById('smtpauthdata').style.display = 'block'; } else { document.getElementById('smtpauthdata').style.display = 'none'; }">
            <label>SMTP Login</label>
        </div>
        <br>
        <small class="helper">Hat der SMTP Server einen Login?</small>
    </div>
    <div id="smtpauthdata"{if !$site['config']['mail']['auth']} style="display: none"{/if}>
        <div class="field required{if $site['errors']['smtpusername'] !== false} error{/if}">
        <label>SMTP Benutzername</label>
            <div class="ui input">
                <input type="text" name="smtpusername" value="{$site['config']['mail']['username']}">
            </div>
        </div>
        <div class="field required{if $site['errors']['smtppassword'] !== false} error{/if}">
        <label>SMTP Passwort</label>
            <div class="ui input">
                <input type="password" name="smtppassword" value="{$site['config']['mail']['password']}">
            </div>
        </div>
    </div>
    <br>
    <div class="field required{if $site['errors']['smtpfrom'] !== false} error{/if}">
    <label>SMTP Sender</label>
        <div class="ui input">
            <input type="email" name="smtpfrom" value="{$site['config']['mail']['from']}">
        </div>
        <small class="helper">meistens ist dies gleich zu dem Benutzernamen</small>
    </div>
</div>
<div class="ui tab" data-tab="third">
    <div class="field required{if $site['errors']['recaptchause'] !== false} error{/if}{if $site['success'] !== false} success{/if}">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['recaptcha']['use']} checked{/if} name="recaptchause" onchange="if(this.checked) { document.getElementById('recaptchadata').style.display = 'block'; } else { document.getElementById('recaptchadata').style.display = 'none'; }">
            <label>reCaptcha aktivieren</label>
        </div>
    </div>
    <div id="recaptchadata"{if !$site['config']['recaptcha']['use']} style="display: none"{/if}>
        <div class="field required{if $site['errors']['recaptchaversion'] !== false} error{/if}">
            <label>reCaptcha Typ</label>
            <div class="ui selection dropdown recaptchaversion">
            <input type="hidden" name="recaptchaversion">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
            </div>
        </div>
        <div class="field required{if $site['errors']['recaptchapublic'] !== false} error{/if}">
        <label>reCaptcha öffentlicher Schlüssel</label>
            <div class="ui input">
                <input type="text" name="recaptchapublic" value="{$site['config']['recaptcha']['public']}">
            </div>
        </div>
        <div class="field required{if $site['errors']['recaptchaprivate'] !== false} error{/if}">
        <label>reCaptcha geheimer Schlüssel</label>
            <div class="ui input">
                <input type="text" name="recaptchaprivate" value="{$site['config']['recaptcha']['secret']}">
            </div>
        </div>
        <div class="field required{if $site['errors']['recaptchacases'] !== false} error{/if}">
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
<input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
{if $errors == "true"}
    <div class="ui error message">
        <ul class="list">
            {foreach from=$site['errors'] item="error"}
                {if $error !== false}
                    <li>{$error}</li>
                {/if}
            {/foreach}
        </ul>
    </div>
{/if}
{if $site['success'] !== false}
    <div class="ui success message">
        <ul class="list">
            <li>{$site['success']}</li>
        </ul>
    </div>
{/if}
</form>
<script>
$('.menu .item').tab();
$('.ui.selection.dropdown.states.closecolor').dropdown({
    values: [
      {foreach from=$site['colors'] item="color"}
        {
          {if $site['config']['state_colors']['closed'] == $color['value']}
            selected: true,
          {/if}
          name: "{$color['name']}",
          value: "{$color['value']}"
        },
      {/foreach}
    ],
});
$('.ui.selection.dropdown.states.opencolor').dropdown({
    values: [
        {foreach from=$site['colors'] item="color"}
        {
            {if $site['config']['state_colors']['open'] == $color['value']}
            selected: true,
            {/if}
            name: "{$color['name']}",
            value: "{$color['value']}"
        },
        {/foreach}
    ],
});
$('.ui.selection.dropdown.states.donecolor').dropdown({
    values: [
        {foreach from=$site['colors'] item="color"}
        {
            {if $site['config']['state_colors']['done'] == $color['value']}
            selected: true,
            {/if}
            name: "{$color['name']}",
            value: "{$color['value']}"
        },
        {/foreach}
    ],
});
$('.ui.selection.dropdown.recaptchaversion').dropdown({
    values: [
        {
            {if $site['config']['recaptcha']['version'] == "2"}
            selected: true,
            {/if}
            name: "Version 2 (Kästchen)",
            value: "2"
        },
        {
            {if $site['config']['recaptcha']['version'] == "3"}
            selected: true,
            {/if}
            name: "Version 3 (unsichtbar)",
            value: "3"
        }
    ],
});
$('.ui.selection.dropdown.recaptchacases').dropdown({
    useLabels: true,
    values: [
        {
            {if "login"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Login",
            value: "login"
        },
        {
            {if "registration"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Registrierung",
            value: "registration"
        },
        {
            {if "passwordreset"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Passwortzurücksetzung",
            value: "passwordreset"
        },
        {
            {if "addticket"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Ticket erstellen",
            value: "addticket"
        },
        {
            {if "ticketanswer"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Ticket Antwort",
            value: "ticketanswer"
        },
        {
            {if "accountmanagement"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Accountverwaltung",
            value: "accountmanagement"
        }
    ],
});
</script>