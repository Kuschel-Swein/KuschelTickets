<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\Ticket;
use KuschelTickets\lib\Exceptions\AccessDeniedException;

class ticketsPage extends Page {

    private $tickets = [];

    public function readParameters(Array $parameters) {
        global $config;

        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("mod.view.tickets.list")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $tickets = [];
        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets");
        $stmt->execute();
        while($row = $stmt->fetch()) {
            array_push($tickets, new Ticket((int) $row['ticketID']));
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