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
              <div class="item" data-value="2">URL</div>
            </div>
          </div>
        </div>
      </div>
      <div class="four wide column right floated">
        <br>
        <a class="ui blue button right floated" href="index.php?admin/pages/add">Seite erstellen</a>
      </div>
    </div>
    
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>URL</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$site['pages'] item="page"}
        <tr id="pageentry{$page['pageID']}">
        <td data-label="ID">{$page['pageID']}</td>
        <td data-label="Titel">{$page['title']}</a></td>
        <td data-label="URL"><a href="{$site['mainurl']}?page/{$page['url']}" target="_blank">{$site['mainurl']}?page/{$page['url']}</a></td>
        <td data-label="Aktion">
          {if $page['system'] !== "1"}
            <a href="javascript:deletePage({$page['pageID']});" data-tooltip="Löschen"><i class="icon times"></i></a>
          {/if}
            <a href="index.php?admin/pages/edit-{$page['pageID']}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
            <a href="javascript:showPage({$page['pageID']});" data-tooltip="Ansehen"><i class="icon eye"></i></a>
            <div id="pagecontent{$page['pageID']}" data-title="{$page['title']}" class="display-none">{$page['content']}</div>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="4">
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
                    $.uiAlert({
                        textHead: data['title'],
                        text: data['message'],
                        bgcolor: "#21ba45",
                        textcolor: "#fff",
                        position: "top-right",
                        icon: 'check',
                        time: 3
                    });
                    $("#pageentry" + id).fadeOut();
                } else {
                    $.uiAlert({
                        textHead: "Fehler",
                        text: "Es ist ein Fehler aufgetreten, bitte versuche es erneut.",
                        bgcolor: "#d01919",
                        textcolor: "#fff",
                        position: "top-right",
                        icon: 'times',
                        time: 3
                    });
                }
            });
        }

        function showPage(id) {
            modal.modal("Seite ansehen", '' +
            '<p><b>Titel: </b></p>' +
            '<p>' + document.getElementById("pagecontent" + id).dataset.title + '</p>' +
            '<p><b>Inhalt: </b></p>' +
            '<p>' + document.getElementById("pagecontent" + id).innerHTML + '</p>' +
            '');
        }
        $('.ui.selection.dropdown').dropdown();
    </script>
{else if $site['site'] == "add"}
<a class="ui blue button right floated" href="index.php?admin/pages">Seiten Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['title'] !== false || $site['errors']['loginneed'] !== false || $site['errors']['text'] !== false || $site['errors']['url'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="index.php?admin/pages/add" method="post">
    <div class="field required{if $site['errors']['title'] !== false} error{/if}">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="{if isset($tpl['post']['title']) && !$site['success']}{$tpl['post']['title']}{/if}">
        </div>
    </div>
    <div class="field required{if $site['errors']['url'] !== false} error{/if}">
    <label>URL</label>
        <div class="ui input">
            <input type="text" name="url" value="{if isset($tpl['post']['url']) && !$site['success']}{$tpl['post']['url']}{/if}">
        </div>
    </div>
    <div class="field required{if $site['errors']['loginneed'] !== false} error{/if}">
        <label>Login benötigt</label>
        <div class="ui selection dropdown loginneed">
            <input type="hidden" name="loginneed" id="type">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
        <label>Inhalt</label>
        <textarea id="text" rows="10" name="text">{if isset($tpl['post']['text']) && !$site['success']}{$tpl['post']['text']}{/if}</textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['loginneed'] !== false || $site['errors']['title'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['url'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['loginneed'] !== false}
              <li>{$site['errors']['loginneed']}</li>
            {/if}
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
{include file="wysiwyg.tpl" selector="#text"}
<script>
$('.ui.selection.dropdown.loginneed').dropdown({
    values: [
        {
        {if isset($tpl['post']['loginneed']) && !$site['success']}
          {if $tpl['post']['loginneed'] == 1}
            selected: true,
          {/if}
        {/if}
          name: "Ja",
          value: "1"
        },
        {
        {if isset($tpl['post']['loginneed']) && !$site['success']}
          {if $tpl['post']['loginneed'] == 0}
            selected: true,
          {/if}
        {/if}
          name: "Nein",
          value: "0"
        }
    ],
});
</script>
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="index.php?admin/pages">Seiten Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['title'] !== false || $site['errors']['loginneed'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="index.php?admin/pages/edit-{$site['page']['pageID']}" method="post">
    <div class="field required{if $site['errors']['title'] !== false} error{/if}">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="{$site['page']['title']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['url'] !== false} error{/if}">
    <label>URL</label>
        <div class="ui input">
            <input type="text" {if $site['page']['system'] == "1"}readonly{/if} name="url" value="{$site['page']['url']}">
        </div>
    </div>
    <div class="field required{if $site['errors']['loginneed'] !== false} error{/if}">
        <label>Login benötigt</label>
        <div class="ui selection dropdown loginneed">
            <input type="hidden" name="loginneed" id="type">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
        <label>Inhalt</label>
        <textarea id="text" rows="10" name="text">{$site['page']['content']}</textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['loginneed'] !== false || $site['errors']['title'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['loginneed'] !== false}
              <li>{$site['errors']['loginneed']}</li>
            {/if}
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
{include file="wysiwyg.tpl" selector="#text"}
<script>
$('.ui.selection.dropdown.loginneed').dropdown({
    values: [
        {
          {if $site['page']['login'] == "1"}
            selected: true,
          {/if}
          name: "Ja",
          value: "1"
        },
        {
          {if $site['page']['login'] == "0"}
            selected: true,
          {/if}
          name: "Nein",
          value: "0"
        }
    ],
});
</script>
{/if}