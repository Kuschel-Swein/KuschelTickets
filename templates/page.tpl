{include file="header.tpl" title=$title}
{if $type == "2"}
    {include file="string:{$content}"}
{else}
    {$content}
{/if}
{include file="footer.tpl"}