<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\TicketCategory;
use KuschelTickets\lib\recaptcha;

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

        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.tickets.add")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(isset($parameters['submit'])) {
           
            if(recaptcha::validate("addticket")) {
                if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                    if(CRSF::validate($parameters['CRSF'])) {
                        if(isset($parameters['title']) && !empty($parameters['title'])) {
                            if(isset($parameters['category']) && !empty($parameters['category'])) {
                                $category = strip_tags($parameters['category']);
                                $cat = new TicketCategory($category);
                                $result = $cat->validateInputs($parameters);
                                $this->errors['custominput'] = $result['errors'];
                                $worked = true;
                                foreach($result['errors'] as $error) {
                                    if($error !== false) {
                                        $worked = false;
                                        break;
                                    }
                                }
                                if($worked) {
                                    if(isset($parameters['text']) && !empty($parameters['text'])) {
                                        $categorys = Utils::getCategorys();
                                        $category_exists = false;
                                        foreach($categorys as $category) {
                                            if($category->categoryID == $parameters['category']) {
                                                $category_exists = true;
                                                break;
                                            }
                                        }
                                        if($category_exists) {
                                            $text = Utils::purify($parameters['text']);
                                            $customfields = "";
                                            foreach($result['results'] as $response) {
                                                $customfields = $customfields.$response;
                                            }
                                            if(!empty($text)) {
                                                    $text = $customfields.$text;
                                                    $title = strip_tags($parameters['title']);
                                                    $category = $cat->getName();
                                                    $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_tickets(`creator`, `title`, `category`, `content`, `state`, `time`) VALUES (?, ?, ?, ? , 1, ?)");
                                                    $time = time();
                                                    $stmt->execute([$user->userID, $title, $category, $text, $time]);
                                                    $this->success = true;
                                                    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE creator = ? AND time = ? LIMIT 1");
                                                    $stmt->execute([$user->userID, $time]);
                                                    $row = $stmt->fetch();
                                                    Utils::redirect("index.php?ticket-".$row['ticketID']);
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
            "categorys" => Utils::getCategorys(),
            "recaptcha" => recaptcha::build('addticket')
        );
    }


}
?>