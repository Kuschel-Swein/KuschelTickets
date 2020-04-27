{include file="header.tpl" title="Meine Tickets"}
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
        <div class="item" data-value="1">Titel</div>
        <div class="item" data-value="2">Kategorie</div>
        <div class="item" data-value="3">Datum</div>
        <div class="item" data-value="4">Status</div>
      </div>
    </div>
  </div>
</div>
<div class="five wide column right floated">
  <br>
  {if $__KT['user']->hasPermission("general.tickets.add")}
    <a class="ui blue button right floated" href="{link url="addticket"}">Ticket erstellen</a>
  {/if}
</div>
</div>
<br>
<br>
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
  <tbody id="search_list">
    {foreach from=$tickets item="ticket"}
    <tr>
      <td data-label="ID">{$ticket->ticketID}</td>
      <td data-label="Titel"><a href="{link url="ticket-{$ticket->ticketID}"}">{$ticket->getTitle()}</a></td>
      <td data-label="Kategorie"><a data-tooltip="alle Tickets der Kategorie {$ticket->getCategory()} anzeigen" onclick="utils.setSearch('search_type', 'search_text', 2, this.innerText, 6);" class="ui label {$ticket->getColor()}">{$ticket->getCategory()}</a></td>
      <td data-label="Datum">{$ticket->getTime()|date_format:"%d.%m.%Y"}, {$ticket->getTime()|date_format:"%H:%M"} Uhr</td>
      <td data-label="Status"><a data-tooltip="alle Tickets mit dem Status {$ticket->getFormattedState("name")} anzeigen" onclick="utils.setSearch('search_type', 'search_text', 4, this.innerText, 6);" class="ui {$ticket->getFormattedState("color")} label">{$ticket->getFormattedState("name")}</a></td>
      {if $__KT['ticketRating']}
        {if $ticket->isRated()}
          <td data-label="Bewertung"><div class="ui huge {$__KT['ticketRatingIcon']} rating" data-max-rating="5" data-rating="{$ticket->getRating()}"></div></td>
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
<script>
$('.ui.selection.dropdown').dropdown();
$(".ui.huge.{$__KT['ticketRatingIcon']}.rating").rating("disable");
</script>
{include file="footer.tpl"}