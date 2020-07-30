<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\data\ticket\TicketList;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\KuschelTickets;

class myticketsPage extends Page {

    private $tickets = [];

    public function readParameters(Array $parameters) {
        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.view.tickets.self")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $this->tickets = new TicketList(array(
            "creator" => KuschelTickets::getUser()->userID
        ));
    }

    public function assign() {
        return array(
            "tickets" => $this->tickets
        );
    }


}
?>