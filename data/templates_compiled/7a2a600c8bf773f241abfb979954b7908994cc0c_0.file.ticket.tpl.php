<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 15:28:39
  from 'C:\xampp\htdocs\kuscheltickets\templates\ticket.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b2e87acfe55_45110356',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7a2a600c8bf773f241abfb979954b7908994cc0c' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\ticket.tpl',
      1 => 1586179717,
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
function content_5e8b2e87acfe55_45110356 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Ticket"), 0, false);
?>
<div class="column">
    <div class="ui middle attached segment">
        <div class="ui <?php echo $_smarty_tpl->tpl_vars['ticket']->value->getColor();?>
 ribbon label"><?php echo $_smarty_tpl->tpl_vars['ticket']->value->getCategory();?>
</div>
        <?php echo $_smarty_tpl->tpl_vars['ticket']->value->getTitle();?>

        <?php if (($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.close") || ($_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user'] && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.close.own"))) || ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.done") || ($_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user'] && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.done.own"))) || ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.reopen") || ($_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user'] && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.reopen.own")))) {?>
            <div class="ui dropdown top right pointing settings float-right">
                <div class="text" data-tooltip="Einstellungen"><i class="cogs icon"></i></div>
                <div class="menu">
                    <?php if ($_smarty_tpl->tpl_vars['ticket']->value->getState() == 1) {?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.close") || ($_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user'] && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.close.own"))) {?>
                            <div class="item" data-value="1">Ticket schließen</div>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.done") || ($_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user'] && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.done.own"))) {?>
                            <div class="item" data-value="3">Ticket als erledigt markieren</div>
                        <?php }?>
                    <?php } else { ?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.reopen") || ($_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user'] && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.reopen.own"))) {?>
                            <div class="item" data-value="2">Ticket erneut öffnen</div>
                        <?php }?>
                    <?php }?>
                </div>
            </div>
        <?php }?>
    </div>
</div>
<br>
<div class="column">
    <h4 class="ui top attached block header">
        <div class="ui left aligned grid container">
            <div class="seven wide column floated left">
                <?php echo $_smarty_tpl->tpl_vars['ticket']->value->getCreator()->getGroup()->getGroupBadge();?>
 <?php echo $_smarty_tpl->tpl_vars['ticket']->value->getCreator()->getUserName();?>

            </div>
            <div class="six wide column right aligned">
                <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ticket']->value->getTime(),"%d.%m.%Y");?>
, <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ticket']->value->getTime(),"%H:%M");?>
 Uhr
            </div>
            <div class="column right aligned">
                <?php if ((($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.delete.own") && $_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user']->userID) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.delete")) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.quote")) {?>
                    <div class="ui right aligned grid mobile only">
                        <div class="column right aligned">
                            <div class="ui small icon buttons">
                                <div class="ui top right pointing dropdown button answeractions">
                                    <i class="settings icon"></i>
                                    <div class="menu">
                                        <?php if (($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.delete.own") && $_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user']->userID) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.delete")) {?>
                                            <div class="item deletebutton" data-id="<?php echo $_smarty_tpl->tpl_vars['ticket']->value->ticketID;?>
" data-type="6">Löschen</div>
                                        <?php }?>
                                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.quote")) {?>
                                            <div class="item quotebutton" data-username="<?php echo $_smarty_tpl->tpl_vars['ticket']->value->getCreator()->getUserName();?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['ticket']->value->ticketID;?>
" data-type="ticket">Zitieren</div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </h4>
    <div class="ui middle attached clearing segment">
        <span id="ticketcontent<?php echo $_smarty_tpl->tpl_vars['ticket']->value->ticketID;?>
">
            <?php echo $_smarty_tpl->tpl_vars['ticket']->value->getContent();?>

        </span>
        <br>
        <div class="ui computer and tablet only grid">
            <div class="column right aligned">
                <div class="ui small icon buttons">
                    <?php if (($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.delete.own") && $_smarty_tpl->tpl_vars['ticket']->value->getCreator()->userID == $_smarty_tpl->tpl_vars['__KT']->value['user']->userID) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("mod.tickets.delete")) {?>
                        <button class="ui button deletebutton" data-tooltip="Löschen" data-id="<?php echo $_smarty_tpl->tpl_vars['ticket']->value->ticketID;?>
" data-type="6"><i class="icon trash"></i></button>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.quote")) {?>
                        <button class="ui button quotebutton" data-username="<?php echo $_smarty_tpl->tpl_vars['ticket']->value->getCreator()->getUserName();?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['ticket']->value->ticketID;?>
" data-type="ticket" data-tooltip="Zitieren"><i class="icon quote left"></i></button>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ticket']->value->getAnswers(), 'answer');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['answer']->value) {
if ($_smarty_tpl->tpl_vars['answer']->value['creator'] == "system") {?>
    <div class="column ticketanswer">
        <div class="ui tablet computer only grid">
            <div class="column">
                <h4 class="ui horizontal divider header" >
                    <?php echo $_smarty_tpl->tpl_vars['answer']->value['content'];?>
 - <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['answer']->value['time'],"%d.%m.%Y");?>
, <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['answer']->value['time'],"%H:%M");?>
 Uhr
                </h4>
            </div>
        </div>
        <div class="ui mobile only grid">
            <div class="column">
                <div class="ui bottom center aligned attached segment">
                    <?php echo $_smarty_tpl->tpl_vars['answer']->value['content'];?>
 - <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['answer']->value['time'],"%d.%m.%Y");?>
