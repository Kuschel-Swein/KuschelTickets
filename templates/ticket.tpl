{include file="header.tpl" title="Ticket"}
<div class="column">
    <div class="ui middle attached segment">
        <div class="ui {$ticket->getColor()} ribbon label">{$ticket->getCategory()}</div>
        {$ticket->getTitle()}
        {if ($__KT['user']->hasPermission("mod.tickets.close") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.close.own"))) ||
            ($__KT['user']->hasPermission("mod.tickets.done") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.done.own"))) ||
            ($__KT['user']->hasPermission("mod.tickets.reopen") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.reopen.own")))
        }
            <div class="ui dropdown top right pointing settings float-right">
                <div class="text" data-tooltip="Einstellungen"><i class="cogs icon"></i></div>
                <div class="menu">
                    {if $ticket->getState() == 1}
                        {if $__KT['user']->hasPermission("mod.tickets.close") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.close.own"))}
                            <div class="item" data-value="1">Ticket schließen</div>
                        {/if}
                        {if $__KT['user']->hasPermission("mod.tickets.done") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.done.own"))}
                            <div class="item" data-value="3">Ticket als erledigt markieren</div>
                        {/if}
                    {else}
                        {if $__KT['user']->hasPermission("mod.tickets.reopen") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.reopen.own"))}
                            <div class="item" data-value="2">Ticket erneut öffnen</div>
                        {/if}
                    {/if}
                    {if $__KT['user']->hasPermission("general.ticket.export.pdf") && $__KT['pdfexport']}
                        <div class="item" data-value="4">Ticket als PDF exportieren</div>
                    {/if}
                </div>
            </div>
        {/if}
    </div>
</div>
<br>
<div class="column">
    <h4 class="ui top attached block header">
        <div class="ui left aligned grid container">
            <div class="seven wide column floated left">
                {$ticket->getCreator()->getGroup()->getGroupBadge()} {$ticket->getCreator()->getUserName()}
            </div>
            <div class="six wide column right aligned">
                {$ticket->getTime()|date_format:"%d.%m.%Y"}, {$ticket->getTime()|date_format:"%H:%M"} Uhr
            </div>
            <div class="column right aligned">
                {if (($__KT['user']->hasPermission("general.tickets.delete.own") && $ticket->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.delete")) || $__KT['user']->hasPermission("general.tickets.quote")}
                    <div class="ui right aligned grid mobile only">
                        <div class="column right aligned">
                            <div class="ui small icon buttons">
                                <div class="ui top right pointing dropdown button answeractions">
                                    <i class="settings icon"></i>
                                    <div class="menu">
                                        {if ($__KT['user']->hasPermission("general.tickets.delete.own") && $ticket->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.delete")}
                                            <div class="item deletebutton" data-id="{$ticket->ticketID}" data-type="6">Löschen</div>
                                        {/if}
                                        {if $__KT['user']->hasPermission("general.tickets.quote")}
                                            <div class="item quotebutton" data-username="{$ticket->getCreator()->getUserName()}" data-id="{$ticket->ticketID}" data-type="ticket">Zitieren</div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    </h4>
    <div class="ui middle attached clearing segment">
        <span id="ticketcontent{$ticket->ticketID}">
            {$ticket->getContent()}
        </span>
        <br>
        <div class="ui computer and tablet only grid">
            <div class="column right aligned">
                <div class="ui small icon buttons">
                    {if ($__KT['user']->hasPermission("general.tickets.delete.own") && $ticket->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.delete")}
                        <button class="ui button deletebutton" data-tooltip="Löschen" data-id="{$ticket->ticketID}" data-type="6"><i class="icon trash"></i></button>
                    {/if}
                    {if $__KT['user']->hasPermission("general.tickets.quote")}
                        <button class="ui button quotebutton" data-username="{$ticket->getCreator()->getUserName()}" data-id="{$ticket->ticketID}" data-type="ticket" data-tooltip="Zitieren"><i class="icon quote left"></i></button>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>

{foreach from=$ticket->getAnswers() item="answer"}
{if $answer['creator'] == "system"}
    <div class="column ticketanswer">
        <div class="ui tablet computer only grid">
            <div class="column">
                <h4 class="ui horizontal divider header" >
                    {$answer['content']} - {$answer['time']|date_format:"%d.%m.%Y"}, {$answer['time']|date_format:"%H:%M"} Uhr
                </h4>
            </div>
        </div>
        <div class="ui mobile only grid">
            <div class="column">
                <div class="ui bottom center aligned attached segment">
                    {$answer['content']} - {$answer['time']|date_format:"%d.%m.%Y"}, {$answer['time']|date_format:"%H:%M"} Uhr
                </div>
            </div>
        </div>
    </div>
{else}
    <div class="column ticketanswer" id="ticketanswer{$answer['id']}">
        <h4 class="ui top attached block header">
            <div class="ui left aligned grid container">
                <div class="seven wide column floated left">
                    {$answer['creator']->getGroup()->getGroupBadge()} {$answer['creator']->getUserName()}
                </div>
                <div class="six wide column right aligned">
                    {$answer['time']|date_format:"%d.%m.%Y"}, {$answer['time']|date_format:"%H:%M"} Uhr
                </div>
                <div class="column right aligned">
                    {if (($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $answer['creator']->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")) || $__KT['user']->hasPermission("general.tickets.quote")}
                        <div class="ui right aligned grid mobile only">
                            <div class="column right aligned">
                                <div class="ui small icon buttons">
                                    <div class="ui top right pointing dropdown button answeractions">
                                        <i class="settings icon"></i>
                                        <div class="menu">
                                            {if ($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $answer['creator']->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
                                                <div class="item deletebutton" data-id="{$answer['id']}" data-type="5">Löschen</div>
                                            {/if}
                                            {if $__KT['user']->hasPermission("general.tickets.quote")}
                                                <div class="item quotebutton" data-username="{$answer['creator']->getUserName()}" data-id="{$answer['id']}" data-type="answer">Zitieren</div>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
        </h4>
        <div class="ui middle attached clearing segment">
            <span id="ticketanswercontent{$answer['id']}">
                {$answer['content']}
            </span>
            <br>
            <div class="ui computer and tablet only grid">
                <div class="column right aligned">
                    <div class="ui small icon buttons">
                        {if ($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $answer['creator']->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
                            <button class="ui button deletebutton" data-tooltip="Löschen" data-id="{$answer['id']}" data-type="5"><i class="icon trash"></i></button>
                        {/if}
                        {if $__KT['user']->hasPermission("general.tickets.quote")}
                            <button class="ui button quotebutton" data-username="{$answer['creator']->getUserName()}" data-id="{$answer['id']}" data-type="answer" data-tooltip="Zitieren"><i class="icon quote left"></i></button>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
{/foreach}
<div class="ticketspacer"></div>
<div class="ui divider"></div>
<div class="ticketspacer"></div>
{if $__KT['user']->hasPermission("general.tickets.answer")}
    {if $ticket->getState() == 1}
    <form id="addform" class="ui form{if $errors['text'] !== false || $errors['token'] !== false} error{/if}{if $success} success{/if}" action="{link url="ticket-{$ticket->ticketID}"}" method="post">
        <div class="field{if $errors['text'] !== false} error{/if}">
            <label>Antwort</label>
            <textarea id="text" name="text">{if isset($tpl['post']['text']) && !$success}{$tpl['post']['text']}{/if}</textarea>
        </div>
        <br>
        {$recaptcha}
        <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
        <input type="hidden" name="CRSF" value="{$__KT['CRSF']}">
        {if $errors['text'] !== false || $errors['token'] !== false}
            <div class="ui error message">
                <ul class="list">
                    {if $errors['text'] !== false}
                        <li>{$errors['text']}</li>
                    {/if}
                    {if $errors['token'] !== false}
                        <li>{$errors['token']}</li>
                    {/if}
                </ul>
            </div>
        {/if}
        {if $success}
            <div class="ui success message">
                <ul class="list">
                    <li>Deine Antwort wurde erfolgreich gespeichert.</li>
                </ul>
            </div>
        {/if}
    </form>
    {else}
    <div class="ui warning message">
        <ul class="list">
            <li>Dieses Ticket wurde geschlossen oder wurde als erledigt markiert, deshalb kannst du nicht antworten.</li>
        </ul>
    </div>
    {/if}
{else}
    <div class="ui error message">
        <ul class="list">
            <li>Du hast nicht die erforderliche Berechtigung um auf ein Ticket zu antworten.</li>
        </ul>
    </div>
{/if}
{include file="wysiwyg.tpl" selector="#text"}
<script>
$(".ui.dropdown.settings").dropdown({
    action: 'select'
});
$('.ui.dropdown.settings').dropdown('setting', 'onChange', function(value, text, $choice){
    if(value == 4) {
        window.open(link("ticketpdf-{$ticket->ticketID}"), "target=_blank");
    } else {
        var data = ajax.call(value, {$ticket->ticketID});
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
                        window.location.replace("{link url=""}");
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
</script>
{include file="footer.tpl"}