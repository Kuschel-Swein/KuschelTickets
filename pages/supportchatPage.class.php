<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\User;

class supportchatPage extends Page {

    public function readParameters(Array $parameters) {
        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
        }

        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.supportchat.view")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
        }

        
    }

    public function assign() {
        return array();
    }


}
?>