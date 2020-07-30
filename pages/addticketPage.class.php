<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\data\user\notification\Notification;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\data\ticket\category\Category;
use KuschelTickets\lib\recaptcha;
use KuschelTickets\lib\KuschelTickets;
use KuschelTickets\lib\data\ticket\category\CategoryList;

class addticketpage extends Page {

    private $errors;
    private $success = false;

    public function readParameters(Array $parameters) {
        global $config;

        $this->errors = array(
            "title" => false,
            "category" => false,
            "text" => false,
            "token" => false,
            "custominput" => false
        );

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.tickets.add")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(isset($parameters['submit'])) {
            if(recaptcha::validate("addticket")) {
                if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                    if(CRSF::validate($parameters['CRSF'])) {
                        if(isset($parameters['title']) && !empty($parameters['title'])) {
                            if(isset($parameters['category']) && !empty($parameters['category'])) {
                                $category = strip_tags($parameters['category']);
                                $cat = new Category($category);
                                $result = $cat->validateInputs($parameters);
                                $this->errors['custominput'] = $result['errors'];
                                $worked = true;
                                foreach($result['errors'] as $error) {
                                    if($error !== false) {
                                        $worked = false;
                                        break;
                                    }
                                }
                                if($worked == true) {
                                    if(isset($parameters['text']) && !empty($parameters['text'])) {
                                        $categoryCheck = new Category($parameters['category']);
                                        if($categoryCheck->categoryID) {
                                            $text = Utils::purify($parameters['text']);
                                            $customfields = "";
                                            if(isset($result['results'])) {
                                                foreach($result['results'] as $response) {
                                                    $customfields = $customfields.$response;
                                                }
                                            }
                                            if(!empty($text) && $text !== "<p></p>") {
                                                    $text = $customfields.$text;
                                                    $title = strip_tags($parameters['title']);
                                                    $category = $cat->categoryName;
                                                    $stmt = KuschelTickets::getDB()->prepare("INSERT INTO kuscheltickets".KT_N."_tickets(`creator`, `title`, `category`, `content`, `state`, `time`, `color`) VALUES (?, ?, ?, ? , 1, ?, ?)");
                                                    $time = time();
                                                    $color = $cat->color;
                                                    $stmt->execute([KuschelTickets::getUser()->userID, $title, $category, $text, $time, $color]);
                                                    $this->success = true;
                                                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE creator = ? AND time = ? LIMIT 1");
                                                    $stmt->execute([KuschelTickets::getUser()->userID, $time]);
                                                    $r = $stmt->fetch();

                                                    $nonotify = false;
                                                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts");
                                                    $stmt->execute();
                                                    while($row = $stmt->fetch()) {
                                                        $account = new User((int) $row['userID']);
                                                        if($account->hasPermission("mod.view.tickets.list")) {
                                                            Notification::add("notification_ticket_new", "Es wurde ein neues Ticket von ".KuschelTickets::getUser()->username." in der Kategorie ".$cat->categoryName." erstellt.", "ticket-".$r['ticketID'], $account);
                                                            if($account->userID == KuschelTickets::getUser()->userID) {
                                                                $nonotify = true;
                                                            }
                                                        }
                                                    }
                                                    if(!$nonotify) {
                                                        Notification::add("notification_ticket_new", "Es wurde ein neues Ticket von ".KuschelTickets::getUser()->username." in der Kategorie ".$cat->categoryName." erstellt.", "ticket-".$r['ticketID'], $user);
                                                    }
                                                    Utils::redirect(Link::get("ticket-".$r['ticketID']));
                                            } else {
                                                $this->errors['text'] = "Bitte gib einen Text an.";
                                            }
                                        } else {
                                            $this->errros['category'] = "Das ist keine valide Kategorie.";
                                        }
                                    } else {
                                        $this->errros['text'] = "Bitte gib einen Text an.";
                                    }
                                }
                            } else {
                                $this->errors['category'] = "Bitte wähle eine Kategorie.";
                            }
                        } else {
                            $this->errors['title'] = "Bitte gib einen Titel an.";
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

    public function assign() {
        return array(
            "errors" => $this->errors,
            "success" => $this->success,
            "categorys" => new CategoryList(),
            "recaptcha" => recaptcha::build('addticket')
        );
    }


}
?>