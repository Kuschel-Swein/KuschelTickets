<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\User;
use kt\system\UserUtils;
use kt\data\ticket\TicketList;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;

class myticketsPage extends AbstractPage {

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
        ), "ORDER BY ticketID DESC");
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "tickets" => $this->tickets
        ));
    }


}
?>