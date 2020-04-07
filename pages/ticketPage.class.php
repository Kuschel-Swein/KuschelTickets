<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\Ticket;
use KuschelTickets\lib\system\TicketCategory;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\system\Notification;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\Exceptions\PageNotFoundException;
use KuschelTickets\lib\recaptcha;

class ticketPage extends Page {

    private $ticket;
    private $errors;
    private $success = false;

    public function readParameters(Array $parameters) {
        global $config;

        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.view.ticket.own") && !$user->hasPermission("mod.view.ticket.all")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $ticket = new Ticket($parameters['ticket']);
        if(!$ticket->exists()) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }
        $creator = $ticket->getCreator();
        if(!$user->hasPermission("mod.view.ticket.all")) {
            if($user->userID !== $creator->userID) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
        }
        $this->ticket = $ticket;

        $this->errors = array(
            "text" => false,
            "token" => false
        );

        if(isset($parameters['submit'])) {
            if($user->hasPermission("general.tickets.answer")) {
                if($ticket->getState() == 1) {
                    if(recaptcha::validate("ticketanswer")) {
                        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                            if(CRSF::validate($parameters['CRSF'])) {
                                if(isset($parameters['text']) && !empty($parameters['text'])) {
                                    $text = Utils::purify($parameters['text']);
                                    if(!empty($text) && $text !== "<p></p>") {
                                        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_ticket_answers(`ticketID`, `creator`, `content`, `time`) VALUES (?, ?, ?, ?)");
                                        $time = time();
                                        $stmt->execute([$parameters['ticket'], $user->userID, $text, $time]);
                                        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_answers WHERE time = ? AND creator = ?");
                                        $stmt->execute([$time, $user->userID]);
                                        $r = $stmt->fetch();
                                        $already = [];
                                        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts");
                                        $stmt->execute();
                                        while($row = $stmt->fetch()) {
                                            $account = new User((int) $row['userID']);
                                            if(!$account->hasPermission("mod.view.ticket.all")) {
                                                if($account->userID == $creator->userID) {
                                                    Notification::create("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->getTitle()." von ".$user->getUserName()." in der Kategorie ".$ticket->getCategory()." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$r['answerID'], $account);
                                                    array_push($already, $account->userID);
                                                }
                                            } else {
                                                Notification::create("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->getTitle()." von ".$user->getUserName()." in der Kategorie ".$ticket->getCategory()." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$r['answerID'], $account);
                                                array_push($already, $account->userID);
                                            }
                                        }
                                        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
                                        $stmt->execute([$ticket->ticketID]);
                                        while($row = $stmt->fetch()) {
                                            $account = new User((int) $row['creator']);
                                            if(!$account->hasPermission("mod.view.ticket.all")) {
                                                if($account->userID == $creator->userID) {
                                                    if(!in_array($account->userID, $already)) {
                                                        Notification::create("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->getTitle()." von ".$user->getUserName()." in der Kategorie ".$ticket->getCategory()." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$r['answerID'], $account);
                                                    }
                                                }
                                            } else {
                                                if(!in_array($account->userID, $already)) {
                                                    Notification::create("notification_ticket_answer", "Es wurde eine Antwort im Ticket ".$ticket->getTitle()." von ".$user->getUserName()." in der Kategorie ".$ticket->getCategory()." erstellt.", "ticket-".$ticket->ticketID."#ticketanswer".$r['answerID'], $account);
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