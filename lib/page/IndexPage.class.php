<?php
namespace kt\page;

use kt\system\Page;
use kt\system\Utils;
use kt\system\Link;
use kt\system\UserUtils;
use kt\data\ticket\TicketList;
use kt\system\KuschelTickets;

class IndexPage extends Page {

    public function readParameters(Array $parameters) {
        if(!KuschelTickets::getUser()->userID) {
            Utils::redirect(Link::get("login"));
        }
    }

    public function assign() {
        return array(
            "tickets" => new TicketList(array(
                "creator" => KuschelTickets::getUser()->userID
            ), "ORDER BY ticketID DESC", 10)
        );
    }


}
?>