{include file="header.tpl" title="Drittanbieter Login"}
{if $messages !== null}
    <div class="ui {$type} message">
        <ul class="list">
            {foreach from=$messages item="message"}
                <li>{$message}</li>
            {/foreach}
        </ul>
    </div>
{/if}
{include file="footer.tpl"}