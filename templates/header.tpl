<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>{$title} - {$__KT['pagetitle']}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="{$__KT['faviconmime']}" href="{$__KT['mainurl']}data/favicon.{$__KT['faviconextension']}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap" crossorigin="anonymous">
        <link rel="stylesheet" href="{$__KT['mainurl']}assets/semantic.min.css?v={$__KT['version']}">
        <link rel="stylesheet" href="{$__KT['mainurl']}assets/toast.css?v={$__KT['version']}">     
        <link rel="stylesheet" href="{$__KT['mainurl']}assets/master.css?v={$__KT['version']}">    
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>
        <script src="{$__KT['mainurl']}assets/semantic.min.js?v={$__KT['version']}" type="text/javascript"></script>
        <script src="{$__KT['mainurl']}assets/toast.js?v={$__KT['version']}" type="text/javascript"></script>
        <script>
            const kuscheltickets_version = "{$__KT['version']}";
            const notifications_link = "{link url="notifications"}";
            const KT = {
                {if !$__KT['user']->userID}
                userID: null,
                {else}
                userID: {$__KT['user']->userID},
                {/if}
                mainurl: "{$__KT['mainurl']}",
                seourls: {if $__KT['seourls']}true{else}false{/if},
                externalURLFavicons: {if $__KT['externalURLFavicons']}true{else}false{/if},
                externalURLWarning: {if $__KT['externalURLWarning']}true{else}false{/if},
                pushNotificationsAvailable: false,
                canJoinChat: {if $__KT['user']->userID}{$__KT['user']->hasPermission("general.supportchat.join")}{else}false{/if},
                canOpenSupportchat: {if $__KT['user']->userID}{$__KT['user']->hasPermission("mod.supportchat.create")}{else}false{/if},
                pagetitle: "{$__KT['pagetitle']}",
                faviconextension: "{$__KT['faviconextension']}",
                externalURLTitle: {if $__KT['externalURLTitle']}true{else}false{/if},
                proxyAllImages: {if $__KT['proxyAllImages']}true{else}false{/if},
                useDesktopNotification: {if $__KT['useDesktopNotification']}true{else}false{/if}
            };
        </script>
        <script src="{$__KT['mainurl']}/assets/master.js?v={$__KT['version']}" type="text/javascript"></script>
        <script>
            KT.userTemplates = ajax.call(19, 1)['message'];
        </script>
    </head>
    <body id="main"> 
        <div class="ui mobile tablet only padded grid">
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
                    {$__KT['topnavigation']}
                    {if $__KT['user']->userID && $__KT['user']->hasPermission("general.notifications.view")}
                        <a href="{link url="notifications"}" class="item"{if $__KT['user']->userID}{if $__KT['user']->twofactor->use == true}{if !isset($tpl['session']['twofactor'])} style="display: none"{/if}{/if}{/if}>Benachrichtigungen <span class="ui red label notificationbadgehandler"></span></a>
                    {/if}
                    {if $__KT['user']->userID}
                        <a href="{link url="logout/token-{$__KT['CRSF']}"}" class="item">Logout</a>
                    {else}
                        <a href="{link url="login"}" class="item{if $__KT['activepage'] =="login"} active{/if}">Login</a>
                    {/if}
                </div>
            </div>
        </div>
        <div class="ui computer only grid menu top fixed">
            <div class="item">
                <h2>{$__KT['pagetitle']}</h2>
            </div>
            {$__KT['topnavigation']}
            {if $__KT['user']->userID && $__KT['user']->hasPermission("general.notifications.view")}
                <div class="item right"{if $__KT['user']->userID}{if $__KT['user']->twofactor->use == true}{if !isset($tpl['session']['twofactor'])} style="visibility: hidden"{/if}{/if}{/if}>
                    <div id="notificationsbell" class="pointer" data-position="bottom center">
                        <i class="icon bell"></i>
                        <div class="floating ui tiny red label notificationbadge notificationbadgehandler"></div>
                    </div>
                </div>
            {else}
                <div class="item right" style="visibility: hidden"></div>
            {/if}
            {if $__KT['user']->userID}
                <div class="item right" {if $__KT['user']->userID && $__KT['user']->hasPermission("general.notifications.view")}style="margin-left: 0!important"{/if}>
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

