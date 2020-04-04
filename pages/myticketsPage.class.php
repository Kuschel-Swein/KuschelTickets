<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\Exceptions\AccessDeniedException;

class myticketsPage extends Page {

    private $tickets = [];

    public function readParameters(Array $parameters) {
        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.view.tickets.self")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $this->tickets = $user->getTickets();
    }

    public function assign() {
        return array(
            "tickets" => $this->tickets
        );
    }


}
?>