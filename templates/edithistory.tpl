{include file="header.tpl" title="Verlauf"}
{assign var="changes" value=$object->getChanges()}
<div class="ui container grid stackable form">
    <div class="nine wide column">
        <h2 class="ui header">
            {if $object->category}Ticket{else}Antwort{/if} von {$object->getCreator()->username} am {$object->time|date_format:"d. F Y"}
        </h2>
    </div>
<div class="five wide column right floated">
    <a class="ui blue button right floated" href="{if $object->category}{link url="ticket-{$object->ticketID}"}#ticketcontent{else}{link url="ticket-{$object->ticketID}"}#ticketanswer{$object->answerID}{/if}">Zum Inhalt gehen</a>
</div>
</div>

{if $changes|count > 0}
<br>
<div class="ui styled fluid accordion" id="changes">
    {foreach from=$changes item="change"}
        <div class="title">
            <i class="dropdown icon"></i>
            Bearbeitung von {$change->getUser()->username}, {$change->time|datetime}
        </div>
        <div class="content" id="change{$change->changeID}">
            <div class="ui horizontal segments">
                <div class="ui segment">
                    <div class="ui header">
                        vorheriger Inhalt
                    </div>
                    <blockquote>
                        {$change->oldContent}
                    </blockquote>
                </div>
                <div class="ui segment">
                    <div class="ui header">
                        neuer Inhalt
                    </div>
                    <blockquote>
                        {$change->newContent}
                    </blockquote>
                </div>
                
            </div>
            <div class="ui segment">
                <div class="ui header">
                    Änderungen
                </div>
                <blockquote>
                    {$change->getFormattedChangedText()}
                </blockquote>
            </div>
        </div>
    {/foreach}
</div>
{else}
<div class="ui info message">
    <ul class="list">
        <li>Für diesen Inhalt existieren keine protokollierten Änderungen.</li>
    </ul>
</div>
{/if}
<script>
$('#changes').accordion();
window.onhashchange = function() {
    var elements = document.querySelectorAll("#changes > .content");
    for(var i = 0; i < elements.length; i++) {
        if(elements[i].id == window.location.hash.substr(1)) {
            $('#changes').accordion("close others");
            $('#changes').accordion('open', i);
            window.scrollTo(window.scrollX, elements[i].previousSibling.offsetHeight - 170);
        }
    }
};
$(document).ready(function() {
    // workarround for some browsers that have problems with the correct scrolling
    window.onhashchange();
    window.onhashchange();
    window.onhashchange();
});
</script>
{include file="footer.tpl"}