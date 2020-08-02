{assign var="errors" value="false"}
{foreach from=$site['errors'] item="error"}
    {if $error !== false}
        {assign var="errors" value="true"}
    {/if}
{/foreach}
<form class="ui form{if $errors == "true"} error{/if} {if $site['success'] !== false} success{/if}" action="{link url="admin/settings"}" method="post" enctype="multipart/form-data">
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
  <a class="item" data-tab="fourth">
    Benutzer
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
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['seourls']} checked{/if} name="seourls">
            <label>SEO freundliche URLs verwenden</label>
        </div>
        <br>
        <small class="helper">dein <b>APACHE</b> Webserver muss das <i>rewrite</i> Modul unterstützen</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['emailnotifications']} checked{/if} name="emailnotifications">
            <label>E-Mail Benachrichtigungen erlauben</label>
        </div>
        <br>
        <small class="helper">Wenn du dies deaktivierst, können Benutzer nurnoch Benachrichtigungen über das eingebaute Benachrichtigungsystem erhalten.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['externalURLFavicons']} checked{/if} name="externalURLFavicons">
            <label>Favicon externer Links anzeigen</label>
        </div>
        <br>
        <small class="helper">Hier wird der Favicon Dienst von <a href="https://www.google.com/s2/favicons?domain=google.com" data-no-favicon="true" target="_blank">Google</a> verwendet, und das Bild über den internen Bilder Proxy eingebungen.<br>
        Bei vielen externen Links kann dieses Feature zu längeren Ladezeiten führen.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['useDesktopNotification']} checked{/if} name="useDesktopNotification">
            <label>Desktop Benachrichtigungen verwenden</label>
        </div>
        <br>
        <small class="helper">Dieses Feature wird nicht auf allen Browsern unterstützt, speziell bei Mobilen Browsern ist dieses Feature weniger verfügbar.</small>
    </div>
    <div class="field{if $site['errors']['favicon'] !== false} error{/if}">
        <label>Favicon der Website</label>
        <div class="ui labeled input">
            <div class="ui label" data-tooltip="aktuelles Favicon">
                <img src="{$__KT['mainurl']}data/favicon.{$site['config']['faviconextension']}" style="width: 15px!important; height:16px!important;">
            </div>
            <input type="text" placeholder="Klicke um eine Datei zu wählen..." readonly>
            <input type="file" accept="image/*" name="favicon">
        </div>
        <small>Lädst du hier eine Datei hoch, wird das aktuelle Favicon permanent überschrieben.<br>
        Dieses Bild wird auch, wenn aktiviert als Icon für Desktiop Benachrichtigungen verwendet.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['externalURLTitle']} checked{/if} name="externalURLTitle">
            <label>Titel externer Links statt URL anzeigen</label>
        </div>
        <br>
        <small class="helper">Dieses Feature kann bei vielen Links zu einem erhöhten Traffic und zu längeren Seiteladezeiten führen.<br>
        Dieses Feature hat leider keine 100%-tige Erfolgswahrscheinlichkeit.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['proxyAllImages']} checked{/if} name="proxyAllImages">
            <label>externe Bilder über den internen Bilder Proxy anzeigen</label>
        </div>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['externalURLWarning']} checked{/if} name="externalURLWarning">
            <label>auf externe Links hinweisen</label>
        </div>
        <br>
        <small>Bevor der Benutzer deine Website verlässt, wird ihm ein Fenster angezeigt, in welchem er bestätigen muss, dass er deine Website verlassen möchte.</small>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['cookienotice']} checked{/if} name="cookienotice">
            <label>Cookie Hinweis anzeigen</label>
        </div>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['ticketRating']} checked{/if} name="ticketRating" onchange="toggleElement('#ticketsRating_section', this)">
            <label>Tickets können bewertet werden</label>
        </div>
    </div>
    <div id="ticketsRating_section"{if !$site['config']['ticketRating']} style="display: none"{/if}>
        <div class="field required{if $site['errors']['ticketRatingIcon'] !== false} error{/if}">
            <label>Ticketbewertungsicon</label>
            <div class="ui selection dropdown ticketRatingIcon">
            <input type="hidden" name="ticketRatingIcon">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
            </div>
        </div>
    </div>
    <br>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['equalfaq']} checked{/if} name="equalfaq">
            <label>ähnliche FAQ Einträge bei der Ticketerstellung anzeigen</label>
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
            <input type="checkbox"{if $site['config']['mail']['auth']} checked{/if} name="smtpauth" onchange="toggleElement('#smtpauthdata', this)">
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
            <input type="checkbox"{if $site['config']['recaptcha']['use']} checked{/if} name="recaptchause" onchange="toggleElement('#recaptchadata', this)">
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
<div class="ui tab" data-tab="fourth">
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['oauth']['google']['use']} checked{/if} name="oauth_google" onchange="toggleElement('#oauth_google', this)">
            <label>Google Drittanbieter Login</label>
        </div>
        <small><br>Du benötigst hierfür einen Google API Schlüssel, weitere Informationen findest du <a href="https://code.google.com/apis/console/" data-no-favicon="true" target="_blank" rel="noreferer">hier</a>.<br>Die Redirect-URL lautet <span data-tooltip="Klicke zum kopieren"><code onclick="utils.copy(this.innerText)" class="pointer">{link url="oauth-1"}</code></span></small>
    </div>
    <div id="oauth_google"{if !$site['config']['oauth']['google']['use']} style="display: none;"{/if}>
        <div class="field required{if $site['errors']['oauth_google_clientid'] !== false} error{/if}">
        <label>Google Client ID</label>
            <div class="ui input">
                <input type="text" name="oauth_google_clientid" value="{$site['config']['oauth']['google']['clientid']}">
            </div>
        </div>
        <div class="field required{if $site['errors']['oauth_google_clientsecret'] !== false} error{/if}">
        <label>Google Client Secret</label>
            <div class="ui input">
                <input type="text" name="oauth_google_clientsecret" value="{$site['config']['oauth']['google']['clientsecret']}">
            </div>
        </div>
    </div>
    <br>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['oauth']['github']['use']} checked{/if} name="oauth_github" onchange="toggleElement('#oauth_github', this)">
            <label>GitHub Drittanbieter Login</label>
        </div>
        <small><br>Du benötigst hierfür einen GitHub API Schlüssel, weitere Informationen findest du <a href="https://github.com/settings/developers" data-no-favicon="true" target="_blank" rel="noreferer">hier</a>.<br>Die Redirect-URL lautet <span data-tooltip="Klicke zum kopieren"><code onclick="utils.copy(this.innerText)" class="pointer">{link url="oauth-2"}</code></span></small>
    </div>
    <div id="oauth_github"{if !$site['config']['oauth']['github']['use']} style="display: none;"{/if}>
        <div class="field required{if $site['errors']['oauth_github_clientid'] !== false} error{/if}">
        <label>GitHub Client ID</label>
            <div class="ui input">
                <input type="text" name="oauth_github_clientid" value="{$site['config']['oauth']['github']['clientid']}">
            </div>
        </div>
        <div class="field required{if $site['errors']['oauth_github_clientsecret'] !== false} error{/if}">
        <label>GitHub Client Secret</label>
            <div class="ui input">
                <input type="text" name="oauth_github_clientsecret" value="{$site['config']['oauth']['github']['clientsecret']}">
            </div>
        </div>
    </div>
    <br>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['pdfexport']} checked{/if} name="pdfexport">
            <label>Ticket PDF Export</label>
        </div>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['registrationEnabled']} checked{/if} name="registrationEnabled">
            <label>Registrierung aktivieren</label>
        </div>
    </div>
    <div class="field">
        <div class="ui checkbox">
            <input type="checkbox"{if $site['config']['gravatar']} checked{/if} name="gravatar">
            <label>Verwendung von Gravataren erlauben</label>
        </div>
    </div>
    <div class="field required{if $site['errors']['avatarextensions'] !== false} error{/if}">
        <label>Avatar Dateiendungen</label>
        <div class="ui search multiple selection dropdown avatarextensions">
            <input type="hidden" name="avatarextensions" id="avatarextensions">
            <div class="default text"></div>
            <div class="menu"></div>
        </div>
        <small class="helper">Gib hier alle Dateiendungen an welche für Avatare verwendet werden dürfen.</small>
    </div>
    <div class="field required{if $site['errors']['avatarsize'] !== false} error{/if}">
        {assign var="avatarsize" value=$site['config']['avatarsize']}
        <label>maximale Avatar Dateigröße</label>
        <div class="ui right labeled input">
            <input type="number" name="avatarsize" value="{$avatarsize/1000000}">
            <div class="ui label">
                MB
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
$("input:text").click(function() {
    $(this).parent().find("input:file").click();
});
    
