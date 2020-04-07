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
              <div class="item" data-value="1">Benutzername</div>
              <div class="item" data-value="2">E-Mail</div>
              <div class="item" data-value="3">Benutzergruppe</div>
            </div>
          </div>
        </div>
      </div>
      <div class="four wide column right floated">
        <br>
        <a class="ui blue button right floated" href="{link url="admin/accounts/add"}">Account erstellen</a>
      </div>
    </div>
    
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Benutzername</th>
            <th>E-Mail</th>
            <th>Benutzergruppe</th>
            <th>Tickets</th>
            <th>Gesperrt</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$site['accounts'] item="account"}
        <tr id="accountentry{$account->userID}">
        <td data-label="ID">{$account->userID}</td>
        <td data-label="Benutzername">{$account->getUserName()}</a></td>
        <td data-label="E-Mail"><a href="mailto:{$account->getEmail()}">{$account->getEmail()}</a></td>
        <td data-label="Benutzergruppe">{$account->getGroup()->getGroupName()}</td>
        <td data-label="Tickets">{$account->getTicketCount()}</td>
        <td data-label="Gesperrt">{if $account->isBanned()}<span data-tooltip="Ja"><i class="icon check"></i></span>{else}<span data-tooltip="Nein"><i class="icon times"></i></span>{/if}</td>
        <td data-label="Aktion">
          {if !$account->hasPermission("admin.bypass.bannable")}
            <a href="{link url="admin/accounts/ban-{$account->userID}"}" data-tooltip="Sperrung verwalten"><i class="icon ban"></i></a>
          {/if}
          {if !$account->hasPermission("admin.bypass.delete") && $account->userID !== $__KT['user']->userID}
            <a href="javascript:deleteUser({$account->userID});" data-tooltip="Löschen"><i class="icon times"></i></a>
          {/if}
          {if !$__KT['user']->hasPermission("admin.login.other") && $account->userID !== $__KT['user']->userID && !$account->hasPermission("admin.bypass.login.other")}
            <a href="javascript:loginAs({$account->userID});" data-tooltip="als dieser Benutzer einloggen"><i class="icon lock"></i></a>
          {/if}
            <a href="{link url="admin/accounts/edit-{$account->userID}"}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="7">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Benutzer erstellt. Dieser Fehler sollte nicht auftreten.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deleteUser(id) {
            modal.confirm("Möchtest du diesen Account wirklich löschen. Dies kann nicht rückgängig gemacht werden.", function() {
                var data = ajax.call(10, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#accountentry" + id).fadeOut(function() {
                      var elems = document.getElementById("search_list").getElementsByTagName("tr");
                      var found = 0;
                      for(var i = 0; i < elems.length; i++) {
                        if(elems[i].style.display !== "none") {
                          found++;
                        }
                      }
                      if(found == 0) {
                        document.getElementById("search_list").innerHTML = '<tr><td colspan="7"><div class="ui info message"><ul class="list"><li>Es wurden noch keine Benutzer erstellt. Dieser Fehler sollte nicht auftreten.</li></ul></div></td></tr>';
                      }
                    });
                } else {
                    toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
            });
        }
        function loginAs(id) {
          modal.confirm("Möchtest du dich wirklich als dieser Benutzer einloggen? Du musst dich erst wieder ausloggen und einloggen um wieder in deinen Account zu gelangen.", function() {
            var data = ajax.call(4, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                } else {
                    toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
          });
        }
        $('.ui.selection.dropdown').dropdown();
    </script>
{else if $site['site'] == "ban"}
<a class="ui blue button right floated" href="{link url="admin/accounts"}">Accounts Auflisten</a>
<br>
<br>
{if $site['banuser']->userID == $__KT['user']->userID}
<div class="ui warning message">
  <ul class="list">
    <li>Achtung! Du bearbeitest gerade dein eigenes Benutzerkonto, unüberlegte Änderungen können dazu führen dass du dich nicht mehr einloggen kannst!</li>
  </ul>
</div>
{/if}
<form class="ui form{if $site['errors']['reason'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/accounts/ban-{$site['banuser']->userID}"}" method="post">
    {if $site['banuser']->isBanned()}
    <div class="field">
      <label>Sperrungsgrund</label>
      <blockquote>{$site['banuser']->getBanReason()}</blockquote>
    </div>
    <div class="ui checkbox">
      <input type="checkbox" name="unban">
      <label>Entsperren</label>
    </div>
    <br>
    <br>
    {else}
    <div class="field required{if $site['errors']['reason'] !== false} error{/if}">
    <label>Grund</label>
      <textarea id="text" name="reason">{if isset($tpl['post']['reason']) && !$site['success']}{$tpl['post']['reason']}{/if}</textarea>
    </div>
    <div class="ui checkbox">
      <input type="checkbox" name="ban">
      <label>Sperren</label>
    </div>
    <br>
    <br>
    {/if}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['reason'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['reason'] !== false}
              <li>{$site['errors']['reason']}</li>
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
  {include file="wysiwyg.tpl" selector="#text" templates="false"}
{else if $site['site'] == "add"}
<a class="ui blue button right floated" href="{link url="admin/accounts"}">Accounts Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['username'] !== false || $site['errors']['email'] !== false || $site['errors']['group'] !== false || $site['errors']['password'] !== false || $site['errors']['password_confirm'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] == true} success{/if}" action="{link url="admin/accounts/add"}" method="post">
<div class="field required{if $site['errors']['username'] !== false} error{/if}">
  <label>Benutzername</label>
  <div class="ui input">
    <input type="text" name="username" value="{if isset($tpl['post']['username']) && !$site['success']}{$tpl['post']['username']}{/if}">
  </div>
