<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\Utils;
use kt\system\Link;
use kt\system\UserUtils;
use kt\data\ticket\TicketList;
use kt\system\KuschelTickets;

class IndexPage extends AbstractPage {

    public function readParameters(Array $parameters) {
        if(!KuschelTickets::getUser()->userID) {
            Utils::redirect(Link::get("login"));
        }
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "tickets" => new TicketList(array(
                "creator" => KuschelTickets::getUser()->userID
            ), "ORDER BY ticketID DESC", 10)
        ));
    }


}
?>