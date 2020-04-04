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
              <div class="item" data-value="1">Name</div>
              <div class="item" data-value="2">Eingabefelder</div>
            </div>
          </div>
        </div>
      </div>
      <div class="five wide column right floated">
        <br>
        <a class="ui blue button right floated" href="index.php?admin/ticketcategories/add">Ticket Kategorie erstellen</a>
      </div>
    </div>
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Eingabefelder</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$site['ticketcategories'] item="category"}
        <tr id="ticketcategoryentry{$category->categoryID}">
        <td data-label="ID">{$category->categoryID}</td>
        <td data-label="Name">{$category->getName()}</a></td>
        <td data-label="Eingabefelder">{$category->getInputCount()}</a></td>
        <td data-label="Aktion">
            <a href="javascript:deleteCategory({$category->categoryID});" data-tooltip="Löschen"><i class="icon times"></i></a>
            <a href="index.php?admin/ticketcategories/edit-{$category->categoryID}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="4">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Ticket Kategorien eingetragen.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deleteCategory(id) {
            modal.confirm("Möchtest du diese Kategorie wirklich löschen. Dies kann nicht rückgängig gemacht werden.<br><b>Beachte:</b> alle in dieser Kategorie befindlichen Tickets werden <b>NICHT</b> gelöscht.", function() {
                var data = ajax.call(12, id);
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
                    $("#ticketcategoryentry" + id).fadeOut();
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
        $('.ui.selection.dropdown').dropdown();
    </script>
{else if $site['site'] == "add"}
<a class="ui blue button right floated" href="index.php?admin/ticketcategories">Ticket Kategorien Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['custominput'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="index.php?admin/ticketcategories/add" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="{if isset($tpl['post']['text']) && !$site['success']}{$tpl['post']['text']}{/if}">
        </div>
    </div>
    <div class="ui divider"></div>
    <button class="ui button float-right" type="button" onClick="custominput.addField()">Eingabefeld hinzufügen</button>
    <br>
    <br>
    <div class="ui celled list" id="customInputsContainer">
    {if isset($tpl['post']['custominputCounter']) && !$site['success']}
      {$tpl['post']['custominputCounter'] = $tpl['post']['custominputCounter']|intval}
      {for $i=1 to $tpl['post']['custominputCounter']}
        {assign var="inputdata" value="customInputData{$i}"}
        {assign var="inputtitle" value="customInputTitle{$i}"}
        {if isset($tpl['post'][$inputdata]) && isset($tpl['post'][$inputtitle])}
          <div class="item customInputListEntry" id="custominputentry{$i}" data-id="{$i}">
            <span class="name">{$tpl['post'][$inputtitle]}</span>
            <span class="float-right">
              <a onclick="custominput.removeField(this)" data-tooltip="Löschen"><i class="icon times"></i></a>
              <a onclick="custominput.editField(this)" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
            </span>
            <textarea name="customInputData{$i}" class="display-none" data-id="{$i}">{$tpl['post'][$inputdata]}</textarea>
            <textarea name="customInputTitle{$i}" class="display-none" data-id="{$i}">{$tpl['post'][$inputtitle]}</textarea>
          </div>
        {/if}
      {/for}

    {/if}
    </div>

    <br>
    <br>
    <br>
    <input type="hidden" id="custominputCounter" name="custominputCounter" value="{if isset($tpl['post']['custominputCounter']) && !$site['success']}{$tpl['post']['custominputCounter']}{/if}">
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['custominput'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
            {/if}
            {if $site['errors']['token'] !== false}
              <li>{$site['errors']['token']}</li>
            {/if}
            {if $site['errors']['custominput'] !== false}
              <li>{$site['errors']['custominput']}</li>
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
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="index.php?admin/ticketcategories">Ticket Kategorien Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['custominput'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="index.php?admin/ticketcategories/edit-{$site['ticketcategory']->categoryID}" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="{$site['ticketcategory']->getName()}">
        </div>
    </div>
    <div class="ui divider"></div>
    <button class="ui button float-right" type="button" onClick="custominput.addField()">Eingabefeld hinzufügen</button>
    <br>
    <br>
    <div class="ui celled list" id="customInputsContainer">
      {for $i=0 to $site['ticketcategory']->getInputCount()}
        {assign var="inputdata" value="customInputData{$i}"}
        {assign var="inputtitle" value="customInputTitle{$i}"}
        {if isset($site['inputjson'][$i]) && isset($site['inputnames'][$i])}
          <div class="item customInputListEntry" id="custominputentry{$i}" data-id="{$i}">
            <span class="name">{$site['inputnames'][$i]}</span>
            <span class="float-right">
              <a onclick="custominput.removeField(this)" data-tooltip="Löschen"><i class="icon times"></i></a>
              <a onclick="custominput.editField(this)" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
            </span>
            <textarea name="customInputData{$i}" class="display-none" data-id="{$i}">{$site['inputjson'][$i]}</textarea>
            <textarea name="customInputTitle{$i}" class="display-none" data-id="{$i}">{$site['inputnames'][$i]}</textarea>
          </div>
        {/if}
      {/for}
    </div>

    <br>
    <br>
    <br>
    <input type="hidden" id="custominputCounter" name="custominputCounter" value="{$site['ticketcategory']->getInputCount()}">
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['custominput'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
            {/if}
            {if $site['errors']['token'] !== false}
              <li>{$site['errors']['token']}</li>
            {/if}
            {if $site['errors']['custominput'] !== false}
              <li>{$site['errors']['custominput']}</li>
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
{/if}