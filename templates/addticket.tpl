{include file="header.tpl" title="Ticket erstellen"}
{if $__KT['user']->hasPermission("general.view.tickets.self")}
    <a class="ui blue button right floated" href="index.php?mytickets">Meine Tickets</a>
    <br>
    <br>
{/if}
{assign var="custominput" value="false"}
{foreach from=$errors['custominput'] item="error"}
  {if $error !== false}
    {assign var="custominput" value="true"}
  {/if}
{/foreach}
<form class="ui form{if $errors['title'] !== false || $errors['category'] !== false || $errors['text'] !== false || $errors['token'] !== false || $custominput == "true"} error{/if}{if $success == true} success{/if}" action="index.php?addticket" method="post">
    <div class="field required{if $errors['title'] !== false} error{/if}">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="{if isset($tpl['post']['title']) && !$success}{$tpl['post']['title']}{/if}">
        </div>
    </div>
    <div class="field required{if $errors['category'] !== false} error{/if}">
        <label>Kategorie</label>
        <div class="ui selection dropdown category">
            <input type="hidden" name="category" id="category">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
    </div>
    <br>
    {foreach from=$categorys item="category"}
      <div class="categoryfields" id="categoryfields_{$category->categoryID}" style="display: none">
        {foreach from=$category->getFormattedInputfields() item="input"}
          <div class="field{if $input['required']} required{/if}{if isset($errors['custominput'][$input['id']]) && $errors['custominput'][$input['id']] !== false} error{/if}">
            <label>{$input['title']}</label>
                {$input['field']}
                <small class="helper">{$input['description']}</small>
          </div>
        {/foreach}
        <br>
      </div>
    {/foreach}
    <div class="field required{if $errors['text'] !== false} error{/if}">
      <label>Antwort</label>
      <textarea id="text" rows="10" name="text">{if isset($tpl['post']['text']) && !$success}{$tpl['post']['text']}{/if}</textarea>
    </div>
    {$recaptcha}
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
    {foreach from=$categorys item="category"}
      {if $custominput == "true"}
        <div class="ui error message">
          <ul class="list">
            {foreach from=$category->getFormattedInputfields() item="input"}
              {if isset($errors['custominput'][$input['id']]) && $errors['custominput'][$input['id']] !== false}
                {if $errors['custominput'][$input['id']] !== false}
                  <li>{$errors['custominput'][$input['id']]}</li>
                {/if}
              {/if}
            {/foreach}
          </ul>
        </div>
      {/if}
    {/foreach}
    {if $errors['title'] !== false || $errors['category'] !== false || $errors['text'] !== false || $errors['token'] !== false}
        <div class="ui error message">
          <ul class="list">
            {if $errors['title'] !== false}
              <li>{$errors['title']}</li>
            {/if}
            {if $errors['category'] !== false}
              <li>{$errors['category']}</li>
            {/if}
            {if $errors['text'] !== false}
              <li>{$errors['text']}</li>
            {/if}
            {if $errors['token'] !== false}
              <li>{$errors['token']}</li>
            {/if}
          </ul>
        </div>
    {/if}
    {if $success == true}
        <div class="ui success message">
            <ul class="list">
                <li>Dein Ticket wurde erstellt, du wirst automatisch weitergeleitet.</li>
            </ul>
        </div>
    {/if}
</form>
<script>
$('.ui.selection.dropdown.custom').dropdown();
$('.ui.selection.dropdown.category').dropdown({
    values: [
      {foreach from=$categorys item="category"}
        {
        {if isset($tpl['post']['category']) && !$success}
          {if $tpl['post']['category'] == $category->categoryID}
            selected: true,
          {/if}
        {/if}
          name: "{$category->getName()}",
          value: "{$category->categoryID}"
        },
      {/foreach}
    ],
  });
$('.ui.dropdown.category').dropdown('setting', 'onChange', function(value, text, $choice) {
  var elems = document.getElementsByClassName("categoryfields");
  for(var i = 0; i < elems.length; i++) {
    elems[i].style.display = "none";
  }
  document.getElementById("categoryfields_" + value).style.display = "block";
});
var value = document.getElementById("category").value;
var elems = document.getElementsByClassName("categoryfields");
for(var i = 0; i < elems.length; i++) {
  elems[i].style.display = "none";
}
var elem = document.getElementById("categoryfields_" + value);
if(elem) {
  elem.style.display = "block";
}
</script>
{include file="wysiwyg.tpl" selector="#text"}
{include file="footer.tpl"}