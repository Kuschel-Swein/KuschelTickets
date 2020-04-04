{include file="header.tpl" title="Startseite"}
<h1>Willkommen, {$__KT['user']->getUserName()}</h1>
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
        {foreach from=$tickets item="ticket"}
        <tr>
            <td data-label="ID">{$ticket->ticketID}</td>
            <td data-label="Titel"><a href="index.php?ticket-{$ticket->ticketID}">{$ticket->getTitle()}</a></td>
            <td data-label="Kategorie">{$ticket->getCategory()}</a></td>
            <td data-label="Datum">{$ticket->getTime()|date_format:"%d.%m.%Y"}, {$ticket->getTime()|date_format:"%H:%M"} Uhr</td>
            <td data-label="Status"><div class="ui {$ticket->getFormattedState("color")} label">{$ticket->getFormattedState("name")}</div></td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="5">
                <div class="ui info message">
                    <ul class="list">
                        <li>Du hast noch keine Tickets erstellt.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include file="footer.tpl"}