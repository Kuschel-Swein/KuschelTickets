<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 14:52:16
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin\errors.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b26005c1432_12799163',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2e78a9d9efc06b3eb01fad46609828f748bb7c69' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin\\errors.tpl',
      1 => 1586169858,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8b26005c1432_12799163 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
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
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['errors'], 'error');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
?>
    <tr id="errorentry<?php echo $_smarty_tpl->tpl_vars['error']->value['filename'];?>
">
    <td data-label="Fehler ID"><?php echo $_smarty_tpl->tpl_vars['error']->value['filename'];?>
</td>
    <td data-label="Datum"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['error']->value['date'],"%d.%m.%Y");?>
, <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['error']->value['date'],"%H:%M");?>
 Uhr</a></td>
    <td data-label="Aktion">
        <a href="javascript:deleteError('<?php echo $_smarty_tpl->tpl_vars['error']->value['filename'];?>
');" data-tooltip="Löschen"><i class="icon times"></i></a>
        <a href="javascript:watchError('<?php echo $_smarty_tpl->tpl_vars['error']->value['filename'];?>
')" data-tooltip="Ansehen"><i class="icon eye"></i></a>
    </td>
    </tr>
    <?php
}
} else {
?>
    <tr>
        <td colspan="3">
            <div class="ui info message">
                <ul class="list">
                    <li>Es sind noch keine Fehler aufgetreten.</li>
                </ul>
            </div>
        </td>
    </tr>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</tbody>
</table>
</div>
<?php echo '<script'; ?>
>
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
<?php echo '</script'; ?>
><?php }
}