</div>
<div class="field required{if $site['errors']['email'] !== false} error{/if}">
  <label>E-Mail Adresse</label>
  <div class="ui input">
    <input type="email" name="email" value="{if isset($tpl['post']['email']) && !$site['success']}{$tpl['post']['email']}{/if}">
  </div>
</div>
<div class="field required{if $site['errors']['password'] !== false} error{/if}">
  <label>Passwort</label>
  <div class="ui input">
    <input type="password" name="password">
  </div>
</div>
<div class="field required{if $site['errors']['password_confirm'] !== false && !$site['success']} error{/if}">
  <label>Passwort bestätigen</label>
  <div class="ui input">
    <input type="password" name="password_confirm">
  </div>
</div>
<div class="field required{if $site['errors']['group'] !== false} error{/if}">
    <label>Benutzergruppe</label>
    <div class="ui selection dropdown usergroup">
        <input type="hidden" name="group" id="group">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
    </div>
</div>
<button type="submit" name="submit" class="ui blue submit button">Absenden</button>
<input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
{if $site['success'] == true}
<div class="ui success message">
  <ul class="list">
      <li>Dieser Account wurde erfolgreich erstellt.</li>
  </ul>
</div>
{/if}
{if $site['errors']['username'] !== false || $site['errors']['email'] !== false || $site['errors']['password'] !== false || $site['errors']['password_confirm'] !== false || $site['errors']['token'] !== false || $site['errors']['group'] !== false}
  <div class="ui error message">
    <ul class="list">
      {if $site['errors']['username'] !== false}
        <li>{$site['errors']['username']}</li>
      {/if}
      {if $site['errors']['email'] !== false}
        <li>{$site['errors']['email']}</li>
      {/if}
      {if $site['errors']['password'] !== false}
        <li>{$site['errors']['password']}</li>
      {/if}
      {if $site['errors']['password_confirm'] !== false}
        <li>{$site['errors']['password_confirm']}</li>
      {/if}
      {if $site['errors']['group'] !== false}
        <li>{$site['errors']['group']}</li>
      {/if}
      {if $site['errors']['token'] !== false}
        <li>{$site['errors']['token']}</li>
      {/if}
    </ul>
  </div>
{/if}
<script>
$('.ui.selection.dropdown.usergroup').dropdown({
    values: [
      {foreach from=$site['usergroups'] item="group"}
        {
        {if isset($tpl['post']['group']) && !$site['success']}
          {if $tpl['post']['group'] == $group->groupID}
            selected: true,
          {/if}
        {/if}
          name: "{$group->getGroupName()}",
          value: "{$group->groupID}"
        },
      {/foreach}
    ],
});
</script>
</form>
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="{link url="admin/accounts"}">Accounts Auflisten</a>
<br>
<br>
{if $site['edituser']->userID == $__KT['user']->userID}
<div class="ui warning message">
  <ul class="list">
    <li>Achtung! Du bearbeitest gerade dein eigenes Benutzerkonto, unüberlegte Änderungen können dazu führen dass du dich nicht mehr einloggen kannst!</li>
  </ul>
