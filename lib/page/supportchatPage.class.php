<?php
namespace kt\page;

use kt\system\Page;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;

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