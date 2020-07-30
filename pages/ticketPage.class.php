<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\KuschelTickets;
use KuschelTickets\lib\data\ticket\Ticket;
use KuschelTickets\lib\data\ticket\answer\Answer;
use KuschelTickets\lib\data\user\UserList;
use KuschelTickets\lib\data\ticket\category\Category;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\data\user\notification\Notification;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\exception\PageNotFoundException;
use KuschelTickets\lib\recaptcha;

class ticketPage extends Page {

    private $ticket;
    private $errors;
    private $success = false;

    public function readParameters(Array $parameters) {
        global $config;

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        if(!KuschelTickets::getUser()->hasPermission("general.view.ticket.own") && !KuschelTickets::getUser()->hasPermission("mod.view.ticket.all")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $ticket = new Ticket($parameters['ticket']);
        if(!$ticket->ticketID) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }
        $creator = $ticket->getCreator();
        if(!KuschelTickets::getUser()->hasPermission("mod.view.ticket.all")) {
            if(KuschelTickets::getUser()->userID !== $creator->userID) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
        }
        $this->ticket = $ticket;

        $this->errors = array(
            "text" => false,
            "token" => false
        );

        if(isset($parameters['submit'])) {
            if(KuschelTickets::getUser()->hasPermission("general.tickets.answer")) {
                if($ticket->state == 1) {
                    if(recaptcha::validate("ticketanswer")) {
                        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                            if(CRSF::validate($parameters['CRSF'])) {
                                if(isset($parameters['text']) && !empty($parameters['text'])) {
                                    $text = Utils::purify($parameters['text']);
                                    if(!empty($text) && $text !== "<p></p>") {
                                        $answer = Answer::create(array(
                                            "ticketID" => (int) $parameters['ticket'],
                                            "creator" => KuschelTickets::getUser()->userID,
                                            "content" => $text,
                                            "time" => time()
                                        ));
                                        $already = [];
                                        $allUsers = new UserList();
                                        foreach($allUsers as $account) {
                                            if(!$account->hasPermission("mod.view.ticket.all")) {
                                                if($account->userID == $creator->userID) {
                                                    Notification::add("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->title." von ".KuschelTickets::getUser()->username." in der Kategorie ".$ticket->getCategory()->categoryName." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$answer->answerID, $account);
                                                    array_push($already, $account->userID);
                                                }
                                            } else {
                                                Notification::add("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->title." von ".KuschelTickets::getUser()->username." in der Kategorie ".$ticket->getCategory()->categoryName." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$answer->answerID, $account);
                                                array_push($already, $account->userID);
                                            }
                                        }
                                        $answers = $ticket->getAnswers();
                                        foreach($answers as $ticketAnswer) {
                                            $account = $ticketAnswer->getCreator();
                                            if(!$account->hasPermission("mod.view.ticket.all")) {
                                                if($account->userID == $creator->userID) {
                                                    if(!in_array($account->userID, $already)) {
                                                        Notification::add("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->title." von ".KuschelTickets::getUser()->username." in der Kategorie ".$ticket->getCategory()->categoryName." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$answer->answerID, $account);
                                                    }
                                                }
                                            } else {
                                                if(!in_array($account->userID, $already)) {
                                                    Notification::add("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->title." von ".KuschelTickets::getUser()->username." in der Kategorie ".$ticket->getCategory()->categoryName." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$answer->answerID, $account);
                                                }
                                            }
                                        }
                                        $this->success = true;
                                    } else {
                                        $this->errors['text'] = "Bitte gib einen Text an.";
                                    }
                                } else {
                                    $this->errors['text'] = "Bitte gib einen Text an.";
                                }
                            } else {
                                $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                            }
                        } else {
                            $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                        }
                    } else {
                        $this->errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
                    }    
                }
            }
        }
    }

    public function assign() {
        return array(
            "ticket" => $this->ticket,
            "errors" => $this->errors,
            "success" => $this->success,
            "recaptcha" => recaptcha::build("ticketanswer")
        );
    }


}
?>