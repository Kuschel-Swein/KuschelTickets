{if $site['site'] == "index"}
    <div class="ui container grid stackable form">
      <div class="four wide column">
        <div class="field">
          <label>Suche</label>
          <input type="text" id="search_text" onchange="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 4);" onkeyup="utils.search(document.querySelector('#search_list'), this, document.querySelector('#search_type'), 4);">
        </div>
      </div>
      <div class="five wide column">
        <div class="field">
          <label>Typ</label>
          <div class="ui selection dropdown">
            <input type="hidden" id="search_type" onchange="utils.search(document.querySelector('#search_list'),  document.querySelector('#search_text'), this, 4;">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
              <div class="item" data-value="0">ID</div>
              <div class="item" data-value="1">Titel</div>
              <div class="item" data-value="2">Elterneintrag</div>
            </div>
          </div>
        </div>
      </div>
      <div class="five wide column right floated">
        <br>
        <a class="ui blue button right floated" href="{link url="admin/menuentries/add"}">Menüeintrag erstellen</a>
      </div>
    </div>
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Elterneintrag</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list" class="jsSortable">
        {foreach from=$site['navigation'] item="entry"}
        <tr id="menuentry{$entry->menuID}">
        <td data-label="ID">{$entry->menuID}</td>
        <td data-label="Name"><a href="{link url=$entry->getLink()}" target="_blank">{$entry->getTitle()}</a></td>
        <td data-label="Elterneintrag">{if $entry->getParent() !== null}{$entry->getParent()->getTitle()}{else}-/-{/if}</a></td>
        <td data-label="Aktion">
            {if !$entry->isSystem()}
              <a href="javascript:deleteMenuEntry({$entry->menuID});" data-tooltip="Löschen"><i class="icon times"></i></a>
            {/if}
            <a href="{link url="admin/menuentries/edit-{$entry->menuID}"}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="4">
                <div class="ui error message">
                    <ul class="list">
                        <li>Es wurden noch keine Menüeinträge erstellt.</li>
                        <li>Dieser Fehler kann nicht auftreten.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deleteMenuEntry(id) {
            modal.confirm("Möchtest du diesen Menüeintrag wirklich löschen? Dies kann nicht rückgängig gemacht werden.<br><b>Beachte:</b> alle sich unter diesem Menüeintrag befindlichen Einträge werden in die oberste Kategorie verschoben.", function() {
                var data = ajax.call(30, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#menuentry" + id).fadeOut(function() {
                      var elems = document.getElementById("search_list").getElementsByTagName("tr");
                      var found = 0;
                      for(var i = 0; i < elems.length; i++) {
                        if(elems[i].style.display !== "none") {
                          found++;
                        }
                      }
                    });
                } else {
                    toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
            });
        }
        $('.ui.selection.dropdown').dropdown();
        $("#search_list").sortable({
          cursor: "move"
        });
        $("#search_list").on("sortupdate", function(event, ui) {
          var elems = document.getElementById("search_list").querySelectorAll("tr");
          var json = [];
          for(var i = 0; i < elems.length; i++) {
            var data = elems[i].id.replace("menuentry", "");
            json.push(data);
          }
          json = JSON.stringify(json);
          ajax.post(31, null, "json=" + json);
        });

    </script>
{else if $site['site'] == "add"}
<a class="ui blue button right floated" href="{link url="admin/menuentries"}">Menüeinträge Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['controller'] !== false || $site['errors']['parent'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/menuentries/add"}" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="{if isset($tpl['post']['text']) && !$site['success']}{$tpl['post']['text']}{/if}">
        </div>
    </div>
    <div class="field required{if $site['errors']['controller'] !== false} error{/if}">
        <label>Seite</label>
        <div class="ui search selection dropdown controller">
            <input type="hidden" name="controller" id="controller">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
        <small class="helper">Wähle hier die Seite oder gib eine externe URL an.</small>
    </div>
    <div class="field{if $site['errors']['parent'] !== false} error{/if}">
        <label>Elterneintrag</label>
        <div class="ui selection dropdown parent">
            <input type="hidden" name="parent" id="parent">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['controller'] !== false || $site['errors']['parent'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
            {/if}
            {if $site['errors']['controller'] !== false}
              <li>{$site['errors']['controller']}</li>
            {/if}
            {if $site['errors']['parent'] !== false}
              <li>{$site['errors']['parent']}</li>
            {/if}
            {if $site['errors']['token'] !== false}
              <li>{$site['errors']['token']}</li>
            {/if}
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
$('.ui.selection.dropdown.controller').dropdown({
    allowAdditions: true,
    forceSelection: false,
    hideAdditions: false,
    {literal}
    message: {
        addResult : '<b>{term}</b> wählen',
        count : '{count} ausgewählt',
        maxSelections : 'maximal {maxCount} Auswahlen',
        noResults : 'Keine Ergebnisse gefunden.'
    },
    {/literal}
    values: [
      {foreach from=$site['controllers'] item="controller"}
        {
        {if isset($tpl['post']['controller']) && !$site['success']}
          {if $tpl['post']['controller'] == $controller['identifier']}
            selected: true,
          {/if}
        {/if}
          name: "{$controller['text']}",
          value: "{$controller['identifier']}"
        },
      {/foreach}
    ],
});
$('.ui.selection.dropdown.parent').dropdown({
    values: [
      {foreach from=$site['navigation'] item="entry"}
        {
        {if isset($tpl['post']['parent']) && !$site['success']}
          {if $tpl['post']['parent'] == $entry->menuID}
            selected: true,
          {/if}
        {/if}
          name: "{$entry->getTitle()}",
          value: "{$entry->menuID}"
        },
      {/foreach}
    ],
});
</script>
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="{link url="admin/menuentries"}">Menüeinträge Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['controller'] !== false || $site['errors']['parent'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/menuentries/edit-{$site['entry']->menuID}"}" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="{$site['entry']->getTitle()}">
        </div>
    </div>
    <div class="field required{if $site['errors']['controller'] !== false} error{/if}">
        <label>Seite</label>
        <div class="ui search selection dropdown controller">
            <input type="hidden" name="controller" id="controller">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
        <small class="helper">Wähle hier die Seite oder gib eine externe URL an.</small>
    </div>
    <div class="field{if $site['errors']['parent'] !== false} error{/if}">
        <label>Elterneintrag</label>
        <div class="ui selection dropdown parent">
            <input type="hidden" name="parent" id="parent">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['controller'] !== false || $site['errors']['parent'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
            {/if}
            {if $site['errors']['controller'] !== false}
              <li>{$site['errors']['controller']}</li>
            {/if}
            {if $site['errors']['parent'] !== false}
              <li>{$site['errors']['parent']}</li>
            {/if}
            {if $site['errors']['token'] !== false}
              <li>{$site['errors']['token']}</li>
            {/if}
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
$('.ui.selection.dropdown.controller').dropdown({
    allowAdditions: true,
    forceSelection: false,
    hideAdditions: false,
    {literal}
    message: {
        addResult : '<b>{term}</b> wählen',
        count : '{count} ausgewählt',
        maxSelections : 'maximal {maxCount} Auswahlen',
        noResults : 'Keine Ergebnisse gefunden.'
    },
    {/literal}
    values: [
      {foreach from=$site['controllers'] item="controller"}
        {
          {if $site['entry']->getController() == $controller['identifier']}
            selected: true,
          {/if}
          name: "{$controller['text']}",
          value: "{$controller['identifier']}"
        },
      {/foreach}
    ],
});
$('.ui.selection.dropdown.parent').dropdown({
    values: [
      {foreach from=$site['navigation'] item="entry"}
        {if $entry->menuID !== $site['entry']->menuID}
        {
          {if $site['entry']->getParent() !== null}
            {if $site['entry']->getParent()->menuID == $entry->menuID}
              selected: true,
            {/if}
          {/if}
          name: "{$entry->getTitle()}",
          value: "{$entry->menuID}"
        },
        {/if}
      {/foreach}
    ],
});
</script>
{/if}