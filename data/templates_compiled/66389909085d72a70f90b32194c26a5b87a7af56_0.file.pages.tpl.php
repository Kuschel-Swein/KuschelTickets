<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 12:33:24
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin\pages.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b05749d08a7_95674936',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '66389909085d72a70f90b32194c26a5b87a7af56' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin\\pages.tpl',
      1 => 1586169203,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:codeeditor_html.tpl' => 2,
    'file:codeeditor_smarty.tpl' => 2,
    'file:wysiwyg.tpl' => 2,
  ),
),false)) {
function content_5e8b05749d08a7_95674936 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
if ($_smarty_tpl->tpl_vars['site']->value['site'] == "index") {?>
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
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['pages'], 'page');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['page']->value) {
?>
        <tr id="pageentry<?php echo $_smarty_tpl->tpl_vars['page']->value['pageID'];?>
">
        <td data-label="ID"><?php echo $_smarty_tpl->tpl_vars['page']->value['pageID'];?>
</td>
        <td data-label="Titel"><?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
</td>
        <td data-label="Typ"><?php if ($_smarty_tpl->tpl_vars['page']->value['type'] == "1") {?>HTML<?php } elseif ($_smarty_tpl->tpl_vars['page']->value['type'] == "2") {?>Template<?php } else { ?>WYSIWYG<?php }?></td>
        <td data-label="URL"><a href="<?php echo smarty_function_link(array('url'=>"page/".((string)$_smarty_tpl->tpl_vars['page']->value['url'])),$_smarty_tpl);?>
" target="_blank"><?php echo smarty_function_link(array('url'=>"page/".((string)$_smarty_tpl->tpl_vars['page']->value['url'])),$_smarty_tpl);?>
</a></td>
        <td data-label="Aktion">
          <?php if ($_smarty_tpl->tpl_vars['page']->value['system'] !== "1") {?>
            <a href="javascript:deletePage(<?php echo $_smarty_tpl->tpl_vars['page']->value['pageID'];?>
);" data-tooltip="Löschen"><i class="icon times"></i></a>
          <?php }?>
            <a href="<?php echo smarty_function_link(array('url'=>"admin/pages/edit-".((string)$_smarty_tpl->tpl_vars['page']->value['pageID'])),$_smarty_tpl);?>
" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        <?php
}
} else {
?>
        <tr>
            <td colspan="4">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Seiten erstellt.</li>
                    </ul>
                </div>
            </td>
        </tr>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </tbody>
    </table>
    <?php echo '<script'; ?>
>
        function deletePage(id) {
            modal.confirm("Möchtest du diese Seite wirklich löschen. Dies kann nicht rückgängig gemacht werden.", function() {
                var data = ajax.call(9, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#pageentry" + id).fadeOut();
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
            var links = ["<?php echo smarty_function_link(array('url'=>"admin/pages/add-0"),$_smarty_tpl);?>
", "<?php echo smarty_function_link(array('url'=>"admin/pages/add-1"),$_smarty_tpl);?>
", "<?php echo smarty_function_link(array('url'=>"admin/pages/add-2"),$_smarty_tpl);?>
"];
            window.location.href = links[value];
          });
        }

        $('.ui.selection.dropdown').dropdown();
    <?php echo '</script'; ?>
>
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['site'] == "add") {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/pages"),$_smarty_tpl);?>
">Seiten Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['url'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"admin/pages/add-".((string)$_smarty_tpl->tpl_vars['site']->value['type'])),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false) {?> error<?php }?>">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['title']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['title'];
}?>">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['url'] !== false) {?> error<?php }?>">
    <label>URL</label>
        <div class="ui input">
            <input type="text" name="url" value="<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['url']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['url'];
}?>">
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
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?> error<?php }?>">
        <label>Inhalt <a href="javascript:variablesInfo()"><i class="icon question"></i></a></label>
        <textarea id="text" rows="10" name="text"><?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['text']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['text'];
}?></textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['url'] !== false) {?>
        <div class="ui error message">
          <ul class="list">
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['title'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['text'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['token'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['url'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['url'];?>
</li>
            <?php }?>
          </ul>
        </div>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?>
        <div class="ui success message">
          <ul class="list">
            <li><?php echo $_smarty_tpl->tpl_vars['site']->value['success'];?>
</li>
          </ul>
        </div>
    <?php }?>
</form>

<?php if ($_smarty_tpl->tpl_vars['site']->value['type'] == "1") {?>
  <?php $_smarty_tpl->_subTemplateRender("file:codeeditor_html.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text"), 0, false);
} elseif ($_smarty_tpl->tpl_vars['site']->value['type'] == "2") {?>
  <?php $_smarty_tpl->_subTemplateRender("file:codeeditor_smarty.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text"), 0, false);
} else { ?>
  <?php $_smarty_tpl->_subTemplateRender("file:wysiwyg.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('template'=>"false",'selector'=>"#text"), 0, false);
}
echo '<script'; ?>
>
$('.ui.selection.dropdown.groups').dropdown({
    values: [
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['allgroups'], 'group');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
?>
        {
        <?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['groupsaccess']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?>
          <?php if ((String) in_array($_smarty_tpl->tpl_vars['group']->value->groupID,$_smarty_tpl->tpl_vars['site']->value['selectedgroups'])) {?>
            selected: true,
          <?php }?>
        <?php }?>
          name: '<?php echo $_smarty_tpl->tpl_vars['group']->value->getGroupBadge();?>
',
          value: "<?php echo $_smarty_tpl->tpl_vars['group']->value->groupID;?>
"
        },
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    ]
});
<?php if ($_smarty_tpl->tpl_vars['site']->value['type'] !== "1" && $_smarty_tpl->tpl_vars['site']->value['type'] !== "2") {?>
  
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
  
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['type'] == "1") {?>
  
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
  
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['type'] == "2") {?>
  
  function variablesInfo() {
    modal.modal("Information", '' +
    '<p>Im Template Modus kannst du mit Template-Scripting deine Seite erstellen. Du verwendest hier die Scripting Sprache <a href="https://www.smarty.net/docs/en/">Smarty</a> in der Version 3.</p>' +
    '<p>Du kannst die <i>$__KT</i> Variable verwenden, diese beinhaltet die gesamte Konfirgurationsdatei sowie weitere wichtige Hinweise. Die Variable <i>$tpl</i> beinhaltet Variablen wie <i>$_COOKIE</i> oder <i>$_POST</i></p>' +
    '');
  }
  
<?php }
echo '</script'; ?>
>
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['site'] == "edit") {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/pages"),$_smarty_tpl);?>
">Seiten Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"admin/pages/edit-".((string)$_smarty_tpl->tpl_vars['site']->value['page']['pageID'])),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false) {?> error<?php }?>">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['page']['title'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['url'] !== false) {?> error<?php }?>">
    <label>URL</label>
        <div class="ui input disabled">
            <input type="text" <?php if ($_smarty_tpl->tpl_vars['site']->value['page']['system'] == "1") {?>readonly<?php }?> name="url" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['page']['url'];?>
">
        </div>
    </div>
    <div class="field">
        <label>Zugriff</label>
        <div class="ui multiple selection dropdown groups<?php if ($_smarty_tpl->tpl_vars['site']->value['page']['system'] == "1") {?> disabled<?php }?>">
            <input type="hidden" name="groupsaccess">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
            </div>
        </div>
        <small class="helper">Wähle hier alle Gruppen aus welche Zugriff auf diese Seite haben sollen, wähle keine Gruppe aus, wenn alle zugriff auf diese Seite haben sollen (auch Gäste).</small>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?> error<?php }?>">
        <label>Inhalt <a href="javascript:variablesInfo()"><i class="icon question"></i></a></label>
        <textarea id="text" rows="10" name="text"><?php echo $_smarty_tpl->tpl_vars['site']->value['page']['content'];?>
