<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\KuschelTickets;

class supportchatPage extends Page {

    public function readParameters(Array $parameters) {
        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.supportchat.view")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
        }

        
    }

    public function assign() {
        return array();
    }


}
?>