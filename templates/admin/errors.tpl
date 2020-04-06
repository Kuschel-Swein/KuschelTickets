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
          <div class="item" data-value="0">Fehler ID</div>
          <div class="item" data-value="1">Datum</div>
        </div>
      </div>
    </div>
  </div>
  <div class="five wide column right floated">
  </div>
</div>
<br>
<br>
<div class="overflow-x-auto">
<table class="ui celled table">
<thead>
    <tr>
        <th>Fehler ID</th>
        <th>Datum</th>
        <th>Aktion</th>
    </tr>
</thead>
<tbody id="search_list">
    {foreach from=$site['errors'] item="error"}
    <tr id="errorentry{$error['filename']}">
    <td data-label="Fehler ID">{$error['filename']}</td>
    <td data-label="Datum">{$error['date']|date_format:"%d.%m.%Y"}, {$error['date']|date_format:"%H:%M"} Uhr</a></td>
    <td data-label="Aktion">
        <a href="javascript:deleteError('{$error['filename']}');" data-tooltip="Löschen"><i class="icon times"></i></a>
        <a href="javascript:watchError('{$error['filename']}')" data-tooltip="Ansehen"><i class="icon eye"></i></a>
    </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="3">
            <div class="ui info message">
                <ul class="list">
                    <li>Es sind noch keine Fehler aufgetreten.</li>
                </ul>
            </div>
        </td>
    </tr>
    {/foreach}
</tbody>
</table>
</div>
<script>
    function deleteError(id) {
        modal.confirm("Möchtest du dieses Protokoll wirklich löschen? Dies kann nicht rückgängig gemacht werden.", function() {
            var data = ajax.call(16, id);
            if(data['success'] !== undefined) {
              toast.create(data['title'], data['message'], "success");
              $("#errorentry" + id).fadeOut(function() {
                var elems = document.getElementById("search_list").getElementsByTagName("tr");
                var found = 0;
                for(var i = 0; i < elems.length; i++) {
                  if(elems[i].style.display !== "none") {
                    found++;
                  }
                }
                if(found == 0) {
                  document.getElementById("search_list").innerHTML = '<tr><td colspan="3"><div class="ui info message"><ul class="list"><li>Es sind noch keine Fehler aufgetreten.</li></ul></div></td></tr>';
                }
              });
            } else {
              toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
            }
        });
    }
    function watchError(id) {
      var content = ajax.call(15, id);
      content = content['message'];
      modal.modal("Fehler Protokoll ansehen", "<pre class='overflow-x-auto'>" + content + "</pre>");
    }
    $('.ui.selection.dropdown').dropdown();
</script>