<?php
use KuschelTickets\lib\PageHandler;
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\Utils;

require("lib/PageHandler.class.php");
require("lib/Page.class.php");
require("lib/Mailer.class.php");
require("lib/Utils.class.php");
require("lib/Link.class.php");
require("lib/recaptcha.class.php");
require("lib/system/User.class.php");
require("lib/system/Group.class.php");
require("lib/system/UserUtils.class.php");
require("lib/system/CRSF.class.php");
require("lib/system/FAQ.class.php");
require("lib/system/Notification.class.php");
require("lib/system/PageContent.class.php");
require("lib/system/Ticket.class.php");
require("lib/system/TicketCategory.class.php");

require("lib/exceptions/AccessDeniedException.class.php");
require("lib/exceptions/PageNotFoundException.class.php");

if(!file_exists("config.php") || !file_exists("./data/INSTALLED")) {
    Utils::redirect("install.php");
    die("Weiterleitung zum <a href='install.php'>Installer</a>");
}

function error($error) {
    return Utils::error($error);
}

set_exception_handler("error");

require("lib/smarty/Smarty.class.php");
require("config.php");
require("data/data.php");

error_reporting(0);
if($config['debugging']['php']) {
    error_reporting(-1);
}
if($config['debugging']['database']) {
    $config['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$templateengine = new Smarty();

session_name($config['cookie']);
session_start();
if(UserUtils::isLoggedIn()) {
    $user = new User($_SESSION['userID']);
    $user->validateHash();
}


new PageHandler();

?>