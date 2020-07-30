{include file="header.tpl" title="Tickets"}
<div class="ui container grid stackable form">
  <div class="four wide column">
    <div class="field">
      <label>Suche</label>
      <input type="text" id="search_text" onchange="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 7);" onkeyup="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 7);">
    </div>
  </div>
  <div class="column">
    <div class="field">
      <label>Typ</label>
      <div class="ui selection dropdown">
        <input type="hidden" id="search_type" onchange="utils.search(document.querySelector('#search_list'),  document.querySelector('#search_text'), this, 7);">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
          <div class="item" data-value="0">ID</div>
          <div class="item" data-value="1">Titel</div>
          <div class="item" data-value="2">Ersteller</div>
          <div class="item" data-value="3">Kategorie</div>
          <div class="item" data-value="4">Datum</div>
          <div class="item" data-value="5">Status</div>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<br>
<table class="ui celled table">
  <thead>
    <tr>
        <th>ID</th>
        <th>Titel</th>
        <th>Ersteller</th>
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
      <td data-label="Titel"><a href="{link url="ticket-{$ticket->ticketID}"}">{$ticket->title}</a></td>
      <td data-label="Ersteller">{$ticket->getCreator()->username}</td>
      <td data-label="Kategorie"><a data-tooltip="alle Tickets der Kategorie „{$ticket->category}“ anzeigen" onclick="utils.setSearch('search_type', 'search_text', 3, this.innerText, 7);" class="ui label {$ticket->color}">{$ticket->category}</a></td>
      <td data-label="Datum">{$ticket->time|date_format:"%d.%m.%Y"}, {$ticket->time|date_format:"%H:%M"} Uhr</td>
      <td data-label="Status"><a data-tooltip="alle Tickets mit dem Status „{$ticket->getFormattedState("name")}“ anzeigen" onclick="utils.setSearch('search_type', 'search_text', 5, this.innerText, 7);" class="ui {$ticket->getFormattedState("color")} label">{$ticket->getFormattedState("name")}</a></td>
      {if $__KT['ticketRating']}
        {if $ticket->rating != null}
          <td data-label="Bewertung"><div class="ui huge {$__KT['ticketRatingIcon']} rating" data-max-rating="5" data-rating="{$ticket->rating}"></div></td>
        {else}
          <td data-label="Bewertung"><i>noch nicht bewertet</i></td>
        {/if}
      {/if}
    </tr>
    {foreachelse}
    <tr>
        <td colspan="7">
            <div class="ui info message">
                <ul class="list">
                    <li>Es wurden noch keine Tickets erstellt.</li>
                </ul>
            </div>
        </td>
    </tr>
    {/foreach}
  </tbody>
</table>
<script>
$('.ui.selection.dropdown').dropdown();
{if $__KT['ticketRating']}
$(".ui.huge.{$__KT['ticketRatingIcon']}.rating").rating("disable");
{/if}
</script>
{include file="footer.tpl"}