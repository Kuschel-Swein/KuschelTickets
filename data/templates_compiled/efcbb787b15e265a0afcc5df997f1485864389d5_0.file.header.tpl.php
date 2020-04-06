<?php
/* Smarty version 3.1.34-dev-7, created on 2020-04-06 15:19:54
  from 'C:\xampp\htdocs\kuscheltickets\templates\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5e8b2c7a38f796_15297033',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'efcbb787b15e265a0afcc5df997f1485864389d5' => 
    array (
      0 => 'C:\\xampp\\htdocs\\kuscheltickets\\templates\\header.tpl',
      1 => 1586179191,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e8b2c7a38f796_15297033 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\kuscheltickets\\lib\\smarty\\plugins\\function.link.php','function'=>'smarty_function_link',),));
?>
<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['__KT']->value['pagetitle'];?>
</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['faviconmime'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
data/favicon.<?php echo $_smarty_tpl->tpl_vars['__KT']->value['faviconextension'];?>
">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap">
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/semantic.min.css">
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/toast.css">     
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/master.css">    
        <?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/semantic.min.js" type="text/javascript"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
assets/toast.js" type="text/javascript"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
>
            const kuscheltickets_version = "<?php echo $_smarty_tpl->tpl_vars['__KT']->value['version'];?>
";
            const notifications_link = "<?php echo smarty_function_link(array('url'=>"notifications"),$_smarty_tpl);?>
";
            const KT = {
                <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] == null) {?>
                userID: null,
                <?php } else { ?>
                userID: <?php echo $_smarty_tpl->tpl_vars['__KT']->value['user']->userID;?>
,
                <?php }?>
                mainurl: "<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
",
                seourls: <?php echo $_smarty_tpl->tpl_vars['__KT']->value['seourls'];?>
,
                externalURLFavicons: <?php echo $_smarty_tpl->tpl_vars['__KT']->value['externalURLFavicons'];?>
,
                externalURLWarning: <?php echo $_smarty_tpl->tpl_vars['__KT']->value['externalURLWarning'];?>
,
                pushNotificationsAvailable: false,
                localStorage: null,
                pagetitle: "<?php echo $_smarty_tpl->tpl_vars['__KT']->value['pagetitle'];?>
",
                faviconextension: "<?php echo $_smarty_tpl->tpl_vars['__KT']->value['faviconextension'];?>
",
                externalURLTitle: <?php echo $_smarty_tpl->tpl_vars['__KT']->value['externalURLTitle'];?>
,
                proxyAllImages: <?php echo $_smarty_tpl->tpl_vars['__KT']->value['proxyAllImages'];?>
,
                useDesktopNotification: <?php echo $_smarty_tpl->tpl_vars['__KT']->value['useDesktopNotification'];?>

            };
        <?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['__KT']->value['mainurl'];?>
/assets/master.js" type="text/javascript"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
>
            KT.userTemplates = ajax.call(19, 1)['message'];
        <?php echo '</script'; ?>
>
    </head>
    <body id="main"> 
        <div class="ui mobile only padded grid">
            <div class="ui top fixed huge fluid menu">
                <div class="header item"><?php echo $_smarty_tpl->tpl_vars['__KT']->value['pagetitle'];?>
</div>
                    <div class="right menu">
                        <div class="item">
                            <button class="ui icon toggle basic button">
                                <i class="content icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="ui vertical fluid menu">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['__KT']->value['topnavigation'], 'link');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['link']->value) {
?>
                        <?php if ($_smarty_tpl->tpl_vars['link']->value['permission'] !== null) {?>
                            <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] !== null) {?>
                                <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission($_smarty_tpl->tpl_vars['link']->value['permission'])) {?>
                                    <a href="<?php echo smarty_function_link(array('url'=>$_smarty_tpl->tpl_vars['link']->value['href']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['__KT']->value['activepage'] == $_smarty_tpl->tpl_vars['link']->value['identifier']) {?> active<?php }
if ($_smarty_tpl->tpl_vars['link']->value['right']) {?> right<?php }?>"><?php echo $_smarty_tpl->tpl_vars['link']->value['text'];?>
</a>
                                <?php }?>
                            <?php }?>
                        <?php } else { ?>
                            <a href="<?php echo smarty_function_link(array('url'=>$_smarty_tpl->tpl_vars['link']->value['href']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['__KT']->value['activepage'] == $_smarty_tpl->tpl_vars['link']->value['identifier']) {?> active<?php }
if ($_smarty_tpl->tpl_vars['link']->value['right']) {?> right<?php }?>"><?php echo $_smarty_tpl->tpl_vars['link']->value['text'];?>
</a>
                        <?php }?>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] !== null && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.notifications.view")) {?>
                        <a href="<?php echo smarty_function_link(array('url'=>"notifications"),$_smarty_tpl);?>
" class="item">Benachrichtigungen <span class="ui red label notificationbadgehandler"></span></a>
                    <?php }?>
                    <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] !== null) {?>
                        <a href="<?php echo smarty_function_link(array('url'=>"logout/token-".((string)$_smarty_tpl->tpl_vars['__KT']->value['CRSF'])),$_smarty_tpl);?>
" class="item">Logout</a>
                    <?php } else { ?>
                        <a href="<?php echo smarty_function_link(array('url'=>"login"),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['__KT']->value['activepage'] == "login") {?> active<?php }?>">Login</a>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="ui tablet computer only grid menu top fixed">
            <div class="item">
                <h2><?php echo $_smarty_tpl->tpl_vars['__KT']->value['pagetitle'];?>
</h2>
            </div>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['__KT']->value['topnavigation'], 'link');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['link']->value) {
?>
                <?php if ($_smarty_tpl->tpl_vars['link']->value['permission'] !== null) {?>
                    <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] !== null) {?>
                        <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission($_smarty_tpl->tpl_vars['link']->value['permission'])) {?>
                            <a href="<?php echo smarty_function_link(array('url'=>$_smarty_tpl->tpl_vars['link']->value['href']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['__KT']->value['activepage'] == $_smarty_tpl->tpl_vars['link']->value['identifier']) {?> active<?php }
if ($_smarty_tpl->tpl_vars['link']->value['right']) {?> right<?php }?>"><?php echo $_smarty_tpl->tpl_vars['link']->value['text'];?>
</a>
                        <?php }?>
                    <?php }?>
                <?php } else { ?>
                    <a href="<?php echo smarty_function_link(array('url'=>$_smarty_tpl->tpl_vars['link']->value['href']),$_smarty_tpl);?>
" class="item<?php if ($_smarty_tpl->tpl_vars['__KT']->value['activepage'] == $_smarty_tpl->tpl_vars['link']->value['identifier']) {?> active<?php }
if ($_smarty_tpl->tpl_vars['link']->value['right']) {?> right<?php }?>"><?php echo $_smarty_tpl->tpl_vars['link']->value['text'];?>
</a>
                <?php }?>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] !== null && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.notifications.view")) {?>
                <div class="item right">
                    <div id="notificationsbell" class="pointer" data-position="bottom center">
                        <i class="icon bell"></i>
                        <div class="floating ui tiny red label notificationbadge notificationbadgehandler"></div>
                    </div>
                </div>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] !== null) {?>
                <div class="item right" <?php if ($_smarty_tpl->tpl_vars['__KT']->value['user'] !== null && $_smarty_tpl->tpl_vars['__KT']->value['user']->hasPermission("general.notifications.view")) {?>style="margin-left: 0!important"<?php }?>>
                    <a href="<?php echo smarty_function_link(array('url'=>"logout/token-".((string)$_smarty_tpl->tpl_vars['__KT']->value['CRSF'])),$_smarty_tpl);?>
" class="ui blue button">Logout</a>
                </div>
            <?php } else { ?>
                <div class="item right">
                    <a href="<?php echo smarty_function_link(array('url'=>"login"),$_smarty_tpl);?>
" class="ui blue button<?php if ($_smarty_tpl->tpl_vars['__KT']->value['activepage'] == "login") {?> active<?php }?>">Login</a>
                </div>
            <?php }?>
        </div>
        <div class="ui grid container" id="content">
            <div class="one wide column"></div>
            <div class="fourteen wide column">

<?php }
}
