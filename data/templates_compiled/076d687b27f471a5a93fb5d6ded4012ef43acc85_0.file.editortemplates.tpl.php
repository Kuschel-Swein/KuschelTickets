<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 15:29:41
  from 'C:\xampp\htdocs\kuscheltickets\templates\editortemplates.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b2ec528fd81_63794621',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '076d687b27f471a5a93fb5d6ded4012ef43acc85' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\editortemplates.tpl',
      1 => 1586170042,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:wysiwyg.tpl' => 2,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b2ec528fd81_63794621 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Editorvorlagen"), 0, false);
if ($_smarty_tpl->tpl_vars['subpage']->value == "index") {?>
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
        <a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"editortemplates/add"),$_smarty_tpl);?>
">Editorvorlage erstellen</a>
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
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['__KT']->value['user']->getEditorTemplates(), 'editortemplate');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['editortemplate']->value) {
?>
        <tr id="templateentry<?php echo $_smarty_tpl->tpl_vars['editortemplate']->value['templateID'];?>
">
        <td data-label="ID"><?php echo $_smarty_tpl->tpl_vars['editortemplate']->value['templateID'];?>
</td>
        <td data-label="Titel"><?php echo $_smarty_tpl->tpl_vars['editortemplate']->value['title'];?>
</a></td>
        <td data-label="Beschreibung"><?php echo $_smarty_tpl->tpl_vars['editortemplate']->value['description'];?>
</td>
        <td data-label="Aktion">
            <a href="javascript:deleteTemplate(<?php echo $_smarty_tpl->tpl_vars['editortemplate']->value['templateID'];?>
);" data-tooltip="Löschen"><i class="icon times"></i></a>
            <a href="<?php echo smarty_function_link(array('url'=>"editortemplates/edit-".((string)$_smarty_tpl->tpl_vars['editortemplate']->value['templateID'])),$_smarty_tpl);?>
" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
            <a href="javascript:watchTemplate(<?php echo $_smarty_tpl->tpl_vars['editortemplate']->value['templateID'];?>
)" data-tooltip="Ansehen"><i class="icon eye"></i></a>
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
                        <li>Es wurden noch keine Editorvorlagen erstellt.</li>
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
    <?php echo '</script'; ?>
>
<?php } elseif ($_smarty_tpl->tpl_vars['subpage']->value == "add") {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"editortemplates"),$_smarty_tpl);?>
">Editorvorlagen Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false || $_smarty_tpl->tpl_vars['errors']->value['title'] !== false || $_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['success']->value !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"editortemplates/add"),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false) {?> error<?php }?>">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="title" value="<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['title']) && !$_smarty_tpl->tpl_vars['success']->value) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['title'];
}?>">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?> error<?php }?>">
    <label>Beschreibung</label>
        <div class="ui input">
            <input type="text" name="description" value="<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['description']) && !$_smarty_tpl->tpl_vars['success']->value) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['description'];
}?>">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false) {?> error<?php }?>">
      <label>Text</label>
      <textarea id="text" name="text"><?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['text']) && !$_smarty_tpl->tpl_vars['success']->value) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['text'];
}?></textarea>
    </div>
    <?php echo $_smarty_tpl->tpl_vars['recaptcha']->value;?>

    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false || $_smarty_tpl->tpl_vars['errors']->value['title'] !== false || $_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?>
        <div class="ui error message">
          <ul class="list">
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['title'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['text'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['token'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['token'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['description'];?>
</li>
            <?php }?>
          </ul>
        </div>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['success']->value !== false) {?>
        <div class="ui success message">
          <ul class="list">
            <li><?php echo $_smarty_tpl->tpl_vars['success']->value;?>
</li>
          </ul>
        </div>
    <?php }?>
</form>
<?php $_smarty_tpl->_subTemplateRender("file:wysiwyg.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text",'templates'=>"false"), 0, false);
} else {
$_prefixVariable1 = "edit";
$_smarty_tpl->_assignInScope('subpage', $_prefixVariable1);
if ($_prefixVariable1) {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"editortemplates"),$_smarty_tpl);?>
">Editorvorlagen Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false || $_smarty_tpl->tpl_vars['errors']->value['title'] !== false || $_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['success']->value !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"editortemplates/edit-".((string)$_smarty_tpl->tpl_vars['editortpl']->value['templateID'])),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false) {?> error<?php }?>">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="title" value="<?php echo $_smarty_tpl->tpl_vars['editortpl']->value['title'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?> error<?php }?>">
    <label>Beschreibung</label>
        <div class="ui input">
            <input type="text" name="description" value="<?php echo $_smarty_tpl->tpl_vars['editortpl']->value['description'];?>
">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false) {?> error<?php }?>">
      <label>Text</label>
      <textarea id="text" name="text"><?php echo $_smarty_tpl->tpl_vars['editortpl']->value['content'];?>
</textarea>
    </div>
    <?php echo $_smarty_tpl->tpl_vars['recaptcha']->value;?>

    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false || $_smarty_tpl->tpl_vars['errors']->value['title'] !== false || $_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?>
        <div class="ui error message">
          <ul class="list">
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['title'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['text'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['token'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['token'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['description'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['description'];?>
</li>
            <?php }?>
          </ul>
        </div>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['success']->value !== false) {?>
        <div class="ui success message">
          <ul class="list">
            <li><?php echo $_smarty_tpl->tpl_vars['success']->value;?>
</li>
          </ul>
        </div>
    <?php }?>
</form>
<?php $_smarty_tpl->_subTemplateRender("file:wysiwyg.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text",'templates'=>"false"), 0, true);
}}
$_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
