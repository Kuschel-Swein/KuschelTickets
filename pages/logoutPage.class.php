<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\system\CRSF;

class logoutPage extends Page {

    private $result;

    public function readParameters(Array $parameters) {
        if(isset($parameters['token']) && !empty($parameters['token'])) {
            if(CRSF::validate($parameters['token'])) {
                session_destroy();
                Utils::redirect(Link::get("login"));
                $this->result = "Du wurdest erfolgreich ausgeloggt.";
            } else {
                $this->result = "Dein CRSF Token stimmt nicht überein, bitte lade die Seite neu.";
            }
        } else {
            $this->result = "Dein CRSF Token stimmt nicht überein, bitte lade die Seite neu.";
        }
    }

    public function assign() {
        return array(
            "result" => $this->result
        );
    }


}
?>