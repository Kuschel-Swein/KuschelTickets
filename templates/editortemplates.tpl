{include file="header.tpl" title="Editorvorlagen"}
{if $subpage == "index"}
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
              <div class="item" data-value="2">Beschreibung</div>
            </div>
          </div>
        </div>
      </div>
      <div class="five wide column right floated">
        <br>
        <a class="ui blue button right floated" href="{link url="editortemplates/add"}">Editorvorlage erstellen</a>
      </div>
    </div>
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Beschreibung</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$__KT['user']->getEditorTemplates() item="editortemplate"}
        <tr id="templateentry{$editortemplate->templateID}">
        <td data-label="ID">{$editortemplate->templateID}</td>
        <td data-label="Titel">{$editortemplate->title}</a></td>
        <td data-label="Beschreibung">{$editortemplate->description}</td>
        <td data-label="Aktion">
            <a href="javascript:deleteTemplate({$editortemplate->templateID});" data-tooltip="Löschen"><i class="icon times"></i></a>
            <a href="{link url="editortemplates/edit-{$editortemplate->templateID}"}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
            <a href="javascript:watchTemplate({$editortemplate->templateID})" data-tooltip="Ansehen"><i class="icon eye"></i></a>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="4">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Editorvorlagen erstellt.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deleteTemplate(id) {
            modal.confirm("Möchtest du diese Vorlage wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.", function() {
                var data = ajax.call(17, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#templateentry" + id).fadeOut(function() {
                      var elems = document.getElementById("search_list").getElementsByTagName("tr");
                      var found = 0;
                      for(var i = 0; i < elems.length; i++) {
                        if(elems[i].style.display !== "none") {
                          found++;
                        }
                      }
                      if(found == 0) {
                        document.getElementById("search_list").innerHTML = '<tr><td colspan="4"><div class="ui info message"><ul class="list"><li>Es wurden noch keine Editorvorlagen erstellt.</li></ul></div></td></tr>';
                      }
                    });
                } else {
                  toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
            });
        }

        function watchTemplate(id) {
          var data = ajax.call(18, id);
          var content = data['message'];
          modal.modal("Editorvorlage ansehen", content);
        }
        $('.ui.selection.dropdown').dropdown();
    </script>
{else if $subpage == "add"}
<a class="ui blue button right floated" href="{link url="editortemplates"}">Editorvorlagen Auflisten</a>
<br>
<br>
<form class="ui form{if $errors['text'] !== false || $errors['token'] !== false || $errors['title'] !== false || $errors['description'] !== false} error{/if}{if $success !== false} success{/if}" action="{link url="editortemplates/add"}" method="post">
    <div class="field required{if $errors['title'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="title" value="{if isset($tpl['post']['title']) && !$success}{$tpl['post']['title']}{/if}">
        </div>
    </div>
    <div class="field required{if $errors['description'] !== false} error{/if}">
    <label>Beschreibung</label>
        <div class="ui input">
            <input type="text" name="description" value="{if isset($tpl['post']['description']) && !$success}{$tpl['post']['description']}{/if}">
        </div>
    </div>
    <div class="field required{if $errors['text'] !== false} error{/if}">
      <label>Text</label>
      <textarea id="text" name="text">{if isset($tpl['post']['text']) && !$success}{$tpl['post']['text']}{/if}</textarea>
    </div>
    {$recaptcha}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $errors['text'] !== false || $errors['token'] !== false || $errors['title'] !== false || $errors['description'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $errors['title'] !== false}
              <li>{$errors['title']}</li>
            {/if}
            {if $errors['text'] !== false}
              <li>{$errors['text']}</li>
            {/if}
            {if $errors['token'] !== false}
              <li>{$errors['token']}</li>
            {/if}
            {if $errors['description'] !== false}
              <li>{$errors['description']}</li>
            {/if}
          </ul>
        </div>
    {/if}
    {if $success !== false}
        <div class="ui success message">
          <ul class="list">
            <li>{$success}</li>
          </ul>
        </div>
    {/if}
</form>
{include file="__wysiwyg.tpl" selector="#text" templates="false"}
{else if $subpage="edit"}
<a class="ui blue button right floated" href="{link url="editortemplates"}">Editorvorlagen Auflisten</a>
<br>
<br>
<form class="ui form{if $errors['text'] !== false || $errors['token'] !== false || $errors['title'] !== false || $errors['description'] !== false} error{/if}{if $success !== false} success{/if}" action="{link url="editortemplates/edit-{$editortpl->templateID}"}" method="post">
    <div class="field required{if $errors['title'] !== false} error{/if}">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="title" value="{$editortpl->title}">
        </div>
    </div>
    <div class="field required{if $errors['description'] !== false} error{/if}">
    <label>Beschreibung</label>
        <div class="ui input">
            <input type="text" name="description" value="{$editortpl->description}">
        </div>
    </div>
    <div class="field required{if $errors['text'] !== false} error{/if}">
      <label>Text</label>
      <textarea id="text" name="text">{$editortpl->content}</textarea>
    </div>
    {$recaptcha}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $errors['text'] !== false || $errors['token'] !== false || $errors['title'] !== false || $errors['description'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $errors['title'] !== false}
              <li>{$errors['title']}</li>
            {/if}
            {if $errors['text'] !== false}
              <li>{$errors['text']}</li>
            {/if}
            {if $errors['token'] !== false}
              <li>{$errors['token']}</li>
            {/if}
            {if $errors['description'] !== false}
              <li>{$errors['description']}</li>
            {/if}
          </ul>
        </div>
    {/if}
    {if $success !== false}
        <div class="ui success message">
          <ul class="list">
            <li>{$success}</li>
          </ul>
        </div>
    {/if}
</form>
{include file="__wysiwyg.tpl" selector="#text" templates="false"}
{/if}
{include file="footer.tpl"}