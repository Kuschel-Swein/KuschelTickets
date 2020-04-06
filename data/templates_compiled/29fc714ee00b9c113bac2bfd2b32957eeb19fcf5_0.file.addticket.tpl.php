<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 15:30:45
  from 'C:\xampp\htdocs\kuscheltickets\templates\addticket.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b2f05b23ad9_02306982',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '29fc714ee00b9c113bac2bfd2b32957eeb19fcf5' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\addticket.tpl',
      1 => 1586096037,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:wysiwyg.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5e8b2f05b23ad9_02306982 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Ticket erstellen"), 0, false);
if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.view.tickets.self")) {?>
    <a class="ui blue button right floated" href="<?php echo smarty_function_link(array('url'=>"mytickets"),$_smarty_tpl);?>
">Meine Tickets</a>
    <br>
    <br>
<?php }
$_smarty_tpl->_assignInScope('custominput', "false");
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['errors']->value['custominput'], 'error');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
?>
  <?php if ($_smarty_tpl->tpl_vars['error']->value !== false) {?>
    <?php $_smarty_tpl->_assignInScope('custominput', "true");?>
  <?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
<form class="ui form<?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false || $_smarty_tpl->tpl_vars['errors']->value['category'] !== false || $_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false || $_smarty_tpl->tpl_vars['custominput']->value == "true") {?> error<?php }
if ($_smarty_tpl->tpl_vars['success']->value == true) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"addticket"),$_smarty_tpl);?>
" method="post">
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false) {?> error<?php }?>">
    <label>Titel</label>
        <div class="ui input">
            <input type="text" name="title" value="<?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['title']) && !$_smarty_tpl->tpl_vars['success']->value) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['title'];
}?>">
        </div>
    </div>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['category'] !== false) {?> error<?php }?>">
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
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['categorys']->value, 'category');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['category']->value) {
?>
      <div class="categoryfields" id="categoryfields_<?php echo $_smarty_tpl->tpl_vars['category']->value->categoryID;?>
" style="display: none">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category']->value->getFormattedInputfields(), 'input');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['input']->value) {
?>
          <div class="field<?php if ($_smarty_tpl->tpl_vars['input']->value['required']) {?> required<?php }
if (isset($_smarty_tpl->tpl_vars['errors']->value['custominput'][$_smarty_tpl->tpl_vars['input']->value['id']]) && $_smarty_tpl->tpl_vars['errors']->value['custominput'][$_smarty_tpl->tpl_vars['input']->value['id']] !== false) {?> error<?php }?>">
            <label><?php echo $_smarty_tpl->tpl_vars['input']->value['title'];?>
</label>
                <?php echo $_smarty_tpl->tpl_vars['input']->value['field'];?>

                <small class="helper"><?php echo $_smarty_tpl->tpl_vars['input']->value['description'];?>
</small>
          </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <br>
      </div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <div class="field required<?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false) {?> error<?php }?>">
      <label>Antwort</label>
      <textarea id="text" rows="10" name="text"><?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['text']) && !$_smarty_tpl->tpl_vars['success']->value) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['text'];
}?></textarea>
    </div>
    <?php echo $_smarty_tpl->tpl_vars['recaptcha']->value;?>

    <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
    <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['categorys']->value, 'category');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['category']->value) {
?>
      <?php if ($_smarty_tpl->tpl_vars['custominput']->value == "true") {?>
        <?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['category']) && !$_smarty_tpl->tpl_vars['success']->value) {?>
          <?php if ($_smarty_tpl->tpl_vars['tpl']->value['post']['category'] == $_smarty_tpl->tpl_vars['category']->value->categoryID) {?>
            <div class="ui error message">
              <ul class="list">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category']->value->getFormattedInputfields(), 'input');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['input']->value) {
?>
                  <?php if (isset($_smarty_tpl->tpl_vars['errors']->value['custominput'][$_smarty_tpl->tpl_vars['input']->value['id']]) && $_smarty_tpl->tpl_vars['errors']->value['custominput'][$_smarty_tpl->tpl_vars['input']->value['id']] !== false) {?>
                    <?php if ($_smarty_tpl->tpl_vars['errors']->value['custominput'][$_smarty_tpl->tpl_vars['input']->value['id']] !== false) {?>
                      <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['custominput'][$_smarty_tpl->tpl_vars['input']->value['id']];?>
</li>
                    <?php }?>
                  <?php }?>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              </ul>
            </div>
          <?php }?>
        <?php }?>
      <?php }?>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false || $_smarty_tpl->tpl_vars['errors']->value['category'] !== false || $_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false) {?>
        <div class="ui error message">
          <ul class="list">
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['title'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['title'];?>
</li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['errors']->value['category'] !== false) {?>
              <li><?php echo $_smarty_tpl->tpl_vars['errors']->value['category'];?>
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
          </ul>
        </div>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['success']->value == true) {?>
        <div class="ui success message">
            <ul class="list">
                <li>Dein Ticket wurde erstellt, du wirst automatisch weitergeleitet.</li>
            </ul>
        </div>
    <?php }?>
</form>
<?php echo '<script'; ?>
>
$('.ui.selection.dropdown.custom').dropdown();
$('.ui.selection.dropdown.category').dropdown({
    values: [
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['categorys']->value, 'category');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['category']->value) {
?>
        {
        <?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['category']) && !$_smarty_tpl->tpl_vars['success']->value) {?>
          <?php if ($_smarty_tpl->tpl_vars['tpl']->value['post']['category'] == $_smarty_tpl->tpl_vars['category']->value->categoryID) {?>
            selected: true,
          <?php }?>
        <?php }?>
          name: "<span class='ui label <?php echo $_smarty_tpl->tpl_vars['category']->value->getColor();?>
'><?php echo $_smarty_tpl->tpl_vars['category']->value->getName();?>
</span>",
          value: "<?php echo $_smarty_tpl->tpl_vars['category']->value->categoryID;?>
"
        },
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
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
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:wysiwyg.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text"), 0, false);
$_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
