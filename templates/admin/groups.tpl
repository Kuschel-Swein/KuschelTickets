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
              <div class="item" data-value="2">Badge</div>
            </div>
          </div>
        </div>
      </div>
      <div class="five wide column right floated">
        <br>
        <a class="ui blue button right floated" href="{link url="admin/groups/add"}">Gruppe erstellen</a>
      </div>
    </div>
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Badge</th>
            <th>System</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$site['groups'] item="group"}
        <tr id="groupentry{$group->groupID}">
        <td data-label="ID">{$group->groupID}</td>
        <td data-label="Name">{$group->name}</a></td>
        <td data-label="Badge">{$group->getGroupBadge()}</a></td>
        <td data-label="System">{if $group->system == 1}<span data-tooltip="Ja"><i class="icon check"></i></span>{else}<span data-tooltip="Nein"><i class="icon times"></i></span>{/if}</a></td>
        <td data-label="Aktion">
          {if $group->system !== 1}
            <a href="javascript:deleteGroup({$group->groupID});" data-tooltip="Löschen"><i class="icon times"></i></a>
          {/if}
            <a href="{link url="admin/groups/edit-{$group->groupID}"}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="5">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Gruppen erstellt, dieser Fehler sollte nicht auftreten.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deleteGroup(id) {
            modal.confirm("Möchtest du diese Gruppe wirklich löschen. Dies kann nicht rückgängig gemacht werden.<br><b>Beachte:</b> alle Nutzer welche diese Gruppe gesetzt haben, werden in die Standartgruppe verschoben.", function() {
                var data = ajax.call(11, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#groupentry" + id).fadeOut(function() {
                      var elems = document.getElementById("search_list").getElementsByTagName("tr");
                      var found = 0;
                      for(var i = 0; i < elems.length; i++) {
                        if(elems[i].style.display !== "none") {
                          found++;
                        }
                      }
                      if(found == 0) {
                        document.getElementById("search_list").innerHTML = '<tr><td colspan="5"><div class="ui info message"><ul class="list"><li>Es wurden noch keine Gruppen erstellt, dieser Fehler sollte nicht auftreten.</li></ul></div></td></tr>';
                      }
                    });
                } else {
                    toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
            });
        }
        $('.ui.selection.dropdown').dropdown();
    </script>
{else if $site['site'] == "add"}
<a class="ui blue button right floated" href="{link url="admin/groups"}">Gruppen Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['badge'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/groups/add"}" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" onchange="utils.previewText(this, 'Badge');" onkeyup="utils.previewText(this, 'Badge');" value="{if isset($tpl['post']['text']) && !$site['success']}{$tpl['post']['text']}{/if}">
        </div>
    </div>
    <div class="field{if $site['errors']['badge'] !== false} error{/if}">
      <label>Badge</label>
    </div>
    <div class="ui grid stackable container">
      {foreach from=$site['colors'] item="color"}
      <div class="five wide column overflow-x-auto">
        <div class="field">
          <div class="ui radio checkbox">
            <input type="radio" value="{$color}" name="badge"{if isset($tpl['post']['badge']) && !$site['success']}{if $tpl['post']['badge'] == $color} checked="checked"{/if}{/if}>
            <label><div class="ui {$color} label preview">Badge</div></label>
          </div>
        </div>
      </div>
      {/foreach}
    </div>
    <br>
    <br>
    <div class="field">
      <label>Berechtigungen</label>
    </div>
    <div class="ui tabmenu secondary pointing menu">
      <a class="active item" data-tab="first">
        Allgemeine Berechtigungen
      </a>
      <a class="item" data-tab="second">
        Moderative Berechtigungen
      </a>
      <a class="item" data-tab="third">
        Administrative Berechtigungen
      </a>
    </div>
    <div class="ui tab active" data-tab="first">
      {foreach from=$site['permissions'] item="permission"}
        {if $permission['name']|strpos:'general_' === 0}
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="{$permission['name']}"{if isset($tpl['post'][$permission['name']])}{if isset($tpl['post'][$permission['name']]) && !$site['success']} checked="checked"{/if}{/if}>
              <label>{$permission['display']}</label>
            </div>
          </div>
        {/if}
      {/foreach}
    </div>
    <div class="ui tab" data-tab="second">
      {foreach from=$site['permissions'] item="permission"}
        {if $permission['name']|strpos:'mod_' === 0}
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="{$permission['name']}"{if isset($tpl['post'][$permission['name']])}{if isset($tpl['post'][$permission['name']]) && !$site['success']} checked="checked"{/if}{/if}>
              <label>{$permission['display']}</label>
            </div>
          </div>
        {/if}
      {/foreach}
    </div>
    <div class="ui tab" data-tab="third">
      {foreach from=$site['permissions'] item="permission"}
        {if $permission['name']|strpos:'admin_' === 0}
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="{$permission['name']}"{if isset($tpl['post'][$permission['name']])}{if isset($tpl['post'][$permission['name']]) && !$site['success']} checked="checked"{/if}{/if}>
              <label>{$permission['display']}</label>
            </div>
          </div>
        {/if}
      {/foreach}
    </div>
    <br>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['badge'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
            {/if}
            {if $site['errors']['token'] !== false}
              <li>{$site['errors']['token']}</li>
            {/if}
            {if $site['errors']['badge'] !== false}
              <li>{$site['errors']['badge']}</li>
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
  $('.menu .item').tab();
</script>
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="{link url="admin/groups"}">Gruppen Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['badge'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/groups/edit-{$site['editgroup']->groupID}"}" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" onchange="utils.previewText(this, 'Badge');" onkeyup="utils.previewText(this, 'Badge');" value="{$site['editgroup']->name}">
        </div>
    </div>
    <div class="field{if $site['errors']['badge'] !== false} error{/if}">
      <label>Badge</label>
    </div>
    <div class="ui grid stackable container">
      {foreach from=$site['colors'] item="color"}
        <div class="five wide column overflow-x-auto">
          <div class="field">
            <div class="ui radio checkbox">
              <input type="radio" value="{$color}" name="badge"{if $site['badge'] == $color} checked="checked"{/if}>
              <label><div class="ui {$color} label preview">{$site['editgroup']->name}</div></label>
            </div>
          </div>
        </div>
      {/foreach}
    </div>
    <br>
    <br>
    {if $site['editgroup']->groupID !== 1}
      <div class="field">
        <label>Berechtigungen</label>
      </div>
      <div class="ui tabmenu secondary pointing menu">
      <a class="active item" data-tab="first">
        Allgemeine Berechtigungen
      </a>
      <a class="item" data-tab="second">
        Moderative Berechtigungen
      </a>
      <a class="item" data-tab="third">
        Administrative Berechtigungen
      </a>
    </div>
    <div class="ui tab active" data-tab="first">
      {foreach from=$site['permissions'] item="permission"}
        {if $permission['name']|strpos:'general_' === 0}
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="{$permission['name']}"{if isset($site['gpermissions'][$permission['name']])}{if $site['gpermissions'][$permission['name']] == "1"} checked="checked"{/if}{/if}>
              <label>{$permission['display']}</label>
            </div>
          </div>
        {/if}
      {/foreach}
    </div>
    <div class="ui tab" data-tab="second">
      {foreach from=$site['permissions'] item="permission"}
        {if $permission['name']|strpos:'mod_' === 0}
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="{$permission['name']}"{if isset($site['gpermissions'][$permission['name']])}{if $site['gpermissions'][$permission['name']] == "1"} checked="checked"{/if}{/if}>
              <label>{$permission['display']}</label>
            </div>
          </div>
        {/if}
      {/foreach}
    </div>
    <div class="ui tab" data-tab="third">
      {foreach from=$site['permissions'] item="permission"}
        {if $permission['name']|strpos:'admin_' === 0}
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="{$permission['name']}"{if isset($site['gpermissions'][$permission['name']])}{if $site['gpermissions'][$permission['name']] == "1"} checked="checked"{/if}{/if}>
              <label>{$permission['display']}</label>
            </div>
          </div>
        {/if}
      {/foreach}
    </div>
      <br>
    {else}
      <div class="ui warning message display-block">
        <ul class="list">
          <li>Aus Sicherheitsgründen kannst du die Berechtigungen der System Administrator Gruppe nicht verändern.</li>
        </ul>
      </div>
    {/if}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['token'] !== false || $site['errors']['badge'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['text'] !== false}
              <li>{$site['errors']['text']}</li>
            {/if}
            {if $site['errors']['token'] !== false}
              <li>{$site['errors']['token']}</li>
            {/if}
            {if $site['errors']['badge'] !== false}
              <li>{$site['errors']['badge']}</li>
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
  $('.menu .item').tab();
</script>
{/if}