</div>
{/if}
<form class="ui form{if $site['errors']['username'] !== false || $site['errors']['email'] !== false || $site['errors']['group'] !== false || $site['errors']['password'] !== false || $site['errors']['password_confirm'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] == true} success{/if}" action="{link url="admin/accounts/edit-{$site['edituser']->userID}"}" method="post">
<div class="field required{if $site['errors']['username'] !== false} error{/if}">
  <label>Benutzername</label>
  <div class="ui input">
    <input type="text" name="username" value="{$site['edituser']->getUserName()}">
  </div>
</div>
<div class="field required{if $site['errors']['email'] !== false} error{/if}">
  <label>E-Mail Adresse</label>
  <div class="ui input">
    <input type="email" name="email" value="{$site['edituser']->getEmail()}">
  </div>
</div>
<div class="field {if $site['errors']['password'] !== false} error{/if}">
  <label>Passwort</label>
  <div class="ui input">
    <input type="password" name="password">
  </div>
  <small class="helper">fülle dieses Feld nur aus, wenn du das Passwort dieses Benutzers bearbeiten möchtest</small>
</div>
<div class="field {if $site['errors']['password_confirm'] !== false && !$site['success']} error{/if}">
  <label>Passwort bestätigen</label>
  <div class="ui input">
    <input type="password" name="password_confirm">
  </div>
  <small class="helper">fülle dieses Feld nur aus, wenn du das Passwort dieses Benutzers bearbeiten möchtest</small>
</div>
<div class="field required{if $site['errors']['group'] !== false} error{/if}">
    <label>Benutzergruppe</label>
    <div class="ui selection dropdown usergroup">
        <input type="hidden" name="group" id="group">
        <i class="dropdown icon"></i>
        <div class="default text"></div>
        <div class="menu">
        </div>
    </div>
</div>
<button type="submit" name="submit" class="ui blue submit button">Absenden</button>
<input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
{if $site['success'] == true}
<div class="ui success message">
  <ul class="list">
      <li>Dieser Account wurde erfolgreich bearbeitet.</li>
  </ul>
</div>
{/if}
{if $site['errors']['username'] !== false || $site['errors']['email'] !== false || $site['errors']['password'] !== false || $site['errors']['password_confirm'] !== false || $site['errors']['token'] !== false || $site['errors']['group'] !== false}
  <div class="ui error message">
    <ul class="list">
      {if $site['errors']['username'] !== false}
        <li>{$site['errors']['username']}</li>
      {/if}
      {if $site['errors']['email'] !== false}
        <li>{$site['errors']['email']}</li>
      {/if}
      {if $site['errors']['password'] !== false}
        <li>{$site['errors']['password']}</li>
      {/if}
      {if $site['errors']['password_confirm'] !== false}
        <li>{$site['errors']['password_confirm']}</li>
      {/if}
      {if $site['errors']['group'] !== false}
        <li>{$site['errors']['group']}</li>
      {/if}
      {if $site['errors']['token'] !== false}
        <li>{$site['errors']['token']}</li>
      {/if}
    </ul>
  </div>
{/if}
<script>
$('.ui.selection.dropdown.usergroup').dropdown({
    values: [
      {foreach from=$site['usergroups'] item="group"}
        {
          {if $site['edituser']->getGroup()->groupID == $group->groupID}
            selected: true,
          {/if}
          name: "{$group->getGroupName()}",
          value: "{$group->groupID}"
        },
      {/foreach}
    ],
});
</script>
</form>
{/if}