</textarea>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
        <div class="ui error message">
          <ul class="list">
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['title'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['title'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['text'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['token'];?>
</li>
            <?php }?>
          </ul>
        </div>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?>
        <div class="ui success message">
          <ul class="list">
            <li><?php echo $_smarty_tpl->tpl_vars['site']->value['success'];?>
</li>
          </ul>
        </div>
    <?php }?>
</form>
<?php if ($_smarty_tpl->tpl_vars['site']->value['page']['type'] == "1") {?>
  <?php $_smarty_tpl->_subTemplateRender("file:codeeditor_html.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text"), 0, true);
} elseif ($_smarty_tpl->tpl_vars['site']->value['page']['type'] == "2") {?>
  <?php $_smarty_tpl->_subTemplateRender("file:codeeditor_smarty.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text"), 0, true);
} else { ?>
  <?php $_smarty_tpl->_subTemplateRender("file:wysiwyg.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('template'=>"false",'selector'=>"#text"), 0, true);
}
echo '<script'; ?>
>
$('.ui.selection.dropdown.groups').dropdown({
    values: [
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['allgroups'], 'group');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
?>
        {
          <?php if ((String) in_array($_smarty_tpl->tpl_vars['group']->value->groupID,$_smarty_tpl->tpl_vars['site']->value['selectedgroups'])) {?>
            selected: true,
          <?php }?>
          name: '<?php echo $_smarty_tpl->tpl_vars['group']->value->getGroupBadge();?>
',
          value: "<?php echo $_smarty_tpl->tpl_vars['group']->value->groupID;?>
"
        },
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    ],
});
<?php if ($_smarty_tpl->tpl_vars['site']->value['page']['type'] !== "1" && $_smarty_tpl->tpl_vars['site']->value['page']['type'] !== "2") {?>
  
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
  
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['page']['type'] == "1") {?>
  
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
  
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['page']['type'] == "2") {?>
  
  function variablesInfo() {
    modal.modal("Information", '' +
    '<p>Im Template Modus kannst du mit Template-Scripting deine Seite erstellen. Du verwendest hier die Scripting Sprache <a href="https://www.smarty.net/docs/en/">Smarty</a> in der Version 3.</p>' +
    '<p>Du kannst die <i>$__KT</i> Variable verwenden, diese beinhaltet die gesamte Konfirgurationsdatei sowie weitere wichtige Hinweise. Die Variable <i>$tpl</i> beinhaltet Variablen wie <i>$_COOKIE</i> oder <i>$_POST</i></p>' +
    '');
  }
  
<?php }
echo '</script'; ?>
>
<?php }
}
}
