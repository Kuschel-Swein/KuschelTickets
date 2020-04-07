{include file="header.tpl" title="Tickets"}
<div class="ui container grid stackable form">
  <div class="four wide column">
    <div class="field">
      <label>Suche</label>
      <input type="text" id="search_text" onchange="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 6);" onkeyup="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 6);">
    </div>
  </div>
  <div class="column">
    <div class="field">
      <label>Typ</label>
      <div class="ui selection dropdown">
        <input type="hidden" id="search_type" onchange="utils.search(document.querySelector('#search_list'),  document.querySelector('#search_text'), this, 6);">
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
    </tr>
  </thead>
  <tbody id="search_list">
    {foreach from=$tickets item="ticket"}
    <tr>
      <td data-label="ID">{$ticket->ticketID}</td>
      <td data-label="Titel"><a href="{link url="ticket-{$ticket->ticketID}"}">{$ticket->getTitle()}</a></td>
      <td data-label="Ersteller">{$ticket->getCreator()->getUserName()}</td>
      <td data-label="Kategorie"><a data-tooltip="alle Tickets der Kategorie {$ticket->getCategory()} anzeigen" onclick="utils.setSearch('search_type', 'search_text', 3, this.innerText, 6);" class="ui label {$ticket->getColor()}">{$ticket->getCategory()}</a></td>
      <td data-label="Datum">{$ticket->getTime()|date_format:"%d.%m.%Y"}, {$ticket->getTime()|date_format:"%H:%M"} Uhr</td>
      <td data-label="Status"><div class="ui {$ticket->getFormattedState("color")} label">{$ticket->getFormattedState("name")}</div></td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="6">
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

</script>
{include file="footer.tpl"}