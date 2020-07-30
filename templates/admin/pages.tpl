{if $site['site'] == "index"}
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
              <div class="item" data-value="2">Typ</div>
              <div class="item" data-value="3">URL</div>
            </div>
          </div>
        </div>
      </div>
      <div class="four wide column right floated">
        <br>
        <a class="ui blue button right floated" href="javascript:createPage();">Seite erstellen</a>
      </div>
    </div>
    
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Typ</th>
            <th>URL</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$site['pages'] item="page"}
        <tr id="pageentry{$page->pageID}">
        <td data-label="ID">{$page->pageID}</td>
        <td data-label="Titel">{$page->title}</td>
        <td data-label="Typ">{if $page->type == "1"}HTML{else if $page->type == "2"}Template{else}WYSIWYG{/if}</td>
        <td data-label="URL"><a href="{link url="page/{$page->url}"}" target="_blank">{link url="page/{$page->url}"}</a></td>
        <td data-label="Aktion">
          {if $page->system !== 1}
            <a href="javascript:deletePage({$page->pageID});" data-tooltip="Löschen"><i class="icon times"></i></a>
          {/if}
            <a href="{link url="admin/pages/edit-{$page->pageID}"}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="5">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Seiten erstellt.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deletePage(id) {
            modal.confirm("Möchtest du diese Seite wirklich löschen. Dies kann nicht rückgängig gemacht werden.", function() {
                var data = ajax.call(9, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#pageentry" + id).fadeOut(function() {
                      var elems = document.getElementById("search_list").getElementsByTagName("tr");
                      var found = 0;
                      for(var i = 0; i < elems.length; i++) {
                        if(elems[i].style.display !== "none") {
                          found++;
                        }
                      }
                      if(found == 0) {
                        document.getElementById("search_list").innerHTML = '<tr><td colspan="5"><div class="ui info message"><ul class="list"><li>Es wurden noch keine Seiten erstellt.</li></ul></div></td></tr>';
                      }
                    });
                } else {
                    toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
            });
        }

        function createPage() {
          modal.modal("Seite erstellen", '' +
            '<p>Wähle den Typ der zu erstellenden Seite aus:' +
            '<br>Beachte: Der Typ einer Seite kann nach der Erstellung <b>NICHT</b> geändert werden.</p>' +
            '<form action="#" method="post" id="addform">' +
            '<div class="ui radio checkbox">' +
              '<input type="radio" checked value="0" name="type">' +
              '<label>WYSIWYG</label>' +
              '<small class="helper">erstellung mit dem WYSIWYG Editor und weiteren nutzbaren Variablen</small>' +
            '</div><br><br>' +
            '<div class="ui radio checkbox">' +
              '<input type="radio" value="1" name="type">' +
              '<label>HTML</label>' +
              '<small class="helper">HTML lässt dich eigenes HTML und JavaScript einbinden.</small>' +
            '</div><br><br>' +
            '<div class="ui radio checkbox">' +
              '<input type="radio" value="2" name="type">' +
              '<label>Template</label>' +
              '<small class="helper">entspricht HTML, zusätzlich wird Smarty Templatescripting unterstützt.</small>' +
            '</div><br><br>' +
            '<button type="submit" name="submit" class="ui blue submit button">Absenden</button>' + 
            '</form>' +
          '');
          document.getElementById("addform").addEventListener("submit", function(e) {
            e.preventDefault();
            var elems = document.getElementsByName("type");
            var value = "0";
            for(var i = 0; i < elems.length; i++) {
              if(elems[i].checked) {
                value = elems[i].value;
              }
            }
            value = parseInt(value);
            var links = ["{link url="admin/pages/add-0"}", "{link url="admin/pages/add-1"}", "{link url="admin/pages/add-2"}"];
            window.location.href = links[value];
          });
        }

        $('.ui.selection.dropdown').dropdown();
    </script>
{else if $site['site'] == "add"}
<a class="ui blue button right floated" href="{link url="admin/pages"}">Seiten Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['title'] !== false || $site['errors']['text'] !== false || $site['errors']['url'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/pages/add-{$site['type']}"}" method="post">
    <div class="field required{if $site['errors']['title'] !== false} error{/if}">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="{if isset($tpl['post']['title']) && !$site['success']}{$tpl['post']['title']}{/if}">
        </div>
    </div>
    <div class="field required{if $site['errors']['url'] !== false} error{/if}">
    <label>URL</label>
        <div class="ui labeled input">
            <div class="ui label">
              {$__KT['mainurl']}page/
            </div>
            <input type="text" name="url" value="{if isset($tpl['post']['url']) && !$site['success']}{$tpl['post']['url']}{/if}">
        </div>
    </div>
    <div class="field">
        <label>Zugriff</label>
        <div class="ui multiple selection dropdown groups">
            <input type="hidden" name="groupsaccess">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
        <small class="helper">Wähle hier alle Gruppen aus welche Zugriff auf diese Seite haben sollen, wähle keine Gruppe aus, wenn alle zugriff auf diese Seite haben sollen (auch Gäste).</small>
    </div>
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
        <label>Inhalt <a href="javascript:variablesInfo()"><i class="icon question"></i></a></label>
        <textarea id="text" rows="10" name="text">{if isset($tpl['post']['text']) && !$site['success']}{$tpl['post']['text']}{/if}</textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['title'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['url'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['title'] !== false}
              <li>{$site['errors']['title']}</li>
            {/if}
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
            {/if}
            {if $site['errors']['token'] !== false}
              <li>{$site['errors']['token']}</li>
            {/if}
            {if $site['errors']['url'] !== false}
              <li>{$site['errors']['url']}</li>
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

{if $site['type'] == "1"}
  {include file="codeeditor_html.tpl" selector="#text"}
{else if $site['type'] == "2"}
  {include file="codeeditor_smarty.tpl" selector="#text"}
{else}
  {include file="wysiwyg.tpl" template="false" selector="#text"}
{/if}
<script>
$('.ui.selection.dropdown.groups').dropdown({
    values: [
        {foreach from=$site['allgroups'] item="group"}
        {
        {if isset($tpl['post']['groupsaccess']) && !$site['success']}
          {if $site['selectedgroups']|is_array}
            {if (String) $group->groupID|in_array:$site['selectedgroups']}
              selected: true,
            {/if}
          {/if}
        {/if}
          name: '{$group->getGroupBadge()}',
          value: "{$group->groupID}"
        },
        {/foreach}
    ]
});
{if $site['type'] !== "1" && $site['type'] !== "2"}
  {literal}
  function variablesInfo() {
    modal.modal("Variablen", '' +
    '<p>Du kannst in Seiten, folgende Variablen verwenden, ist ein Benutzer nicht eingeloggt, werden diese Variablen mit Beispieldaten gefüllt.</p>' +
    '<p></p>' +
    '<table class="ui celled table">' +
      '<thead>' +
          '<tr>' +
              '<th>Variable</th>' +
              '<th>Inhalt</th>' +
          '</tr>' +
      '</thead>' +
      '<tbody>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERNAME}</i></td>' +
          '<td data-label="Inhalt">Benutzername</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERID}</i></td>' +
          '<td data-label="Inhalt">Benutzer ID</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERGROUP}</i></td>' +
          '<td data-label="Inhalt">Gruppenname des Benutzers</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@EMAIL}</i></td>' +
          '<td data-label="Inhalt">E-Mail Adresse des Benutzer</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@TICKETS}</i></td>' +
          '<td data-label="Inhalt">Anzahl der Tickets des Benutzers</td>' +
        '</tr>' +
      '</tbody>' +
    '</table>' +
    '');
  }
  {/literal}
{else if $site['type'] == "1"}
  {literal}
  function variablesInfo() {
    modal.modal("Information", '' +
    '<p>Du kannst im HTML Modus normales HTML verwenden. Beachte bitte dass wir deinen Code nicht überprüfen. Du kannst ebenfalls diese Variablen verwenden um deine Seite dynamisch zu gestalten.</p>' +
    '<p></p>' +
    '<table class="ui celled table">' +
      '<thead>' +
          '<tr>' +
              '<th>Variable</th>' +
              '<th>Inhalt</th>' +
          '</tr>' +
      '</thead>' +
      '<tbody>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERNAME}</i></td>' +
          '<td data-label="Inhalt">Benutzername</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERID}</i></td>' +
          '<td data-label="Inhalt">Benutzer ID</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERGROUP}</i></td>' +
          '<td data-label="Inhalt">Gruppenname des Benutzers</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@EMAIL}</i></td>' +
          '<td data-label="Inhalt">E-Mail Adresse des Benutzer</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@TICKETS}</i></td>' +
          '<td data-label="Inhalt">Anzahl der Tickets des Benutzers</td>' +
        '</tr>' +
      '</tbody>' +
    '</table>' +
    '');
  }
  {/literal}
{else if $site['type'] == "2"}
  {literal}
  function variablesInfo() {
    modal.modal("Information", '' +
    '<p>Im Template Modus kannst du mit Template-Scripting deine Seite erstellen. Du verwendest hier die Scripting Sprache <a href="https://www.smarty.net/docs/en/">Smarty</a> in der Version 3.</p>' +
    '<p>Du kannst die <i>$__KT</i> Variable verwenden, diese beinhaltet die gesamte Konfirgurationsdatei sowie weitere wichtige Hinweise. Die Variable <i>$tpl</i> beinhaltet Variablen wie <i>$_COOKIE</i> oder <i>$_POST</i></p>' +
    '');
  }
  {/literal}
{/if}
</script>
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="{link url="admin/pages"}">Seiten Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['title'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/pages/edit-{$site['page']->pageID}"}" method="post">
    <div class="field required{if $site['errors']['title'] !== false} error{/if}">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="{$site['page']->title}">
        </div>
    </div>
    <div class="field required{if $site['errors']['url'] !== false} error{/if}">
    <label>URL</label>
        <div class="ui labeled input{if $site['page']->system == 1} disabled{/if}">
            <div class="ui label">
              {$__KT['mainurl']}page/
            </div>
            <input type="text" {if $site['page']->system == 1}readonly{/if} name="url" value="{$site['page']->url}">
        </div>
    </div>
    <div class="field">
        <label>Zugriff</label>
        <div class="ui multiple selection dropdown groups{if $site['page']->system == 1} disabled{/if}">
            <input type="hidden" name="groupsaccess">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
        <small class="helper">Wähle hier alle Gruppen aus welche Zugriff auf diese Seite haben sollen, wähle keine Gruppe aus, wenn alle zugriff auf diese Seite haben sollen (auch Gäste).</small>
    </div>
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
        <label>Inhalt <a href="javascript:variablesInfo()"><i class="icon question"></i></a></label>
        <textarea id="text" rows="10" name="text">{$site['page']->content}</textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['title'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['title'] !== false}
              <li>{$site['errors']['title']}</li>
            {/if}
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
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
{if $site['page']->type == "1"}
  {include file="codeeditor_html.tpl" selector="#text"}
{else if $site['page']->type == "2"}
  {include file="codeeditor_smarty.tpl" selector="#text"}
{else}
  {include file="wysiwyg.tpl" template="false" selector="#text"}
{/if}
<script>
$('.ui.selection.dropdown.groups').dropdown({
    values: [
        {foreach from=$site['allgroups'] item="group"}
        {
          {if (String) $group->groupID|in_array:$site['selectedgroups']}
            selected: true,
          {/if}
          name: '{$group->getGroupBadge()}',
          value: "{$group->groupID}"
        },
        {/foreach}
    ],
});
{if $site['page']->type !== "1" && $site['page']->type !== "2"}
  {literal}
  function variablesInfo() {
    modal.modal("Variablen", '' +
    '<p>Du kannst in Seiten, folgende Variablen verwenden, ist ein Benutzer nicht eingeloggt, werden diese Variablen mit Beispieldaten gefüllt.</p>' +
    '<p></p>' +
    '<table class="ui celled table">' +
      '<thead>' +
          '<tr>' +
              '<th>Variable</th>' +
              '<th>Inhalt</th>' +
          '</tr>' +
      '</thead>' +
      '<tbody>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERNAME}</i></td>' +
          '<td data-label="Inhalt">Benutzername</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERID}</i></td>' +
          '<td data-label="Inhalt">Benutzer ID</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERGROUP}</i></td>' +
          '<td data-label="Inhalt">Gruppenname des Benutzers</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@EMAIL}</i></td>' +
          '<td data-label="Inhalt">E-Mail Adresse des Benutzer</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@TICKETS}</i></td>' +
          '<td data-label="Inhalt">Anzahl der Tickets des Benutzers</td>' +
        '</tr>' +
      '</tbody>' +
    '</table>' +
    '');
  }
  {/literal}
{else if $site['page']->type == "1"}
  {literal}
  function variablesInfo() {
    modal.modal("Information", '' +
    '<p>Du kannst im HTML Modus normales HTML verwenden. Beachte bitte dass wir deinen Code nicht überprüfen. Du kannst ebenfalls diese Variablen verwenden um deine Seite dynamisch zu gestalten.</p>' +
    '<p></p>' +
    '<table class="ui celled table">' +
      '<thead>' +
          '<tr>' +
              '<th>Variable</th>' +
              '<th>Inhalt</th>' +
          '</tr>' +
      '</thead>' +
      '<tbody>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERNAME}</i></td>' +
          '<td data-label="Inhalt">Benutzername</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERID}</i></td>' +
          '<td data-label="Inhalt">Benutzer ID</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@USERGROUP}</i></td>' +
          '<td data-label="Inhalt">Gruppenname des Benutzers</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@EMAIL}</i></td>' +
          '<td data-label="Inhalt">E-Mail Adresse des Benutzer</td>' +
        '</tr>' +
        '<tr>' +
          '<td data-label="Variable"><i>{@TICKETS}</i></td>' +
          '<td data-label="Inhalt">Anzahl der Tickets des Benutzers</td>' +
        '</tr>' +
      '</tbody>' +
    '</table>' +
    '');
  }
  {/literal}
{else if $site['page']->type== "2"}
  {literal}
  function variablesInfo() {
    modal.modal("Information", '' +
    '<p>Im Template Modus kannst du mit Template-Scripting deine Seite erstellen. Du verwendest hier die Scripting Sprache <a href="https://www.smarty.net/docs/en/">Smarty</a> in der Version 3.</p>' +
    '<p>Du kannst die <i>$__KT</i> Variable verwenden, diese beinhaltet die gesamte Konfirgurationsdatei sowie weitere wichtige Hinweise. Die Variable <i>$tpl</i> beinhaltet Variablen wie <i>$_COOKIE</i> oder <i>$_POST</i></p>' +
    '');
  }
  {/literal}
{/if}
</script>
{/if}