<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 12:17:43
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin\groups.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b01c7a3ed86_99511864',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '400bc0628d8bd9b492896869fa449d276fb03d63' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin\\groups.tpl',
      1 => 1586166721,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8b01c7a3ed86_99511864 (Smarty_Internal_Template $_smarty_tpl) {
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
              <div class="item" data-value="1">Name</div>
              <div class="item" data-value="2">Badge</div>
            </div>
          </div>
        </div>
      </div>
      <div class="five wide column right floated">
        <br>
        <a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/groups/add"),$_smarty_tpl);?>
">Gruppe erstellen</a>
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
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['groups'], 'group');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['group']->value) {
?>
        <tr id="groupentry<?php echo $_smarty_tpl->tpl_vars['group']->value->groupID;?>
">
        <td data-label="ID"><?php echo $_smarty_tpl->tpl_vars['group']->value->groupID;?>
</td>
        <td data-label="Name"><?php echo $_smarty_tpl->tpl_vars['group']->value->getGroupname();?>
</a></td>
        <td data-label="Badge"><?php echo $_smarty_tpl->tpl_vars['group']->value->getGroupBadge();?>
</a></td>
        <td data-label="System"><?php if ($_smarty_tpl->tpl_vars['group']->value->isSystem()) {?><span data-tooltip="Ja"><i class="icon check"></i></span><?php } else { ?><span data-tooltip="Nein"><i class="icon times"></i></span><?php }?></a></td>
        <td data-label="Aktion">
          <?php if (!$_smarty_tpl->tpl_vars['group']->value->isSystem()) {?>
            <a href="javascript:deleteGroup(<?php echo $_smarty_tpl->tpl_vars['group']->value->groupID;?>
);" data-tooltip="Löschen"><i class="icon times"></i></a>
          <?php }?>
            <a href="<?php echo smarty_function_link(array('url'=>"admin/groups/edit-".((string)$_smarty_tpl->tpl_vars['group']->value->groupID)),$_smarty_tpl);?>
