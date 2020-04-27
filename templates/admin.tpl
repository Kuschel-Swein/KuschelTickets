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
                            <a class="item" href="{link url="admin"}">Dashboard</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.settings")}
                            <a class="item" href="{link url="admin/settings"}">Einstellungen</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.cleanup")}
                            <a class="item" href="{link url="admin/cleanup"}">Aufräumarbeiten</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.errors")}
                            <a class="item" href="{link url="admin/errors"}">Fehler</a>
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
                            <a class="item" href="{link url="admin/faq"}">FAQ</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.faqcategories")}
                            <a class="item" href="{link url="admin/faqcategories"}">FAQ Kategorien</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.pages")}
                            <a class="item" href="{link url="admin/pages"}">Seiten</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.menu")}
                            <a class="item" href="{link url="admin/menuentries"}">Menüeinträge</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.ticketcategories")}
                            <a class="item" href="{link url="admin/ticketcategories"}">Ticket Kategorien</a>
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
                            <a class="item" href="{link url="admin/accounts"}">Accounts</a>
                        {/if}
                        {if $__KT['user']->hasPermission("admin.acp.page.groups")}
                            <a class="item" href="{link url="admin/groups"}">Benutzergruppen</a>
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