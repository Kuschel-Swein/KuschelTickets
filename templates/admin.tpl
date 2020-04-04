{include file="header.tpl" title="{$title} - Administration"}
<div class="ui container grid stackable">
    <div class="three wide column">
        <div class="ui styled accordion">
            <div class="title">
                <i class="dropdown icon"></i>
                Allgemein
            </div>
            <div class="content">
                <p>
                    <ul class="ui list">
                        {if $__KT['user']->hasPermission("admin.acp.page.dashboard")}
                            <a class="item" href="index.php?admin/">Dashboard</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.settings")}
                            <a class="item" href="index.php?admin/settings">Einstellungen</a>
                        {/if}
                        
                    </ul>
                </p>
            </div>
            <div class="title">
                <i class="dropdown icon"></i>
                Module
            </div>
            <div class="content">
                <p>
                    <ul class="ui list">
                        {if $__KT['user']->hasPermission("admin.acp.page.faq")}
                            <a class="item" href="index.php?admin/faq">FAQ</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.faqcategories")}
                            <a class="item" href="index.php?admin/faqcategories">FAQ Kategorien</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.pages")}
                            <a class="item" href="index.php?admin/pages">Seiten</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.ticketcategories")}
                            <a class="item" href="index.php?admin/ticketcategories">Ticket Kategorien</a>
                        {/if}
                    </ul>
                </p>
            </div>
            <div class="title">
                <i class="dropdown icon"></i>
                Benutzer
            </div>
            <div class="content">
                <p>
                    <ul class="ui list">
                        {if $__KT['user']->hasPermission("admin.acp.page.accounts")}
                            <a class="item" href="index.php?admin/accounts">Accounts</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.groups")}
                            <a class="item" href="index.php?admin/groups">Benutzergruppen</a>
                        {/if}
                    </ul>
                </p>
            </div>
        </div>
    </div>
    <div class="thirteen wide column">
        <div class="ui segment">
            <h2 class="ui header">
                {$title}
            </h2>
            {include file=$file}
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('.ui.accordion').accordion();
});
</script>
{include file="footer.tpl"}