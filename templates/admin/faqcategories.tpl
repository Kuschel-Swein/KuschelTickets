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
              <div class="item" data-value="2">FAQs</div>
            </div>
          </div>
        </div>
      </div>
      <div class="five wide column right floated">
        <br>
        <a class="ui blue button right floated" href="{link url="admin/faqcategories/add"}">Kategorie erstellen</a>
      </div>
    </div>
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>FAQs</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$site['categories'] item="category"}
        <tr id="faqcategory{$category->categoryID}">
        <td data-label="ID">{$category->categoryID}</td>
        <td data-label="Name">{$category->name}</a></td>
        <td data-label="FAQs">{$category->getFAQs()|count}</a></td>
        <td data-label="Aktion">
            <a href="javascript:deleteCategory({$category->categoryID});" data-tooltip="Löschen"><i class="icon times"></i></a>
            <a href="{link url="admin/faqcategories/edit-{$category->categoryID}"}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="4">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Kategorien eingetragen.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deleteCategory(id) {
            modal.confirm("Möchtest du diese Kategorie wirklich löschen. Dies kann nicht rückgängig gemacht werden.<br><b>Beachte:</b> alle in dieser Kategorie befindlichen FAQ Einträge werden ebenfalls gelöscht.", function() {
                var data = ajax.call(8, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#faqcategory" + id).fadeOut(function() {
                      var elems = document.getElementById("search_list").getElementsByTagName("tr");
                      var found = 0;
                      for(var i = 0; i < elems.length; i++) {
                        if(elems[i].style.display !== "none") {
                          found++;
                        }
                      }
                      if(found == 0) {
                        document.getElementById("search_list").innerHTML = '<tr><td colspan="4"><div class="ui info message"><ul class="list"><li>Es wurden noch keine Kategorien eingetragen.</li></ul></div></td></tr>';
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
<a class="ui blue button right floated" href="{link url="admin/faqcategories"}">Kategorien Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/faqcategories/add"}" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="{if isset($tpl['post']['text']) && !$site['success']}{$tpl['post']['text']}{/if}">
        </div>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
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
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="{link url="admin/faqcategories"}">Kategorien Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['text'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/faqcategories/edit-{$site['category']->categoryID}"}" method="post">
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="{$site['category']->name}">
        </div>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['text'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
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
{/if}