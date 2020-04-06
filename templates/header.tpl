<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>{$title} - {$__KT['pagetitle']}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="{$__KT['faviconmime']}" href="{$__KT['mainurl']}data/favicon.{$__KT['faviconextension']}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap">
        <link rel="stylesheet" href="{$__KT['mainurl']}assets/semantic.min.css">
        <link rel="stylesheet" href="{$__KT['mainurl']}assets/toast.css">     
        <link rel="stylesheet" href="{$__KT['mainurl']}assets/master.css">    
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="{$__KT['mainurl']}assets/semantic.min.js" type="text/javascript"></script>
        <script src="{$__KT['mainurl']}assets/toast.js" type="text/javascript"></script>
        <script>
            const kuscheltickets_version = "{$__KT['version']}";
            const notifications_link = "{link url="notifications"}";
            const KT = {
                {if $__KT['user'] == null}
                userID: null,
                {else}
                userID: {$__KT['user']->userID},
                {/if}
                mainurl: "{$__KT['mainurl']}",
                seourls: {if $__KT['seourls']}true{else}false{/if},
                externalURLFavicons: {if $__KT['externalURLFavicons']}true{else}false{/if},
                externalURLWarning: {if $__KT['externalURLWarning']}true{else}false{/if},
                pushNotificationsAvailable: false,
                localStorage: null,
                pagetitle: "{$__KT['pagetitle']}",
                faviconextension: "{$__KT['faviconextension']}",
                externalURLTitle: {if $__KT['externalURLTitle']}true{else}false{/if},
                proxyAllImages: {if $__KT['proxyAllImages']}true{else}false{/if},
                useDesktopNotification: {if $__KT['useDesktopNotification']}true{else}false{/if}
            };
        </script>
        <script src="{$__KT['mainurl']}/assets/master.js" type="text/javascript"></script>
        <script>
            KT.userTemplates = ajax.call(19, 1)['message'];
        </script>
    </head>
    <body id="main"> 
        <div class="ui mobile only padded grid">
            <div class="ui top fixed huge fluid menu">
                <div class="header item">{$__KT['pagetitle']}</div>
                    <div class="right menu">
                        <div class="item">
                            <button class="ui icon toggle basic button">
                                <i class="content icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="ui vertical fluid menu">
                    {foreach from=$__KT['topnavigation'] item="link"}
                        {if $link['permission'] !== null}
                            {if $__KT['user'] !== null}
                                {if $__KT['user']->hasPermission($link['permission'])}
                                    <a href="{link url=$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                                {/if}
                            {/if}
                        {else}
                            <a href="{link url=$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                        {/if}
                    {/foreach}
                    {if $__KT['user'] !== null && $__KT['user']->hasPermission("general.notifications.view")}
                        <a href="{link url="notifications"}" class="item">Benachrichtigungen <span class="ui red label notificationbadgehandler"></span></a>
                    {/if}
                    {if $__KT['user'] !== null}
                        <a href="{link url="logout/token-{$__KT['CRSF']}"}" class="item">Logout</a>
                    {else}
                        <a href="{link url="login"}" class="item{if $__KT['activepage'] =="login"} active{/if}">Login</a>
                    {/if}
                </div>
            </div>
        </div>
        <div class="ui tablet computer only grid menu top fixed">
            <div class="item">
                <h2>{$__KT['pagetitle']}</h2>
            </div>
            {foreach from=$__KT['topnavigation'] item="link"}
                {if $link['permission'] !== null}
                    {if $__KT['user'] !== null}
                        {if $__KT['user']->hasPermission($link['permission'])}
                            <a href="{link url=$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                        {/if}
                    {/if}
                {else}
                    <a href="{link url=$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                {/if}
            {/foreach}
            {if $__KT['user'] !== null && $__KT['user']->hasPermission("general.notifications.view")}
                <div class="item right">
                    <div id="notificationsbell" class="pointer" data-position="bottom center">
                        <i class="icon bell"></i>
                        <div class="floating ui tiny red label notificationbadge notificationbadgehandler"></div>
                    </div>
                </div>
            {/if}
            {if $__KT['user'] !== null}
                <div class="item right" {if $__KT['user'] !== null && $__KT['user']->hasPermission("general.notifications.view")}style="margin-left: 0!important"{/if}>
                    <a href="{link url="logout/token-{$__KT['CRSF']}"}" class="ui blue button">Logout</a>
                </div>
            {else}
                <div class="item right">
                    <a href="{link url="login"}" class="ui blue button{if $__KT['activepage'] =="login"} active{/if}">Login</a>
                </div>
            {/if}
        </div>
        <div class="ui grid container" id="content">
            <div class="one wide column"></div>
            <div class="fourteen wide column">

