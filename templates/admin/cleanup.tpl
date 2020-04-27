<div class="ui info message">
    <ul class="list">
        <li>Die Aufräumarbeiten können verwendet werden, wenn die Datenbank zu groß wird oder zu viele Fehler Protokolle existieren, sie löschen vorgegebene Inhalte permanent.</li>
        <li>Deine Datenbank ist aktuell {$site['dbsize']} MB groß.</li>
        <li>Achte darauf, dass du deine Fehler Protokolle regelmäßig löschst, da von diesem um Speicherplatz zu sparen maximal <b>400</b> auf einmal existieren können.</li>
    </ul>
</div>
<form action="{link url="admin/cleanup"}" method="post" class="ui form{if $site['errors']['token'] !== false || $site['errors']['sure'] !== false} error{/if}{if $site['success'] !== false} success{/if}">
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="notifications"{if isset($tpl['post']['notifications']) && !$site['success']} checked{/if}>
        <label>alle als gelesen markierte Benachrichtigungen löschen.</label>
        <small class="helper">ca. {$site['readnotifications']} Datensätz(e)</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="tickets"{if isset($tpl['post']['tickets']) && !$site['success']} checked{/if}>
        <label>nicht mehr geöffnete Tickets und deren Antworten löschen.</label>
        <small class="helper">ca. {$site['closetickets']} Datensätz(e) für Tickets und ca. {$site['closeanswers']} Datensätz(e) für Ticketantworten</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="banned"{if isset($tpl['post']['banned']) && !$site['success']} checked{/if}>
        <label>alle gesperrten Benutzer löschen.</label>
        <small class="helper">ca. {$site['bannedusers']} Datensätz(e)</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="supportchat"{if isset($tpl['post']['supportchat']) && !$site['success']} checked{/if}>
        <label>alle geschlossenen SupportChats und deren Nachrichten löschen.</label>
        <small class="helper">ca. {$site['supportchats']} Datensätz(e)</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="errorlogs"{if isset($tpl['post']['errorlogs']) && !$site['success']} checked{/if}>
        <label>alle Fehler Protokolle löschen.</label>
        <small class="helper">ca. {$site['errorlogs']} Datei(en)</small>
    </div>
</div>
<div class="field">
    <div class="ui checkbox">
        <input type="checkbox" name="templatescompiled"{if isset($tpl['post']['templatescompiled']) && !$site['success']} checked{/if}>
        <label>alle kompilierten Templates löschen.</label>
        <small class="helper">ca. {$site['templatescompiled']} Datei(en)</small>
    </div>
</div>



<br>
<div class="field{if $site['errors']['sure'] !== false} error{/if}">
    <div class="ui checkbox">
        <input type="checkbox" name="sure">
        <label>Willst du wirklich die oben ausgewählten Einträge löschen? Dies kann <b>NICHT</b> rückgängig gemacht werden.</label>
    </div>
</div>
<button type="submit" name="submit" class="ui blue submit button">Absenden</button>
<input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
{if $site['errors']['token'] !== false || $site['errors']['sure'] !== false}
<div class="ui error message">
    <ul class="list">
        {if $site['errors']['token'] !== false}
            <li>{$site['errors']['token']}</li>
        {/if}
        {if $site['errors']['sure'] !== false}
            <li>{$site['errors']['sure']}</li>
        {/if}
    </ul>
</div>
{/if}
{if $site['success'] !== false}
<div class="ui success message">
    <ul class="list">
        {if $site['success']!== false}
            <li>{$site['success']}</li>
        {/if}
    </ul>
</div>
{/if}
</form>