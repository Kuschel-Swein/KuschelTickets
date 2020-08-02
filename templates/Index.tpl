{include file="header.tpl" title="Startseite"}
<h1>Willkommen, {$__KT['user']->username}</h1>
<div class="ui divider"></div>
<h3>deine letzten {$tickets|count} Tickets</h3>
<table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Kategorie</th>
            <th>Datum</th>
            <th>Status</th>
            {if $__KT['ticketRating']}
                <th class="no-sort">Bewertung</th>
            {/if}
        </tr>
    </thead>
    <tbody>
        {foreach from=$tickets item="ticket"}
        <tr>
            <td data-label="ID">{$ticket->ticketID}</td>
            <td data-label="Titel"><a href="{link url="ticket-{$ticket->ticketID}"}">{$ticket->title}</a></td>
            <td data-label="Kategorie"><span class="ui label {$ticket->color}">{$ticket->category}</span></td>
            <td data-label="Datum">{$ticket->time|datetime}</td>
            <td data-label="Status"><div class="ui {$ticket->getFormattedState("color")} label">{$ticket->getFormattedState("name")}</div></td>
            {if $__KT['ticketRating']}
                {if $ticket->hasRating()}
                <td data-label="Bewertung"><div class="ui huge {$__KT['ticketRatingIcon']} rating" data-max-rating="5" data-rating="{$ticket->rating}"></div></td>
                {else}
                <td data-label="Bewertung"><i>noch nicht bewertet</i></td>
                {/if}
            {/if}
        </tr>
        {foreachelse}
        <tr>
            <td colspan="6">
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
{if $__KT['ticketRating']}
<script>
$(".ui.huge.{$__KT['ticketRatingIcon']}.rating").rating("disable");
</script>
{/if}
{include file="footer.tpl"}