$('input:file', '.ui.labeled.input').on('change', function(e) {
    if(e.target.files[0] !== undefined) {
        var name = e.target.files[0].name;
        $('input:text', $(e.target).parent()).val(name);
    } else {
        $('input:text', $(e.target).parent()).val("");
    }
});
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
$('.ui.selection.dropdown.ticketRatingIcon').dropdown({
    values: [
        {
            {if $site['config']['ticketRatingIcon'] == "star"}
            selected: true,
            {/if}
            name: "<span class='ui star rating'><i class='icon active'></i></span> Stern",
            value: "star"
        },
        {
            {if $site['config']['ticketRatingIcon'] == "heart"}
            selected: true,
            {/if}
            name: "<span class='ui heart rating'><i class='icon active'></i></span> Herz",
            value: "heart"
        }
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
            name: "Account-Verwaltung",
            value: "accountmanagement"
        },
        {
            {if "notificationsettings"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Benachrichtigunseinstellungen",
            value: "notificationsettings"
        },
        {
            {if "editortemplates"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Editorvorlagen",
            value: "editortemplates"
        },
        {
            {if "twofactor"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "2-Faktor Authentisierung",
            value: "twofactor"
        },
        {
            {if "ticketedit"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Ticket Eintrag Bearbeitung",
            value: "ticketedit"
        },
        {
            {if "avataredit"|in_array:$site['config']['recaptcha']['cases']}
            selected: true,
            {/if}
            name: "Avatar-Verwaltung",
            value: "avataredit"
        }
    ],
});
$('.ui.selection.dropdown.avatarextensions').dropdown({
    allowAdditions: true,
    forceSelection: false,
    hideAdditions: false,
    useLabels: true,
    {literal}
    message: {
        addResult : '<b>{term}</b> wählen',
        count : '{count} ausgewählt',
        maxSelections : 'maximal {maxCount} Auswahlen',
        noResults : 'Keine Ergebnisse gefunden.'
    },
    {/literal}
    values: [
        {foreach from=$site['config']['avatarextensions'] item="extension"}
            {
                selected: true,
                name: "{$extension}",
                value: "{$extension}"
            },
        {/foreach}
    ]
});
</script>