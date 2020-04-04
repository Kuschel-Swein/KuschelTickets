<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\Group;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\Ticket;
use KuschelTickets\lib\system\TicketCategory;

class ajaxPage extends Page {

    public function readParameters(Array $parameters) {
        global $config;

        header("Content-Type: application/json");
        $result = array(
            "code" => "404",
            "message" => "specified type was not found"
        );
        $type = $parameters['ajax'];
        /**
         * 1 => close ticket
         * 2 => reopen ticket
         * 3 => mark ticket as done
         * 4 => debuging helper
         * 5 => delete answer
         * 6 => delete ticket
         * 7 => [ADMIN] delete faq
         * 8 => [ADMIN] delete faq category
         * 9 => [ADMIN] delete page
         * 10 => [ADMIN] delete user
         * 11 => [ADMIN] delete group
         */
        if($type == 1) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $ticket = new Ticket($parameters['object']);
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("mod.tickets.close") && $ticket->getState() == "1") {
                        $ticket->addLog("Das Ticket wurde von ".$user->getUserName()." geschlossen.");
                        $ticket->setState(0);
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich geschlossen.",
                            "title" => "Ticket geschlossen"
                        );
                    } else if($user->hasPermission("general.tickets.close.own") && $ticket->getCreator()->userID == $user->userID && $ticket->getState() == "1") {
                        $ticket->addLog("Das Ticket wurde von ".$user->getUserName()." geschlossen.");
                        $ticket->setState(0);
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich geschlossen.",
                            "title" => "Ticket geschlossen"
                        );
                    } else {
                        $result = array(
                            "code" => "403",
                            "message" => "access denied"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 2) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $ticket = new Ticket($parameters['object']);
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("mod.tickets.reopen") && $ticket->getState() !== "1") {
                        $ticket->addLog("Das Ticket wurde von ".$user->getUserName()." erneut geöffnet.");
                        $ticket->setState(1);
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich erneut geöffnet.",
                            "title" => "Ticket erneut geöffnet"
                        );
                    } else if($user->hasPermission("general.tickets.reopen.own") && $ticket->getCreator()->userID == $user->userID && $ticket->getState() !== "1") {
                        $ticket->addLog("Das Ticket wurde von ".$user->getUserName()." erneut geöffnet.");
                        $ticket->setState(1);
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich erneut geöffnet.",
                            "title" => "Ticket erneut geöffnet"
                        );
                    } else {
                        $result = array(
                            "code" => "403",
                            "message" => "access denied"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 3) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $ticket = new Ticket($parameters['object']);
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("mod.tickets.done") && $ticket->getState() == "1") {
                        $ticket->addLog("Das Ticket wurde von ".$user->getUserName()." als erledigt markiert.");
                        $ticket->setState(2);
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich als erledigt markiert.",
                            "title" => "Ticket als erledigt markiert."
                        );
                    } else if($user->hasPermission("general.tickets.done.own") && $ticket->getCreator()->userID == $user->userID && $ticket->getState() == "1") {
                        $ticket->addLog("Das Ticket wurde von ".$user->getUserName()." als erledigt markiert.");
                        $ticket->setState(2);
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich als erledigt markiert.",
                            "title" => "Ticket als erledigt markiert."
                        );
                    } else {
                        $result = array(
                            "code" => "403",
                            "message" => "access denied"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 4) {
            if(UserUtils::isLoggedIn()) {
                $result = array(
                    "version" => $config['version'],
                    "debugging" => $config['debugging'],
                    "software" => "KuschelTickets",
                    "creator" => "Kuschel_Swein",
                    "link" => "https://github.com/Kuschel-Swein"
                );
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 5) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    $stmt = $config['db']->prepare("SELECT * FROM `kuscheltickets1_ticket_answers` WHERE answerID = ?");
                    $stmt->execute([$parameters['object']]);
                    $row = $stmt->fetch();
                    $creator = $row['creator'];
                    if($user->hasPermission("mod.tickets.answers.delete")) {
                        $stmt = $config['db']->prepare("DELETE FROM `kuscheltickets1_ticket_answers` WHERE answerID = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Diese Antwort wurde erfolgreich gelöscht.",
                            "title" => "Antwort gelöscht"
                        );
                    } else if($user->hasPermission("general.tickets.answers.delete.own") && $user->userID == $creator) {
                        $stmt = $config['db']->prepare("DELETE FROM `kuscheltickets1_ticket_answers` WHERE answerID = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Diese Antwort wurde erfolgreich gelöscht.",
                            "title" => "Antwort gelöscht"
                        );
                    } else {
                        $result = array(
                            "code" => "403",
                            "message" => "access denied"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 6) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    $ticket = new Ticket($parameters['object']);
                    $creator = $ticket->getCreator()->userID;
                    if($user->hasPermission("mod.tickets.delete")) {
                        $stmt = $config['db']->prepare("DELETE FROM `kuscheltickets1_tickets` WHERE ticketID = ?");
                        $stmt->execute([$parameters['object']]);
                        $stmt = $config['db']->prepare("DELETE FROM `kuscheltickets1_ticket_answers` WHERE ticketID = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Dieses Ticket wurde erfolgreich gelöscht.",
                            "title" => "Ticket gelöscht"
                        );
                    } else if($user->hasPermission("general.tickets.delete.own") && $user->userID == $creator) {
                        $stmt = $config['db']->prepare("DELETE FROM `kuscheltickets1_tickets` WHERE ticketID = ?");
                        $stmt->execute([$parameters['object']]);
                        $stmt = $config['db']->prepare("DELETE FROM `kuscheltickets1_ticket_answers` WHERE ticketID = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Dieses Ticket wurde erfolgreich gelöscht.",
                            "title" => "Ticket gelöscht"
                        );
                    } else {
                        $result = array(
                            "code" => "403",
                            "message" => "access denied"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 7) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("admin.acp.page.faq")) {
                        $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_faq WHERE faqID = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Dieser FAQ Eintrag wurde erfolgreich gelöscht.",
                            "title" => "FAQ gelöscht"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 8) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("admin.acp.page.faqcategories")) {
                        $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_faq_categorys WHERE categoryID = ?");
                        $stmt->execute([$parameters['object']]);
                        $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_faq WHERE category = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Diese Kategorie wurde erfolgreich gelöscht.",
                            "title" => "Kategorie gelöscht"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 9) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("admin.acp.page.pages")) {
                        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE pageID = ?");
                        $stmt->execute();
                        $row = $stmt->fetch();
                        if($row['system'] !== "1") {
                            $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_pages WHERE pageID = ?");
                            $stmt->execute([$parameters['object']]);
                            $result = array(
                                "success" => true,
                                "message" => "Diese Seite wurde erfolgreich gelöscht.",
                                "title" => "Seite gelöscht"
                            );
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 10) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("admin.acp.page.accounts")) {
                        $account = new User($parameters['object']);
                        if(!$account->hasPermission("admin.bypass.delete") && $parameters['object'] !== $user->userID && $account->exists()) {
                            $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_tickets WHERE creator = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE creator = ?");
                            $stmt->execute([$parameters['object']]);
                            $result = array(
                                "success" => true,
                                "message" => "Dieser Benutzer wurde erfolgreich gelöscht.",
                                "title" => "Benutzer gelöscht"
                            );
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 11) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("admin.acp.page.groups")) {
                        $group = new Group($parameters['object']);
                        if(!$group->isSystem() && $group->exists()) {
                            $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `userGroup`= 0 WHERE userGroup = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_group_permissions WHERE groupID = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_groups WHERE groupID = ?");
                            $stmt->execute([$parameters['object']]);
                            $result = array(
                                "success" => true,
                                "message" => "Diese Gruppe wurde erfolgreich gelöscht.",
                                "title" => "Gruppe gelöscht"
                            );
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 12) {
            if(UserUtils::isLoggedIn()) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User(UserUtils::getUserID());
                    if($user->hasPermission("admin.acp.page.ticketcategories")) {
                        $category = new TicketCategory($parameters['object']);
                        if($category->exists()) {
                            $stmt = $config['db']->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
                            $stmt->execute([$parameters['object']]);
                            $result = array(
                                "success" => true,
                                "message" => "Diese Ticket Kategorie wurde erfolgreich gelöscht.",
                                "title" => "Ticket Kategorie gelöscht"
                            );
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        }

        die(json_encode($result, JSON_PRETTY_PRINT));
    }

    public function assign() {
        return array();
    }


}
?>