<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>{$title} - {$__KT['pagetitle']}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap">
        <link rel="stylesheet" href="assets/semantic.min.css">
        <link rel="stylesheet" href="assets/toast.css">     
        <link rel="stylesheet" href="assets/master.css">    
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="assets/semantic.min.js" type="text/javascript"></script>
        <script src="assets/toast.js" type="text/javascript"></script>
        <script src="assets/master.js" type="text/javascript"></script>
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
                                    <a href="index.php?{$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                                {/if}
                            {/if}
                        {else}
                            <a href="index.php?{$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                        {/if}
                    {/foreach}
                    {if $__KT['user'] !== null}
                        <a href="index.php?logout/token-{$__KT['CRSF']}" class="item">Logout</a>
                    {else}
                        <a href="index.php?login" class="item{if $__KT['activepage'] =="login"} active{/if}">Login</a>
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
                            <a href="index.php?{$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                        {/if}
                    {/if}
                {else}
                    <a href="index.php?{$link['href']}" class="item{if $__KT['activepage'] == $link['identifier']} active{/if}{if $link['right']} right{/if}">{$link['text']}</a>
                {/if}
            {/foreach}
            {if $__KT['user'] !== null}
                <div class="item right">
                    <a href="index.php?logout/token-{$__KT['CRSF']}" class="ui blue button">Logout</a>
                </div>
            {else}
                <div class="item right">
                    <a href="index.php?login" class="ui blue button{if $__KT['activepage'] =="login"} active{/if}">Login</a>
                </div>
            {/if}
        </div>
        <div class="ui grid container" id="content">
            <div class="one wide column"></div>
            <div class="fourteen wide column">

