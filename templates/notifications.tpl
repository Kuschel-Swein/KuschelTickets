{if $subpage == "index"}
    {include file="header.tpl" title="Benachrichtigungen"}
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
    {if $__KT['user']->hasPermission("general.notifications.settings")}
        <a class="ui blue button right floated" href="{link url="notifications/settings"}">Benachrichtigungseinstellungen</a>
    {/if}
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
            {foreach from=$__KT['user']->getNotifications() item="notification"}
            <tr>
                <td data-label="ID">{if !$notification->isDone()}<b>{/if}{$notification->notificationID}{if !$notification->isDone()}</b>{/if}</td>
                <td data-label="Nachricht">{if !$notification->isDone()}<b>{/if}<a href="{$notification->getLink()}">{$notification->getMessage()}</a>{if !$notification->isDone()}</b>{/if}</td>
                <td data-label="Datum">{if !$notification->isDone()}<b>{/if}{$notification->getTime()|date_format:"%d.%m.%Y"}, {$notification->getTime()|date_format:"%H:%M"} Uhr{if !$notification->isDone()}</b>{/if}</td>
                <td data-label="gelesen">{if $notification->isDone()}<span data-tooltip="Ja"><i class="icon check"></i></span>{else}<a data-id="{$notification->notificationID}" class="pointer" onclick="notifications.doneList(this);" data-tooltip="Nein, jetzt als gelesen markieren"><i class="icon times"></i></a>{/if}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="4">
                    <div class="ui info message">
                        <ul class="list">
                            <li>Du hast noch keine Benachrichtigungen erhalten.</li>
                        </ul>
                    </div>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    <script>
    $('.ui.selection.dropdown').dropdown();
    </script>
{else if $subpage == "settings"}
    {include file="header.tpl" title="Benachrichtigungseinstellungen"}
    {assign var="erroroccured" value="false"}
    {foreach from=$notificationreasons item="reason"}
        {if $errors[$reason['identifier']] !== false}
            {assign var="erroroccured" value="true"}
        {/if}
    {/foreach}
    <form class="ui form {if $errors['token'] !== false || $erroroccured == "true"} error{/if}{if $success !== false} success{/if}" action="{link url="notifications/settings"}" method="post">
        {foreach from=$notificationreasons item="reason"}
            <div class="field required{if $errors[$reason['identifier']] !== false} error{/if}">
                <label>{$reason['display']}</label>
                <div class="ui selection dropdown notificationoptions" id="optionsdropdown{$reason['identifier']}">
                    <input type="hidden" name="{$reason['identifier']}">
                    <i class="dropdown icon"></i>
                    <div class="default text"></div>
                    <div class="menu">
                    </div>
                </div>
            </div>
        {/foreach}
        {$recaptcha}
        <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
        <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
        {if $errors['token'] !== false || $erroroccured == "true"}
            <div class="ui error message">
                <ul class="list">
                    {if $errors['token'] !== false}
                        <li>{$errors['token']}</li>
                    {/if}
                    {foreach from=$notificationreasons item="reason"}
                        {if $errors[$reason['identifier']] !== false}
                            <li>{$errors[$reason['identifier']]}</li>
                        {/if}
                    {/foreach}
                </ul>
            </div>
        {/if}
        {if $success !== false}
            <div class="ui success message">
                <ul class="list">
                    <li>{$success}</li>
                </ul>
            </div>
        {/if}
    </form>
    <script>
    {foreach from=$notificationreasons item="reason"}
        $('.ui.selection.dropdown#optionsdropdown{$reason['identifier']}').dropdown({
            values: [
                {if $__KT['emailnotifications']}
                    {
                    {if $__KT['user']->getNotificationType($reason['identifier']) == "email"}
                        selected: true,
                    {/if}
                    name: "E-Mail Benachrichtigung",
                    value: "email"
                    },
                {/if}
                {
                {if $__KT['user']->getNotificationType($reason['identifier']) == "normal" || ($__KT['user']->getNotificationType($reason['identifier']) == "email" && !$__KT['emailnotifications'])}
                    selected: true,
                {/if}
                name: "normale Benachrichtigung",
                value: "normal"
                },
                {
                {if $__KT['user']->getNotificationType($reason['identifier']) == "none"}
                    selected: true,
                {/if}
                name: "keine Benachrichtigung",
                value: "none"
                },
            ],
        });
    {/foreach}
    </script>
{/if}
{include file="footer.tpl"}