" data-tooltip="Bearbeiten"><i class="icon pencil"></i></a>
        </td>
        </tr>
        <?php
}
} else {
?>
        <tr>
            <td colspan="5">
                <div class="ui info message">
                    <ul class="list">
                        <li>Es wurden noch keine Gruppen erstellt, dieser Fehler sollte nicht auftreten.</li>
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
        function deleteGroup(id) {
            modal.confirm("Möchtest du diese Gruppe wirklich löschen. Dies kann nicht rückgängig gemacht werden.<br><b>Beachte:</b> alle Nutzer welche diese Gruppe gesetzt haben, werden in die Standartgruppe verschoben.", function() {
                var data = ajax.call(11, id);
                if(data['success'] !== undefined) {
                    toast.create(data['title'], data['message'], "success");
                    $("#groupentry" + id).fadeOut();
                } else {
                    toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                }
            });
        }
        $('.ui.selection.dropdown').dropdown();
    <?php echo '</script'; ?>
>
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['site'] == "add") {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/groups"),$_smarty_tpl);?>
">Gruppen Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"admin/groups/add"),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?> error<?php }?>">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" onchange="preview(this)" onkeyup="preview(this)" value="<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['text']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['text'];
}?>">
        </div>
    </div>
    <div class="field<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?> error<?php }?>">
      <label>Badge</label>
    </div>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['colors'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>
    <div class="field">
      <div class="ui radio checkbox">
        <input type="radio" value="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
" name="badge"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['badge']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {
if ($_smarty_tpl->tpl_vars['tpl']->value['post']['badge'] == $_smarty_tpl->tpl_vars['color']->value) {?> checked<?php }
}?>>
        <label><div class="ui <?php echo $_smarty_tpl->tpl_vars['color']->value;?>
 label preview">Badge</div></label>
      </div>
    </div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
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
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['permissions'], 'permission');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['permission']->value) {
?>
        <?php if (strpos($_smarty_tpl->tpl_vars['permission']->value['name'],'general_') === 0) {?>
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['permission']->value['name'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post'][$_smarty_tpl->tpl_vars['permission']->value['name']]) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
              <label><?php echo $_smarty_tpl->tpl_vars['permission']->value['display'];?>
</label>
            </div>
          </div>
        <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <div class="ui tab" data-tab="second">
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['permissions'], 'permission');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['permission']->value) {
?>
        <?php if (strpos($_smarty_tpl->tpl_vars['permission']->value['name'],'mod_') === 0) {?>
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['permission']->value['name'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post'][$_smarty_tpl->tpl_vars['permission']->value['name']]) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
              <label><?php echo $_smarty_tpl->tpl_vars['permission']->value['display'];?>
</label>
            </div>
          </div>
        <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <div class="ui tab" data-tab="third">
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['permissions'], 'permission');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['permission']->value) {
?>
        <?php if (strpos($_smarty_tpl->tpl_vars['permission']->value['name'],'admin_') === 0) {?>
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['permission']->value['name'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post'][$_smarty_tpl->tpl_vars['permission']->value['name']]) && !$_smarty_tpl->tpl_vars['site']->value['success']) {?> checked<?php }?>>
              <label><?php echo $_smarty_tpl->tpl_vars['permission']->value['display'];?>
</label>
            </div>
          </div>
        <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <br>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?>
        <div class="ui error message">
          <ul class="list">
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['text'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['token'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['badge'];?>
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
<?php echo '<script'; ?>
>
$('.menu .item').tab();
function preview(elem) {
  var value = elem.value;
  if(value == "") {
    value = "Bage";
  }
  value = value.replace(/</, "&lt;");
  value = value.replace(/>/, "&gt;");
  var elems = document.getElementsByClassName("preview");
  for(var i = 0; i < elems.length; i++) {
    elems[i].innerHTML = value;
  }
}
<?php echo '</script'; ?>
>
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['site'] == "edit") {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/groups"),$_smarty_tpl);?>
">Gruppen Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"admin/groups/edit-".((string)$_smarty_tpl->tpl_vars['site']->value['editgroup']->groupID)),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?> error<?php }?>">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" onchange="preview(this)" onkeyup="preview(this)" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['editgroup']->getGroupname();?>
">
        </div>
    </div>
    <div class="field<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?> error<?php }?>">
      <label>Badge</label>
    </div>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['colors'], 'color');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
?>
    <div class="field">
      <div class="ui radio checkbox">
        <input type="radio" value="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
" name="badge"<?php if ($_smarty_tpl->tpl_vars['site']->value['badge'] == $_smarty_tpl->tpl_vars['color']->value) {?> checked<?php }?>>
        <label><div class="ui <?php echo $_smarty_tpl->tpl_vars['color']->value;?>
 label preview"><?php echo $_smarty_tpl->tpl_vars['site']->value['editgroup']->getGroupname();?>
</div></label>
      </div>
    </div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php if ($_smarty_tpl->tpl_vars['site']->value['editgroup']->groupID !== 1) {?>
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
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['permissions'], 'permission');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['permission']->value) {
?>
        <?php if (strpos($_smarty_tpl->tpl_vars['permission']->value['name'],'general_') === 0) {?>
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['permission']->value['name'];?>
"<?php if ($_smarty_tpl->tpl_vars['site']->value['gpermissions'][$_smarty_tpl->tpl_vars['permission']->value['name']] == "1") {?> checked<?php }?>>
              <label><?php echo $_smarty_tpl->tpl_vars['permission']->value['display'];?>
</label>
            </div>
          </div>
        <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <div class="ui tab" data-tab="second">
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['permissions'], 'permission');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['permission']->value) {
?>
        <?php if (strpos($_smarty_tpl->tpl_vars['permission']->value['name'],'mod_') === 0) {?>
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['permission']->value['name'];?>
"<?php if ($_smarty_tpl->tpl_vars['site']->value['gpermissions'][$_smarty_tpl->tpl_vars['permission']->value['name']] == "1") {?> checked<?php }?>>
              <label><?php echo $_smarty_tpl->tpl_vars['permission']->value['display'];?>
</label>
            </div>
          </div>
        <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <div class="ui tab" data-tab="third">
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['permissions'], 'permission');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['permission']->value) {
?>
        <?php if (strpos($_smarty_tpl->tpl_vars['permission']->value['name'],'admin_') === 0) {?>
          <div class="field">
            <div class="ui checkbox">
              <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['permission']->value['name'];?>
"<?php if ($_smarty_tpl->tpl_vars['site']->value['gpermissions'][$_smarty_tpl->tpl_vars['permission']->value['name']] == "1") {?> checked<?php }?>>
              <label><?php echo $_smarty_tpl->tpl_vars['permission']->value['display'];?>
</label>
            </div>
          </div>
        <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
      <br>
    <?php } else { ?>
      <div class="ui warning message display-block">
        <ul class="list">
          <li>Aus Sicherheitsgründen kannst du die Berechtigungen der System Administrator Gruppe nicht verändern.</li>
        </ul>
      </div>
    <?php }?>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?>
        <div class="ui error message">
          <ul class="list">
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['text'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['token'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['badge'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['site']->value['errors']['badge'];?>
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
<?php echo '<script'; ?>
>
$('.menu .item').tab();
function preview(elem) {
  var value = elem.value;
  if(value == "") {
    value = "Bage";
  }
  value = value.replace(/</, "&lt;");
  value = value.replace(/>/, "&gt;");
  var elems = document.getElementsByClassName("preview");
  for(var i = 0; i < elems.length; i++) {
    elems[i].innerHTML = value;
  }
}
<?php echo '</script'; ?>
>
<?php }
}
}
