<?php
use KuschelTickets\lib\PageHandler;
use KuschelTickets\lib\Page;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\Utils;

require("lib/KuschelTickets.class.php");
require("lib/PageHandler.class.php");
require("lib/Page.class.php");
require("lib/Mailer.class.php");
require("lib/Utils.class.php");
require("lib/Link.class.php");
require("lib/dompdfAdapter.class.php");
require("lib/recaptcha.class.php");
require("lib/system/UserUtils.class.php");
require("lib/system/CRSF.class.php");
require("lib/system/Oauth.class.php");
require("lib/HttpRequest.class.php");
require("lib/system/GitHubOauth.class.php");
require("lib/data/DatabaseObject.class.php");
require("lib/data/DatabaseObjectList.class.php");


Utils::autoload("lib/data/*");

require("lib/exception/AccessDeniedException.class.php");
require("lib/exception/PageNotFoundException.class.php");

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