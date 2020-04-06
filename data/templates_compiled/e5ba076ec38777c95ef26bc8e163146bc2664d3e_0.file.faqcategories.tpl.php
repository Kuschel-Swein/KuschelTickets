<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 12:47:15
  from 'C:\xampp\htdocs\kuscheltickets\templates\admin\faqcategories.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b08b3a07721_00798125',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e5ba076ec38777c95ef26bc8e163146bc2664d3e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\admin\\faqcategories.tpl',
      1 => 1586169894,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8b08b3a07721_00798125 (Smarty_Internal_Template $_smarty_tpl) {
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
              <div class="item" data-value="2">FAQs</div>
              <div class="item" data-value="3">Kategorie</div>
            </div>
          </div>
        </div>
      </div>
      <div class="five wide column right floated">
        <br>
        <a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/faqcategories/add"),$_smarty_tpl);?>
">Kategorie erstellen</a>
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
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['site']->value['categories'], 'category');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['category']->value) {
?>
        <tr id="faqcategory<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
">
        <td data-label="ID"><?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
</td>
        <td data-label="Name"><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</a></td>
        <td data-label="FAQs"><?php echo $_smarty_tpl->tpl_vars['category']->value['faqs'];?>
</a></td>
        <td data-label="Aktion">
            <a href="javascript:deleteCategory(<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
);" data-tooltip="Löschen"><i class="icon times"></i></a>
            <a href="<?php echo smarty_function_link(array('url'=>"admin/faqcategories/edit-".((string)$_smarty_tpl->tpl_vars['category']->value['id'])),$_smarty_tpl);?>
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
                        <li>Es wurden noch keine Kategorien eingetragen.</li>
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
    <?php echo '</script'; ?>
>
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['site'] == "add") {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/faqcategories"),$_smarty_tpl);?>
">Kategorien Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"admin/faqcategories/add"),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?> error<?php }?>">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['text']) && !$_smarty_tpl->tpl_vars['site']->value['success']) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['text'];
}?>">
        </div>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
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
<?php } elseif ($_smarty_tpl->tpl_vars['site']->value['site'] == "edit") {?>
<a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"admin/faqcategories"),$_smarty_tpl);?>
">Kategorien Auflisten</a>
<br>
<br>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['site']->value['success'] !== false) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"admin/faqcategories/edit-".((string)$_smarty_tpl->tpl_vars['site']->value['id'])),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false) {?> error<?php }?>">
    <label>Name</label>
        <div class="ui input">
            <input type="text" name="text" value="<?php echo $_smarty_tpl->tpl_vars['site']->value['text'];?>
">
        </div>
    </div>
    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php if ($_smarty_tpl->tpl_vars['site']->value['errors']['text'] !== false || $_smarty_tpl->tpl_vars['site']->value['errors']['token'] !== false) {?>
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
<?php }
}
}
