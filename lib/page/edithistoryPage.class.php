<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\exception\PageNotFoundException;
use kt\system\exception\AccessDeniedException;
use kt\data\ticket\Ticket;
use kt\data\ticket\answer\Answer;
use kt\system\KuschelTickets;

class edithistoryPage extends AbstractPage {

    private $object;

    public function readParameters(Array $parameters) {
        if(!KuschelTickets::getUser()->hasPermission("mod.tickets.edithistory")) {
            throw new AccessDeniedException();
        }
        if(!isset($parameters['edithistory']) || empty($parameters['edithistory'])) {
            throw new PageNotFoundException();
        }
        $ticket = new Ticket($parameters['edithistory']);
        if(!$ticket->ticketID) {
            throw new PageNotFoundException();
        }
        if(!KuschelTickets::getUser()->hasPermission("general.view.ticket.own") && !KuschelTickets::getUser()->hasPermission("mod.view.ticket.all")) {
            throw new AccessDeniedException();
        }
        if(!KuschelTickets::getUser()->hasPermission("mod.view.ticket.all")) {
            if(KuschelTickets::getUser()->userID !== $ticket->creator) {
                throw new AccessDeniedException();
            }
        }
        $this->object = $ticket;
        if(isset($parameters['answer']) && !empty($parameters['answer'])) {
            $answer = new Answer($parameters['answer']);
            if(!$answer->answerID) {
                throw new PageNotFoundException();
            }
            if($answer->ticketID !== $ticket->ticketID) {
                throw new PageNotFoundException();
            }
            $this->object = $answer;
        }
        if(!method_exists($this->object, "getChanges") || !method_exists($this->object, "getCreator")) {
            throw new PageNotFoundException();
        }
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "object" => $this->object
        ));
    }
}