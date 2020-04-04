{include file="header.tpl" title="Ticket"}
<div class="column">
    <div class="ui middle attached segment">
        <div class="ui blue ribbon label">{$ticket->getCategory()}</div>
        {$ticket->getTitle()}
        <div class="ui dropdown float-right">
            <div class="text"><i class="cogs icon"></i></div>
            <div class="menu">
                {if $ticket->getState() == 1}
                    <div class="item" data-value="1">Ticket schließen</div>
                    <div class="item" data-value="3">Ticket als erledigt markieren</div>
                {else}
                    <div class="item" data-value="2">Ticket erneut öffnen</div>
                {/if}
            </div>
        </div>
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
            <div class="three wide column right aligned">
                {if $__KT['user']->hasPermission("general.tickets.deletemessage.own") && $ticket->getCreator()->userID == $__KT['user']->userID}
            <a class="ui label deletebutton" data-id="{$ticket->ticketID}" data-type="6"><i class="icon trash"></i>Löschen</a>
        {else if $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
            <a class="ui label deletebutton" data-id="{$ticket->ticketID}" data-type="6"><i class="icon trash"></i>Löschen</a>
        {/if}
            </div>
        </div>
    </h4>
    <div class="ui bottom attached segment">{$ticket->getContent()}</div>    
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
                <div class="three wide column right aligned">
                {if $__KT['user']->hasPermission("general.tickets.deletemessage.own") && $ticket->getCreator()->userID == $__KT['user']->userID}
                    <a class="ui label deletebutton" data-id="{$answer['id']}" data-type="5"><i class="icon trash"></i>Löschen</a>
                {else if $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
                    <a class="ui label deletebutton" data-id="{$answer['id']}" data-type="5"><i class="icon trash"></i>Löschen</a>
                {/if}
                </div>
            </div>
        </h4>
        <div class="ui bottom attached segment">{$answer['content']}</div>
    </div>
{/if}
{/foreach}
<div class="ticketspacer"></div>
<div class="ui divider"></div>
<div class="ticketspacer"></div>
{if $__KT['user']->hasPermission("general.tickets.answer")}
    {if $ticket->getState() == 1}
    <form id="addform" class="ui form{if $errors['text'] !== false || $errors['token'] !== false} error{/if}{if $success} success{/if}" action="index.php?ticket-{$ticket->ticketID}" method="post">
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
$(".ui.dropdown").dropdown({
    action: 'select'
});
$('.ui.dropdown').dropdown('setting', 'onChange', function(value, text, $choice){
    var data = ajax.call(value, {$ticket->ticketID});
    if(data['success'] !== undefined) {
        $.uiAlert({
            textHead: data['title'],
            text: data['message'],
            bgcolor: "#21ba45",
            textcolor: "#fff",
            position: "top-right",
            icon: 'check',
            time: 3
        });
        if(tinymce.editors[0] !== undefined) {
            tinymce.remove(tinymce.editors[0]);
            document.getElementById("addform").remove();
        }
        setTimeout(function() {
            window.location.reload();
        }, 3000)
    } else {
        $.uiAlert({
            textHead: "Fehler",
            text: "Es ist ein Fehler aufgetreten, bitte versuche es erneut.",
            bgcolor: "#d01919",
            textcolor: "#fff",
            position: "top-right",
            icon: 'times',
            time: 3
        });
    }
});
$(".deletebutton").click(function() {
    var type = $(this).data("type");
    var id = $(this).attr("data-id");
    modal.confirm("Möchtest du diesen Eintrag wirklich löschen. Dies kann nicht rückgängig gemacht werden.", function() {
        var data = ajax.call(type, id);
            if(data['success'] !== undefined) {
                $.uiAlert({
                    textHead: data['title'],
                    text: data['message'],
                    bgcolor: "#21ba45",
                    textcolor: "#fff",
                    position: "top-right",
                    icon: 'check',
                    time: 3
                });
                if(type == 5) {
                    $("#ticketanswer" + id).fadeOut();
                }
                $(this).remove();
                setTimeout(function() {
                    if(type == 5) {
                        window.location.reload();
                    } else {
                        window.location.replace("index.php");
                    }
                }, 3000)
            } else {
                $.uiAlert({
                    textHead: "Fehler",
                    text: "Es ist ein Fehler aufgetreten, bitte versuche es erneut.",
                    bgcolor: "#d01919",
                    textcolor: "#fff",
                    position: "top-right",
                    icon: 'times',
                    time: 3
                });
            }
    });
});
</script>
{include file="footer.tpl"}