<?php
namespace KuschelTickets\lib;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\CRSF;

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
        global $templateengine;
        global $_REQUEST;

        $tpl = array(
            "request" => $_REQUEST,
            "post" => $_POST,
            "cookie" => $_COOKIE,
            "server" => $_SERVER,
            "now" => time()
        );
        $templateengine->setTemplateDir("./templates");
        $templateengine->setCompileDir("./data/templates_compiled");
        $templateengine->setCacheDir("./data/cache");
        $templateengine->debugging = $config['debugging']['templates'];
        // we do not cache here because we could have some errors
        $templateengine->caching = false;
        foreach($this->extravariables as $key => $value) {
            $templateengine->assign($key, $value, false);
        }
        $kt = $config;
        if(UserUtils::isLoggedIn()) {
            $kt['user'] = new User($_SESSION['userID']);
        } else {
            $kt['user'] = null;
        }
        $data = DATA;
        $kt['topnavigation'] = $data['topnavigation'];
        $kt['CRSF'] = CRSF::get();
        $kt['activepage'] = $this->identifier;
        $templateengine->assign("__KT", $kt, true);
        $templateengine->assign("tpl", $tpl, true);
        $templateengine->display($this->identifier.".tpl");
    }
}