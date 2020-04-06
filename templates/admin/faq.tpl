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
              <div class="item" data-value="1">Frage</div>
              <div class="item" data-value="2">Kategorie</div>
            </div>
          </div>
        </div>
      </div>
      <div class="four wide column right floated">
        <br>
        <a class="ui blue button right floated" href="{link url="admin/faq/add"}">FAQ erstellen</a>
      </div>
    </div>
    
    <br>
    <br>
    <table class="ui celled table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Frage</th>
            <th>Kategorie</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody id="search_list">
        {foreach from=$site['faqs'] item="faq"}
        <tr id="faqentry{$faq->faqID}">
        <td data-label="ID">{$faq->faqID}</td>
        <td data-label="Frage">{$faq->getQuestion()}</a></td>
        <td data-label="Kategorie">{$faq->getCategoryName()}</td>
        <td data-label="Aktion">
            <a href="javascript:deleteFAQ({$faq->faqID});" data-tooltip="Löschen"><i class="icon times"></i></a>
            <a href="{link url="admin/faq/edit-{$faq->faqID}"}" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
            <a href="javascript:showFAQ({$faq->faqID});" data-tooltip="Ansehen"><i class="icon eye"></i></a>
            <div id="faqanswer{$faq->faqID}" data-question="{$faq->getQuestion()}" class="display-none">{$faq->getAnswer()}</div>
        </td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="4">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine FAQs eingetragen.</li>
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
    <script>
        function deleteFAQ(id) {
            modal.confirm("Möchtest du diesen Eintrag wirklich löschen. Dies kann nicht rückgängig gemacht werden.", function() {
                var data = ajax.call(7, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#faqentry" + id).fadeOut(function() {
                      var elems = document.getElementById("search_list").getElementsByTagName("tr");
                      var found = 0;
                      for(var i = 0; i < elems.length; i++) {
                        if(elems[i].style.display !== "none") {
                          found++;
                        }
                      }
                      if(found == 0) {
                        document.getElementById("search_list").innerHTML = '<tr><td colspan="4"><div class="ui info message"><ul class="list"><li>Es wurden noch keine FAQs eingetragen.</li></ul></div></td></tr>';
                      }
                    });
                } else {
                    toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
            });
        }

        function showFAQ(id) {
            modal.modal("FAQ Eintrag ansehen", '' +
            '<p><b>Frage: </b></p>' +
            '<p>' + document.getElementById("faqanswer" + id).dataset.question + '</p>' +
            '<p><b>Antwort: </b></p>' +
            '<p>' + document.getElementById("faqanswer" + id).innerHTML + '</p>' +
            '');
        }
        $('.ui.selection.dropdown').dropdown();
    </script>
{else if $site['site'] == "add"}
<a class="ui blue button right floated" href="{link url="admin/faq"}">FAQs Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['category'] !== false || $site['errors']['question'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/faq/add"}" method="post">
    <div class="field required{if $site['errors']['category'] !== false} error{/if}">
        <label>Kategorie</label>
        <div class="ui selection dropdown category">
            <input type="hidden" name="category" id="category">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
    <div class="field required{if $site['errors']['question'] !== false} error{/if}">
    <label>Frage</label>
        <div class="ui input">
            <input type="text" name="question" value="{if isset($tpl['post']['question']) && !$site['success']}{$tpl['post']['question']}{/if}">
        </div>
    </div>
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
        <label>Antwort</label>
        <textarea id="text" rows="10" name="text">{if isset($tpl['post']['text']) && !$site['success']}{$tpl['post']['text']}{/if}</textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['category'] !== false || $site['errors']['question'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['category'] !== false}
              <li>{$site['errors']['category']}</li>
            {/if}
            {if $site['errors']['question'] !== false}
              <li>{$site['errors']['question']}</li>
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
{include file="wysiwyg.tpl" template="false" selector="#text"}
<script>
$('.ui.selection.dropdown.category').dropdown({
    values: [
      {foreach from=$site['categorys'] item="category"}
        {
        {if isset($tpl['post']['category']) && !$site['success']}
          {if $tpl['post']['category'] == $category['id']}
            selected: true,
          {/if}
        {/if}
          name: "{$category['name']}",
          value: "{$category['id']}"
        },
      {/foreach}
    ],
});
</script>
{else if $site['site'] == "edit"}
<a class="ui blue button right floated" href="{link url="admin/faq"}">FAQs Auflisten</a>
<br>
<br>
<form class="ui form{if $site['errors']['category'] !== false || $site['errors']['question'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false} error{/if}{if $site['success'] !== false} success{/if}" action="{link url="admin/faq/edit-{$site['faq']->faqID}"}" method="post">
    <div class="field required{if $site['errors']['category'] !== false} error{/if}">
        <label>Kategorie</label>
        <div class="ui selection dropdown category">
            <input type="hidden" name="category" id="category">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
    <div class="field required{if $site['errors']['question'] !== false} error{/if}">
    <label>Frage</label>
        <div class="ui input">
            <input type="text" name="question" value="{$site['faq']->getQuestion()}">
        </div>
    </div>
    <div class="field required{if $site['errors']['text'] !== false} error{/if}">
        <label>Antwort</label>
        <textarea id="text" rows="10" name="text">{$site['faq']->getAnswer()}</textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {if $site['errors']['category'] !== false || $site['errors']['question'] !== false || $site['errors']['text'] !== false || $site['errors']['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $site['errors']['category'] !== false}
              <li>{$site['errors']['category']}</li>
            {/if}
            {if $site['errors']['question'] !== false}
              <li>{$site['errors']['question']}</li>
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
{include file="wysiwyg.tpl" template="false" selector="#text"}
<script>
$('.ui.selection.dropdown.category').dropdown({
    values: [
      {foreach from=$site['categorys'] item="category"}
        {
          {if $site['faq']->getCategory() == $category['id']}
            selected: true,
          {/if}
          name: "{$category['name']}",
          value: "{$category['id']}"
        },
      {/foreach}
    ],
});
</script>
{/if}