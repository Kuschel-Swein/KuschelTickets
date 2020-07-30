<?php
namespace kt\system;
use kt\system\Link;
use kt\data\user\User;
use kt\system\UserUtils;
use kt\system\CRSF;
use kt\system\Oauth;
use kt\data\menu\MenuEntry;
use kt\system\KuschelTickets;

class Page {

    private $identifier = null;
    private $extravariables = []; 

    public function __construct(String $identifier) {
        $this->identifier = $identifier;
    }

    public function assignTPL(Array $assignements) {
        $this->extravariables = $assignements;
    }

    public function show() {
        global $config;
        global $_REQUEST;

        $tpl = array(
            "request" => $_REQUEST,
            "post" => $_POST,
            "cookie" => $_COOKIE,
            "server" => $_SERVER,
            "now" => time()
        );
        KuschelTickets::getTPL()->setTemplateDir("./templates");
        KuschelTickets::getTPL()->setCompileDir("./data/templates_compiled");
        KuschelTickets::getTPL()->setCacheDir("./data/cache");
        KuschelTickets::getTPL()->debugging = $config['debugging']['templates'];
        // we do not cache here because we could have some errors
        KuschelTickets::getTPL()->caching = false;
        foreach($this->extravariables as $key => $value) {
            KuschelTickets::getTPL()->assign($key, $value, false);
        }
        $kt = $config;
        if(UserUtils::isLoggedIn()) {
            $kt['user'] = new User($_SESSION['userID']);
        } else {
            $kt['user'] = null;
        }
        $kt['mainurl'] = Link::mainurl();
        $kt['google_auth_uri'] = Oauth::getGoogleURL();
        $kt['github_auth_uri'] = Oauth::getGitHubURL();
        $kt['topnavigation'] = MenuEntry::buildMenu($this->identifier);
        $kt['CRSF'] = CRSF::get();
        $kt['activepage'] = $this->identifier;
        KuschelTickets::getTPL()->assign("__KT", $kt, true);
        KuschelTickets::getTPL()->assign("tpl", $tpl, true);
        KuschelTickets::getTPL()->display($this->identifier.".tpl");
    }
}