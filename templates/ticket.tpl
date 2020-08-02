{include file="header.tpl" title="Ticket"}
<div class="column">
    <div class="ui middle attached segment ticketTitle">
        <div class="ui {$ticket->color} ribbon label">{$ticket->category}</div>
        {$ticket->title}
        {if ($__KT['user']->hasPermission("mod.tickets.close") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.close.own"))) ||
            ($__KT['user']->hasPermission("mod.tickets.done") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.done.own"))) ||
            ($__KT['user']->hasPermission("mod.tickets.reopen") || ($ticket->getCreator()->userID == $__KT['user'] && $__KT['user']->hasPermission("general.tickets.reopen.own")))
        }
            <div class="ui dropdown top right pointing settings float-right">
                <div class="text" data-tooltip="Einstellungen"><i class="cogs icon"></i></div>
                <div class="menu">
                    {if $ticket->state == 1}
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
<div class="ui segments ticketanswer" id="ticketcontent">
    <div class="ui attached padded segment">
        <div class="ui stackable grid">
            <div class="ui row">
                <div class="ui five wide tablet three wide computer column usersidebar">
                <div class="ui center aligned grid">
                    <div class="column">
                        <img class="useravatar ui small circular image" draggable="false" src="{$ticket->getCreator()->getAvatar()}" alt="{$ticket->getCreator()->username}">
                        <h3 class="ui header {$ticket->getCreator()->getGroup()->getBadgeColor()}">
                            {$ticket->getCreator()->username}
                        </h3>
                        {$ticket->getCreator()->getGroup()->getGroupBadge()}<br>
                    </div>
                </div>
                <div class="ui divider"></div>
                <div class="ui list">
                    <div class="item">
                        <div class="content">
                            <div class="header">Tickets</div>
                            <div class="res right floated description">{$ticket->getCreator()->getTicketCount()}</div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="content">
                            <div class="header">2-Faktor Authentisierung</div>
                            <div class="res right floated description">{if $ticket->getCreator()->twofactor->use}Ja{else}Nein{/if}</div>
                        </div>
                    </div>
                    {if $__KT['oauth']['google']['use'] || $__KT['oauth']['github']['use']}
                    <div class="item">
                        <div class="content">
                            <div class="header">Drittanbieter</div>
                            <div class="res right floated description">{$ticket->getCreator()->getOauthProvider()}</div>
                        </div>
                    </div>
                    {/if}
                </div>
                <div class="ui list">
                    <div class="ui divider"></div>
                    <div class="item">
                        <div class="content">
                            <div class="header">Benutzer ID</div>
                            <div class="res right floated description">{$ticket->getCreator()->userID}</div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="ui eleven wide tablet thirteen wide computer column ticketanswerContent">
                <div class="ticketanswerContentContent">
                    {if $ticket->customInputResponse|strlen > 0}
                        {$ticket->customInputResponse}
                        <div class="ui divider"></div>
                    {/if}
                    <span id="ticketmaincontent">
                        {$ticket->content}
                    </span>
                </div>
                {if $ticket->getCreator()->signature|strlen > 0 && $ticket->getCreator()->hasPermission("general.account.signature")}
                <div class="ui divider"></div>
                    {$ticket->getCreator()->signature}
                {/if}
                </div>
            </div>
        </div>
    </div>
    <div class="ui bottom attached secondary segment">
        <div class="ticketFooter">
        {$ticket->getCreator()->username}&nbsp;·&nbsp;{$ticket->time|datetime}
        {if $ticket->getLatestChange() !== null}
            &nbsp;·&nbsp;{if $__KT['user']->hasPermission("mod.tickets.edithistory")}<a class="ticketFooterLink" href="{link url="edithistory-{$ticket->ticketID}"}#change{$ticket->getLatestChange()->changeID}">{/if}Letzte Änderung: {$ticket->getLatestChange()->time|datetime} ({$ticket->getLatestChange()->getUser()->username}){if $__KT['user']->hasPermission("mod.tickets.edithistory")}</a>{/if}
        {/if}
        </div>
        {if (($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $ticket->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")) || ($__KT['user']->hasPermission("general.tickets.quote") && $ticket->state == 1 && $__KT['user']->hasPermission("general.tickets.answer")) || ($__KT['user']->hasPermission("general.tickets.edit.own") && $answer->creator == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.edit.all")}
            <div class="ui right aligned grid mobile only ticketanswerActionContainer">
                <div class="column right aligned ticketanswerAction">
                    <div class="ui small icon buttons">
                        <div class="ui top right pointing dropdown button answeractions">
                            <i class="settings icon"></i>
                            <div class="menu">
                                {if ($__KT['user']->hasPermission("general.tickets.edit.own") && $ticket->creator == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.edit.all")}
                                    <div class="item editbutton" data-id="{$ticket->ticketID}" data-type="35">Bearbeiten</div>
                                {/if}
                                {if ($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $ticket->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
                                    <div class="item deletebutton" data-id="{$ticket->ticketID}" data-type="6">Löschen</div>
                                {/if}
                                {if $__KT['user']->hasPermission("general.tickets.quote") && $ticket->state == 1 && $__KT['user']->hasPermission("general.tickets.answer")}
                                    <div class="item quotebutton" data-username="{$ticket->getCreator()->username}" data-id="{$ticket->ticketID}" data-type="ticket">Zitieren</div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
        <div class="ui computer and tablet only grid ticketanswerActionContainer">
            <div class="column right aligned ticketanswerAction">
                <div class="ui small icon buttons">
                    {if ($__KT['user']->hasPermission("general.tickets.edit.own") && $ticket->creator == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.edit.all")}
                        <button class="ui button editbutton" data-id="{$ticket->ticketID}" data-type="35" data-tooltip="Bearbeiten"><i class="icon pencil"></i></button>
                    {/if}
                    {if ($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $ticket->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
                        <button class="ui button deletebutton" data-tooltip="Löschen" data-id="{$ticket->ticketID}" data-type="6"><i class="icon trash"></i></button>
                    {/if}
                    {if $__KT['user']->hasPermission("general.tickets.quote") && $ticket->state == 1 && $__KT['user']->hasPermission("general.tickets.answer")}
                        <button class="ui button quotebutton" data-username="{$ticket->getCreator()->username}" data-id="{$ticket->ticketID}" data-type="ticket" data-tooltip="Zitieren"><i class="icon quote left"></i></button>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>
{foreach from=$ticket->getAnswers() item="answer"}
{if $answer->creator == 0}
    <div class="column ticketModificationEntry">
        <div class="ui tablet computer only grid">
            <div class="column">
                <h4 class="ui horizontal divider header">
                    {$answer->content}&nbsp;·&nbsp;{$answer->time|datetime}
                </h4>
            </div>
        </div>
        <div class="ui mobile only grid center aligned">
            <div class="middle aligned row">
                <div class="column">
                    <div class="ui divider"></div>
                    <h4 class="ui header display-inline">
                        {$answer->content}&nbsp;·&nbsp;{$answer->time|datetime}
                    </h4>
                    <div class="ui divider"></div>
                </div>
            </div>
        </div>
    </div>
{else}
    <div class="ui segments ticketanswer" id="ticketanswer{$answer->answerID}">
        <div class="ui attached padded segment">
            <div class="ui stackable grid">
                <div class="ui row">
                    <div class="ui five wide tablet three wide computer column usersidebar">
                    <div class="ui center aligned grid">
                        <div class="column">
                            <img class="useravatar ui small circular image" draggable="false" src="{$answer->getCreator()->getAvatar()}" alt="{$answer->getCreator()->username}">
                            <h3 class="ui header {$answer->getCreator()->getGroup()->getBadgeColor()}">
                                {$answer->getCreator()->username}
                            </h3>
                            {$answer->getCreator()->getGroup()->getGroupBadge()}<br>
                        </div>
                    </div>
                    <div class="ui divider"></div>
                    <div class="ui list">
                        <div class="item">
                            <div class="content">
                                <div class="header">Tickets</div>
                                <div class="res right floated description">{$answer->getCreator()->getTicketCount()}</div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="content">
                                <div class="header">2-Faktor Authentisierung</div>
                                <div class="res right floated description">{if $answer->getCreator()->twofactor->use}Ja{else}Nein{/if}</div>
                            </div>
                        </div>
                        {if $__KT['oauth']['google']['use'] || $__KT['oauth']['github']['use']}
                        <div class="item">
                            <div class="content">
                                <div class="header">Drittanbieter</div>
                                <div class="res right floated description">{$answer->getCreator()->getOauthProvider()}</div>
                            </div>
                        </div>
                        {/if}
                    </div>
                    <div class="ui list">
                        <div class="ui divider"></div>
                        <div class="item">
                            <div class="content">
                                <div class="header">Benutzer ID</div>
                                <div class="res right floated description">{$answer->getCreator()->userID}</div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="ui eleven wide tablet thirteen wide computer column ticketanswerContent">
                    <div id="ticketanswercontent{$answer->answerID}" class="ticketanswerContentContent">
                        {$answer->content}
                    </div>
                    {if $answer->getCreator()->signature|strlen > 0 && $answer->getCreator()->hasPermission("general.account.signature")}
                    <div class="ui divider"></div>
                        {$answer->getCreator()->signature}
                    {/if}
                    </div>
                </div>
            </div>
        </div>
        <div class="ui bottom attached secondary segment">
            <div class="ticketFooter">
            {$answer->getCreator()->username}&nbsp;·&nbsp;{$answer->time|datetime}
            {if $answer->getLatestChange() !== null}
                &nbsp;·&nbsp;{if $__KT['user']->hasPermission("mod.tickets.edithistory")}<a class="ticketFooterLink" href="{link url="edithistory-{$ticket->ticketID}/answer-{$answer->answerID}"}#change{$answer->getLatestChange()->changeID}">{/if}Letzte Änderung: {$answer->getLatestChange()->time|datetime} ({$answer->getLatestChange()->getUser()->username}){if $__KT['user']->hasPermission("mod.tickets.edithistory")}</a>{/if}
            {/if}
            </div>
            {if (($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $answer->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")) || ($__KT['user']->hasPermission("general.tickets.quote") && $ticket->state == 1 && $__KT['user']->hasPermission("general.tickets.answer")) || ($__KT['user']->hasPermission("general.tickets.edit.own") && $answer->creator == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.edit.all")}
                <div class="ui right aligned grid mobile only ticketanswerActionContainer">
                    <div class="column right aligned ticketanswerAction">
                        <div class="ui small icon buttons">
                            <div class="ui top right pointing dropdown button answeractions">
                                <i class="settings icon"></i>
                                <div class="menu">
                                    {if ($__KT['user']->hasPermission("general.tickets.edit.own") && $answer->creator == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.edit.all")}
                                        <div class="item editbutton" data-id="{$answer->answerID}" data-type="34">Bearbeiten</div>
                                    {/if}
                                    {if ($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $answer->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
                                        <div class="item deletebutton" data-id="{$answer->answerID}" data-type="5">Löschen</div>
                                    {/if}
                                    {if $__KT['user']->hasPermission("general.tickets.quote") && $ticket->state == 1 && $__KT['user']->hasPermission("general.tickets.answer")}
                                        <div class="item quotebutton" data-username="{$answer->getCreator()->username}" data-id="{$answer->answerID}" data-type="answer">Zitieren</div>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
            <div class="ui computer and tablet only grid ticketanswerActionContainer">
                <div class="column right aligned ticketanswerAction">
                    <div class="ui small icon buttons">
                        {if ($__KT['user']->hasPermission("general.tickets.edit.own") && $answer->creator == $__KT['user']->userID) || $__KT['user']->hasPermission("mod.tickets.edit.all")}
                            <button class="ui button editbutton" data-id="{$answer->answerID}" data-type="34" data-tooltip="Bearbeiten"><i class="icon pencil"></i></button>
                        {/if}
                        {if ($__KT['user']->hasPermission("general.tickets.deletemessage.own") && $answer->getCreator()->userID == $__KT['user']->userID) || $__KT['user']->hasPermission("general.tickets.deletemessage.other")}
                            <button class="ui button deletebutton" data-tooltip="Löschen" data-id="{$answer->answerID}" data-type="5"><i class="icon trash"></i></button>
                        {/if}
                        {if $__KT['user']->hasPermission("general.tickets.quote") && $ticket->state == 1 && $__KT['user']->hasPermission("general.tickets.answer")}
                            <button class="ui button quotebutton" data-username="{$answer->getCreator()->username}" data-id="{$answer->answerID}" data-type="answer" data-tooltip="Zitieren"><i class="icon quote left"></i></button>
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
    {if $ticket->state == 1}
    <form id="addform" class="ui form{if $errors['text'] !== false || $errors['token'] !== false} error{/if}{if $success} success{/if}" action="{link url="ticket-{$ticket->ticketID}"}" method="post">
        <div class="field required{if $errors['text'] !== false} error{/if}">
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
        {if $__KT['ticketRating'] && $ticket->state !== 1}
            <div class="ui segment" id="ratingSection">
                <br>
                {if !$ticket->hasRating()}
                    {if $ticket->getCreator()->userID == $__KT['user']->userID}
                        {if $__KT['user']->hasPermission("general.ticket.rate")}
                            <div class="ui one column center aligned page grid">
                                <h4 id="ticketRating_title">Bitte bewerte unseren Support:</h4><br>
                            </div>
                            <div class="ui one column center aligned page grid">
                                <div class="ui massive {$__KT['ticketRatingIcon']} rating" id="ticketRating"></div>
                            </div>
                        {else}
                            <div class="ui error message">
                                <ul class="list">
                                    <li>Du hast nicht die erforderliche Berechtigung Tickets zu bewerten.</li>
                                </ul>
                            </div>
                        {/if}
                    {else}
                        <div class="ui info message">
                            <ul class="list">
                                <li>Der Ticketersteller hat noch keine Bewertung zu diesem Ticket abgegeben.</li>
                            </ul>
                        </div>
                    {/if}
                {else}
                    <div class="ui one column center aligned page grid">
                        <h4>Bewertung:</h4><br>
                    </div>
                    <div class="ui one column center aligned page grid">
                        <div class="ui massive {$__KT['ticketRatingIcon']} rating" data-max-rating="5" data-rating="{$ticket->rating}" id="ticketRating"></div>
                    </div>
                {/if}
                <br>
            </div>
        {/if}
    {/if}
{else}
    <div class="ui error message">
        <ul class="list">
            <li>Du hast nicht die erforderliche Berechtigung um auf ein Ticket zu antworten.</li>
        </ul>
    </div>
{/if}
{include file="__wysiwyg.tpl" selector="#text"}
<script>
{if !$ticket->hasRating()}
    {if $ticket->getCreator()->userID == $__KT['user']->userID}
        {if $__KT['user']->hasPermission("general.ticket.rate")}
            $("#ticketRating").rating({
                initialRating: 0,
                maxRating: 5,
                onRate: function(rating) {
                    {if $__KT['ticketRatingIcon'] == "star"}
                    var ratingIcon = "Sternen";
                    {else if $__KT['ticketRatingIcon'] == "heart"}
                    var ratingIcon = "Herzen";
                    {/if}
                    modal.confirm("Möchtest du dieses Ticket wirklich mit <b>" + rating + " von 5</b> " + ratingIcon + " bewerten?", function() {
                        var data = ajax.post(32, {$ticket->ticketID}, "rating=" + rating);
                        if(data.success) {
                            toast.create(data.title, data.message, "success");
                            $("#ticketRating").rating("disable");
                            document.getElementById("ticketRating_title").innerHTML = "Bewertung:";
                        } else {
                            toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                        }
                    });
                }
            });
        {/if}
    {/if}
{else}
$("#ticketRating").rating("disable");
{/if}
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
            var elem = document.getElementById("ratingSection");
            if(elem) {
                elem.remove();
            }
            setTimeout(function() {
                window.location.reload();
            }, 3000)
        } else {
            toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
        }
    }
});
{if $__KT['user']->hasPermission("general.tickets.edit.own") || $__KT['user']->hasPermission("mod.tickets.edit.all")}
$(".editbutton").click(function() {
    var callback = function() {
        var type = parseInt($(this).data("type"));
        var id = $(this).data("id");
        var elementID = (type == 34) ? "ticketanswercontent" + id : "ticketmaincontent"
        var editor = {include file="__wysiwygInit.tpl" selector='#" + elementID + "' templates=true}
        editor.then(function(editorData) {
            var submitContainer = document.createElement("div");
            submitContainer.appendChild(document.createElement("br"));
            var submitButton = document.createElement("button");
            submitButton.classList = "ui blue submit button";
            submitButton.innerHTML = "Absenden";
            submitButton.addEventListener("click", function(e) {
                e.preventDefault();
                const showCaptcha = {if "ticketedit"|in_array:$__KT['recaptcha']['cases'] && $__KT['recaptcha']['use']}true{else}false{/if};
                var submitCallback = function(recaptchaToken) {
                    var removeNotice = false;
                    {if $__KT['user']->hasPermission("mod.tickets.edit.removenotice")}removeNotice = document.getElementById("removeeditNotice" + id).checked;{/if}
                    var data = ajax.post(type, id, "content=" + encodeURIComponent(tinyMCE.editors[elementID].getContent()) + "&g-recaptcha-response=" + encodeURIComponent(recaptchaToken) + "&removeeditnotice=" + removeNotice);
                    if(data['success'] !== undefined) {
                        if(data['success'] == "true") {
                            toast.create(data['title'], data['message'], "success");
                            tinyMCE.editors[elementID].destroy();
                            submitContainer.remove();
                        } else {
                            toast.create("Fehler", data['message'], "error");
                        }
                    } else {
                        toast.create("Fehler", "Es ist ein Fehler aufgetreten, bitte versuche es erneut.", "error");
                    }
                };
                if(showCaptcha) {
                    modal.captcha(submitCallback);
                } else {
                    submitCallback("recaptcha");
                }
            });        
            {if $__KT['user']->hasPermission("mod.tickets.edit.removenotice")}
            var field = document.createElement("div");
            field.classList.add("field");
            var checkboxContainer = document.createElement("div");
            checkboxContainer.classList = "ui checkbox";
            checkboxContainer.innerHTML = '<input type="checkbox" tabindex="0" class="hidden" id="removeeditNotice' + id + '"><label>Editierungshinweis entfernen</label>';
            field.appendChild(checkboxContainer);
            submitContainer.appendChild(field);
            submitContainer.appendChild(document.createElement("br"));
            {/if}
            submitContainer.appendChild(submitButton);
            var cancelButton = document.createElement("button")
            cancelButton.classList = "ui button";
            cancelButton.innerHTML = "Abbruch";
            cancelButton.addEventListener("click", function(e) {
                e.preventDefault();
                editorData[0].destroy();
                submitContainer.remove();
            });
            submitContainer.appendChild(cancelButton);
            $(submitContainer).insertAfter(editorData[0].editorContainer);
            $('.ui.checkbox').checkbox();
        });
    }.bind(this);
    if(typeof tinyMCE === "undefined") {
        utils.loadScript(KT.mainurl + "assets/tinymce/tinymce.min.js", callback);
    } else {
        callback()
    }
});
if(typeof grecaptcha === "undefined") {
    utils.loadScript(KT.recaptchaTypes[KT.captchaType], function() { });
}
{/if}
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
$(".answeractions").dropdown();
{if $__KT['user']->hasPermission("general.tickets.quote") && $ticket->state == 1 && $__KT['user']->hasPermission("general.tickets.answer")}
$(".quotebutton").click(function() {
    quote($(this)[0]);
});
function quote(elem) {
    var type = elem.dataset.type;
    var id = elem.dataset.id;
    var username = elem.dataset.username;
    var content = null;
    if(type == "answer") {
        content = document.getElementById("ticketanswercontent" + id).innerHTML;
    } else if(type == "ticket") {
        content = document.getElementById("ticketmaincontent").innerHTML;
    }

    var quote = '<blockquote contenteditable="false" data-name="Zitat"><div contenteditable="true">' + content + '</div><p><span style="font-size: 8pt;"><em><a href="#ticketanswer' + id + '">von ' + username + '</a></em></span></p></blockquote>';
    tinymce.editors[0].insertContent(quote);
}
{/if}
window.onhashchange = function() {
    var elem = document.getElementById(window.location.hash.substr(1));
    if(elem) {
        window.scrollTo(window.scrollX, elem.offsetHeight - 170);
    }
};
window.onhashchange();
</script>
{include file="footer.tpl"}