, <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['answer']->value['time'],"%H:%M");?>
 Uhr
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="column ticketanswer" id="ticketanswer<?php echo $_smarty_tpl->tpl_vars['answer']->value['id'];?>
">
        <h4 class="ui top attached block header">
            <div class="ui left aligned grid container">
                <div class="seven wide column floated left">
                    <?php echo $_smarty_tpl->tpl_vars['answer']->value['creator']->getGroup()->getGroupBadge();?>
 <?php echo $_smarty_tpl->tpl_vars['answer']->value['creator']->getUserName();?>

                </div>
                <div class="six wide column right aligned">
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['answer']->value['time'],"%d.%m.%Y");?>
, <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['answer']->value['time'],"%H:%M");?>
 Uhr
                </div>
                <div class="column right aligned">
                    <?php if ((($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.deletemessage.own") && $_smarty_tpl->tpl_vars['answer']->value['creator']->userID == $_smarty_tpl->tpl_vars['__KT']->value['user']->userID) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.deletemessage.other")) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.quote")) {?>
                        <div class="ui right aligned grid mobile only">
                            <div class="column right aligned">
                                <div class="ui small icon buttons">
                                    <div class="ui top right pointing dropdown button answeractions">
                                        <i class="settings icon"></i>
                                        <div class="menu">
                                            <?php if (($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.deletemessage.own") && $_smarty_tpl->tpl_vars['answer']->value['creator']->userID == $_smarty_tpl->tpl_vars['__KT']->value['user']->userID) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.deletemessage.other")) {?>
                                                <div class="item deletebutton" data-id="<?php echo $_smarty_tpl->tpl_vars['answer']->value['id'];?>
" data-type="5">Löschen</div>
                                            <?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.quote")) {?>
                                                <div class="item quotebutton" data-username="<?php echo $_smarty_tpl->tpl_vars['answer']->value['creator']->getUserName();?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['answer']->value['id'];?>
" data-type="answer">Zitieren</div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </h4>
        <div class="ui middle attached clearing segment">
            <span id="ticketanswercontent<?php echo $_smarty_tpl->tpl_vars['answer']->value['id'];?>
">
                <?php echo $_smarty_tpl->tpl_vars['answer']->value['content'];?>

            </span>
            <br>
            <div class="ui computer and tablet only grid">
                <div class="column right aligned">
                    <div class="ui small icon buttons">
                        <?php if (($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.deletemessage.own") && $_smarty_tpl->tpl_vars['answer']->value['creator']->userID == $_smarty_tpl->tpl_vars['__KT']->value['user']->userID) || $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.deletemessage.other")) {?>
                            <button class="ui button deletebutton" data-tooltip="Löschen" data-id="<?php echo $_smarty_tpl->tpl_vars['answer']->value['id'];?>
" data-type="5"><i class="icon trash"></i></button>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.quote")) {?>
                            <button class="ui button quotebutton" data-username="<?php echo $_smarty_tpl->tpl_vars['answer']->value['creator']->getUserName();?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['answer']->value['id'];?>
" data-type="answer" data-tooltip="Zitieren"><i class="icon quote left"></i></button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
<div class="ticketspacer"></div>
<div class="ui divider"></div>
<div class="ticketspacer"></div>
<?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.tickets.answer")) {?>
    <?php if ($_smarty_tpl->tpl_vars['ticket']->value->getState() == 1) {?>
    <form id="addform" class="ui form<?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false) {?> error<?php }
if ($_smarty_tpl->tpl_vars['success']->value) {?> success<?php }?>" action="<?php echo smarty_function_link(array('url'=>"ticket-".((string)$_smarty_tpl->tpl_vars['ticket']->value->ticketID)),$_smarty_tpl);?>
" method="post">
        <div class="field<?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false) {?> error<?php }?>">
            <label>Antwort</label>
            <textarea id="text" name="text"><?php if (isset($_smarty_tpl->tpl_vars['tpl']->value['post']['text']) && !$_smarty_tpl->tpl_vars['success']->value) {
echo $_smarty_tpl->tpl_vars['tpl']->value['post']['text'];
}?></textarea>
        </div>
        <br>
        <?php echo $_smarty_tpl->tpl_vars['recaptcha']->value;?>

        <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
        <input type="hidden" name="CRSF" value="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['CRSF'];?>
">
        <?php if ($_smarty_tpl->tpl_vars['errors']->value['text'] !== false || $_smarty_tpl->tpl_vars['errors']->value['token'] !== false) {?>
            <div class="ui error message">
                <ul class="list">
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
        <?php if ($_smarty_tpl->tpl_vars['success']->value) {?>
            <div class="ui success message">
                <ul class="list">
                    <li>Deine Antwort wurde erfolgreich gespeichert.</li>
                </ul>
            </div>
        <?php }?>
    </form>
    <?php } else { ?>
    <div class="ui warning message">
        <ul class="list">
            <li>Dieses Ticket wurde geschlossen oder wurde als erledigt markiert, deshalb kannst du nicht antworten.</li>
        </ul>
    </div>
    <?php }
} else { ?>
    <div class="ui error message">
        <ul class="list">
            <li>Du hast nicht die erforderliche Berechtigung um auf ein Ticket zu antworten.</li>
        </ul>
    </div>
<?php }
$_smarty_tpl->_subTemplateRender("file:wysiwyg.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('selector'=>"#text"), 0, false);
echo '<script'; ?>
>
$(".ui.dropdown.settings").dropdown({
    action: 'select'
});
$('.ui.dropdown.settings').dropdown('setting', 'onChange', function(value, text, $choice){
    var data = ajax.call(value, <?php echo $_smarty_tpl->tpl_vars['ticket']->value->ticketID;?>
);
    if(data['success'] !== undefined) {
        toast.create(data['title'], data['message'], "success");
        if(tinymce.editors[0] !== undefined) {
            tinymce.remove(tinymce.editors[0]);
            document.getElementById("addform").remove();
        }
        setTimeout(function() {
            window.location.reload();
        }, 3000)
    } else {
        toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
    }
});
$(".deletebutton").click(function() {
    var type = $(this).data("type");
    var id = $(this).attr("data-id");
    modal.confirm("Möchtest du diesen Eintrag wirklich löschen. Dies kann nicht rückgängig gemacht werden.", function() {
        var data = ajax.call(type, id);
            if(data['success'] !== undefined) {
                toast.create(data['title'], data['message'], "success");
                if(type == 5) {
                    $("#ticketanswer" + id).fadeOut();
                }
                $(this).remove();
                setTimeout(function() {
                    if(type == 5) {
                        window.location.reload();
                    } else {
                        window.location.replace("<?php echo smarty_function_link(array('url'=>''),$_smarty_tpl);?>
");
                    }
                }, 3000)
            } else {
                toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
            }
    });
});
$(".quotebutton").click(function() {
    quote($(this)[0]);
});
$(".answeractions").dropdown();

function quote(elem) {
    var type = elem.dataset.type;
    var id = elem.dataset.id;
    var username = elem.dataset.username;
    var content = null;
    if(type == "answer") {
        content = document.getElementById("ticketanswercontent" + id).innerHTML;
    } else if(type == "ticket") {
        content = document.getElementById("ticketcontent" + id).innerHTML;
    }

    var quote = '<blockquote contenteditable="false" data-name="Zitat"><div contenteditable="true">' + content + '</div><p><span style="font-size: 8pt;"><em><a href="#ticketanswer' + id + '">von ' + username + '</a></em></span></p></blockquote>';
    tinymce.editors[0].insertContent(quote);
}
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
