<?php
namespace kt\page;

use kt\system\Page;
use kt\data\user\User;
use kt\data\user\group\Group;
use kt\system\UserUtils;
use kt\data\ticket\Ticket;
use kt\system\Utils;
use kt\data\faq\FAQ;
use kt\data\user\notification\NotificationList;
use kt\system\CRSF;
use kt\data\user\notification\Notification;
use kt\data\supportchat\SupportChat;
use kt\data\menu\MenuEntry;
use kt\data\Page\Page as UserPage;
use kt\system\KuschelTickets;

class ajaxPage extends Page {

    public function readParameters(Array $parameters) {
        global $config;

        // set the error reporting to nothing in any case
        error_reporting(0);

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
         * 4 => [ADMIN] log in as user
         * 5 => delete answer
         * 6 => delete ticket
         * 7 => [ADMIN] delete faq
         * 8 => [ADMIN] delete faq category
         * 9 => [ADMIN] delete page
         * 10 => [ADMIN] delete user
         * 11 => [ADMIN] delete group
         * 12 => [ADMIN] delete ticketcategory
         * 13 => mark notification done
         * 14 => get all not done notifications
         * 15 => [ADMIN] watch error
         * 16 => [ADMIN] delete error
         * 17 => delete editor template
         * 18 => watch editor template
         * 19 => get all editor templates from active user
         * 20 => get title of an given website
         * 21 => get faq content of given id
         * 22 => check if notification was sent
         * 23 => mark notification as sent
         * 24 => get all open chats
         * 25 => join chat (user)
         * 26 => load chat messages
         * 27 => create chat message
         * 28 => leave chat
         * 29 => open chat
         * 30 => [ADMIN] delete menuentry
         * 31 => [ADMIN] sort menuentries
         * 32 => rate ticket
         */
        if($type == 1) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $ticket = new Ticket($parameters['object']);
                    
                    if(KuschelTickets::getUser()->hasPermission("mod.tickets.close") && $ticket->state == "1") {
                        $ticket->addLog("Das Ticket wurde von ".KuschelTickets::getUser()->username." geschlossen.");
                        $ticket->update(array(
                            "state" => 0
                        ));
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich geschlossen.",
                            "title" => "Ticket geschlossen"
                        );
                    } else if(KuschelTickets::getUser()->hasPermission("general.tickets.close.own") && $ticket->getCreator()->userID == KuschelTickets::getUser()->userID && $ticket->state == "1") {
                        $ticket->addLog("Das Ticket wurde von ".KuschelTickets::getUser()->username." geschlossen.");
                        $ticket->update(array(
                            "state" => 0
                        ));
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $ticket = new Ticket($parameters['object']);
                    
                    if(KuschelTickets::getUser()->hasPermission("mod.tickets.reopen") && $ticket->state !== 1) {
                        $ticket->addLog("Das Ticket wurde von ".KuschelTickets::getUser()->username." erneut geöffnet.");
                        $ticket->update(array(
                            "state" => 1,
                            "rating" => null
                        ));
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich erneut geöffnet.",
                            "title" => "Ticket erneut geöffnet"
                        );
                    } else if(KuschelTickets::getUser()->hasPermission("general.tickets.reopen.own") && $ticket->getCreator()->userID == KuschelTickets::getUser()->userID && $ticket->state !== 1) {
                        $ticket->addLog("Das Ticket wurde von ".KuschelTickets::getUser()->username." erneut geöffnet.");
                        $ticket->update(array(
                            "state" => 1
                        ));
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $ticket = new Ticket($parameters['object']);
                    
                    if(KuschelTickets::getUser()->hasPermission("mod.tickets.done") && $ticket->state == 1) {
                        $ticket->addLog("Das Ticket wurde von ".KuschelTickets::getUser()->username." als erledigt markiert.");
                        $ticket->update(array(
                            "state" => 2
                        ));
                        $result = array(
                            "success" => true,
                            "message" => "Das Ticket wurde erfolgreich als erledigt markiert.",
                            "title" => "Ticket als erledigt markiert."
                        );
                    } else if(KuschelTickets::getUser()->hasPermission("general.tickets.done.own") && $ticket->getCreator()->userID == KuschelTickets::getUser()->userID && $ticket->state == 1) {
                        $ticket->addLog("Das Ticket wurde von ".KuschelTickets::getUser()->username." als erledigt markiert.");
                        $ticket->update(array(
                            "state" => 2
                        ));
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    $account = new User($parameters['object']);
                    if(KuschelTickets::getUser()->hasPermission("admin.login.other") && $parameters['object'] !== KuschelTickets::getUser()->userID) {
                        if(!$account->hasPermission("admin.bypass.login.other")) {
                            UserUtils::loginAs($account, $account->getHash());
                            $result = array(
                                "success" => true,
                                "message" => "Du wurdest erfolgreich als ".$account->username." eingeloggt. Lade die Seite neu damit die Änderungen sichtbar werden.",
                                "title" => "als Benutzer eingeloggt"
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
        } else if($type == 5) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_answers WHERE answerID = ?");
                    $stmt->execute([$parameters['object']]);
                    $row = $stmt->fetch();
                    $creator = $row['creator'];
                    if(KuschelTickets::getUser()->hasPermission("mod.tickets.answers.delete")) {
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE answerID = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Diese Antwort wurde erfolgreich gelöscht.",
                            "title" => "Antwort gelöscht"
                        );
                    } else if(KuschelTickets::getUser()->hasPermission("general.tickets.answers.delete.own") && KuschelTickets::getUser()->userID == $creator) {
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE answerID = ?");
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    $ticket = new Ticket($parameters['object']);
                    $creator = $ticket->getCreator()->userID;
                    if(KuschelTickets::getUser()->hasPermission("mod.tickets.delete")) {
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
                        $stmt->execute([$parameters['object']]);
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
                        $stmt->execute([$parameters['object']]);
                        $result = array(
                            "success" => true,
                            "message" => "Dieses Ticket wurde erfolgreich gelöscht.",
                            "title" => "Ticket gelöscht"
                        );
                    } else if(KuschelTickets::getUser()->hasPermission("general.tickets.delete.own") && KuschelTickets::getUser()->userID == $creator) {
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
                        $stmt->execute([$parameters['object']]);
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.faq")) {
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_faq WHERE faqID = ?");
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.faqcategories")) {
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_faq_categories WHERE categoryID = ?");
                        $stmt->execute([$parameters['object']]);
                        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_faq WHERE category = ?");
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.pages")) {
                        $userpage = new UserPage($parameters['object']);
                        if($userpage->system !== 1) {
                            $userpage->delete();
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.accounts")) {
                        $account = new User($parameters['object']);
                        if(!$account->hasPermission("admin.bypass.delete") && $parameters['object'] !== KuschelTickets::getUser()->userID && $account->userID) {
                            $account->delete();
                            $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_tickets WHERE creator = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE creator = ?");
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.groups")) {
                        $group = new Group($parameters['object']);
                        if(!$group->system == 0 && $group->groupID) {
                            $stmt = KuschelTickets::getDB()->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `userGroup`= 0 WHERE userGroup = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_group_permissions WHERE groupID = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_groups WHERE groupID = ?");
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
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.ticketcategories")) {
                        $category = new \kt\data\ticket\category\Category($parameters['object']);
                        if($category->categoryID) {
                            $category->delete();
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
        } else if($type == 13) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.notifications.view")) {
                        $notification = new Notification($parameters['object']);
                        if($notification->notificationID) {
                            if($notification->getUser()->userID == KuschelTickets::getUser()->userID && $notification->done !== 1) {
                                $notification->update(array(
                                    "done" => 1
                                ));
                                $result = array(
                                    "success" => true,
                                    "message" => "Diese Benachrichtigung wurde als abgeschlossen markiert.",
                                    "title" => "Benachrichtigung abgeschlossen"
                                );
                            }
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 14) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    $user = new User($parameters['object']);
                    if($user->userID) {
                        if($user->userID == KuschelTickets::getUser()->userID) {
                            if(KuschelTickets::getUser()->hasPermission("general.notifications.view")) {
                                $list = new NotificationList(array(
                                    "userID" => KuschelTickets::getUser()->userID,
                                    "done" => 0
                                ));
                                $result = array(
                                    "success" => true,
                                    "message" => $list->getObjects(),
                                    "title" => null
                                );
                            } else {
                                $result = array(
                                    "code" => "403",
                                    "message" => "access denied"
                                ); 
                            }
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 15) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.errors")) {
                        if(file_exists("./data/logs/".$parameters['object'].".txt")) {
                            $content = file_get_contents("./data/logs/".$parameters['object'].".txt");
                            $result = array(
                                "success" => true,
                                "message" => $content,
                                "title" => null
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
        } else if($type == 16) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.errors")) {
                        if(file_exists("./data/logs/".$parameters['object'].".txt")) {
                            unlink("./data/logs/".$parameters['object'].".txt");
                            $result = array(
                                "success" => true,
                                "message" => "Dieses Fehler Protokoll wurde erfolgreich gelöscht.",
                                "title" => "Fehlerprotokoll gelöscht"
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
        } else if($type == 17) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.editor.templates")) {
                        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE userID = ? AND templateID = ?");
                        $stmt->execute([KuschelTickets::getUser()->userID, $parameters['object']]);
                        $row = $stmt->fetch();
                        if($row !== false) {
                            $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_editortemplates WHERE templateID = ?");
                            $stmt->execute([$parameters['object']]);
                            $result = array(
                                "success" => true,
                                "message" => "Diese Editorvorlage wurde erfolgreich gelöscht.",
                                "title" => "Editorvorlage gelöscht"
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
        } else if($type == 18) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.editor.templates")) {
                        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE userID = ? AND templateID = ?");
                        $stmt->execute([KuschelTickets::getUser()->userID, $parameters['object']]);
                        $row = $stmt->fetch();
                        if($row !== false) {
                            $result = array(
                                "success" => true,
                                "message" => $row['content'],
                                "title" => null
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
        } else if($type == 19) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.editor.templates")) {
                        $editortemplates = KuschelTickets::getUser()->getEditorTemplates();
                        $out = [];
                        foreach($editortemplates as $editortpl) {
                            $data = array(
                                "title" => $editortpl->title,
                                "description" => $editortpl->description,
                                "content" => $editortpl->content
                            );
                            array_push($out, $data);
                        }
                        $result = array(
                            "success" => true,
                            "message" => $out,
                            "title" => null
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 20) {
            if(isset($parameters['object']) && !empty($parameters['object']) && $config['externalURLTitle']) {
                $url = Utils::fromASCI($parameters['object']);
                if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                    $title = $url;
                    $opts = [
                        "http" => [
                            "method" => "GET",
                            "header" => [
                                "User-Agent: PHP"
                            ]
                        ]
                    ];
                    $context = stream_context_create($opts);
                    try {
                        $data = file_get_contents($url, false, $context);
                        preg_match("/<title>(.*)<\/title>/i", $data, $matches);
                        $title = $matches[1];
                    } catch(Exception $e) {
                        $title = $url;
                    }
                    $result = array(
                        "success" => true,
                        "message" => $title,
                        "title" => null
                    );
                }
            }
        } else if($type == 21) {
            if(isset($parameters['object']) && !empty($parameters['object']) && $config['externalURLTitle']) {
                if(KuschelTickets::getUser()->userID) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.view.faq")) {
                        $faq = new FAQ($parameters['object']);
                        if($faq->faqID) {
                            $result = array(
                                "success" => true,
                                "message" => $faq->answer,
                                "title" => null
                            );
                        }
                    }
                }
            }
        } else if($type == 22) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.notifications.view")) {
                        $notification = new Notification($parameters['object']);
                        if($notification->notificationID) {
                            if($notification->getUser()->userID == KuschelTickets::getUser()->userID) {
                                $result = array(
                                    "success" => true,
                                    "message" => $notification->sent,
                                    "title" => null
                                );
                            }
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 23) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    if(KuschelTickets::getUser()->hasPermission("general.notifications.view")) {
                        $notification = new Notification($parameters['object']);
                        if($notification->notificationID) {
                            if($notification->getUser()->userID == KuschelTickets::getUser()->userID && $notification->sent !== 1) {
                                $notification->update(array(
                                    "sent" => 1
                                ));
                                $result = array(
                                    "success" => true,
                                    "message" => true,
                                    "title" => null
                                );
                            }
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 24) {
            if(KuschelTickets::getUser()->userID) {
                
                if(KuschelTickets::getUser()->hasPermission("general.supportchat.view")) {
                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE state = 0 AND NOT creator = ?");
                    $stmt->execute([KuschelTickets::getUser()->userID]);
                    $data = [];
                    while($row = $stmt->fetch()) {
                        $creator = new User($row['creator']);
                        $content = array(
                            "creatorName" => $creator->username,
                            "time" => date("d.m.Y", $row['time']).", ".date("H:i", $row['time'])." Uhr",
                            "chatID" => $row['chatID']
                        );
                        array_push($data, $content);
                    }
                    $result = array(
                        "success" => "true",
                        "message" => $data,
                        "title" => null
                    );
                }
            }
        } else if($type == 25) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.supportchat.join")) {
                        $chat = new SupportChat((int) $parameters['object']);
                        if($chat->chatID) {
                            if($chat->getCreator()->userID !== KuschelTickets::getUser()->userID) {
                                if($chat->isJoinable()) {
                                    $chat->join(KuschelTickets::getUser());
                                    $result = array(
                                        "success" => "true",
                                        "message" => "Du bist erfolgreich dem Supportchat von ".$chat->getCreator()->username." beigetreten.",
                                        "title" => "Supportchat beigetreten"
                                    );
                                } else {
                                    $result = array(
                                        "success" => "false",
                                        "message" => "Dieser Supportchat ist leider bereits besetzt.",
                                        "title" => "Supportchat besetzt"
                                    );
                                }
                            } else {
                                $result = array(
                                    "success" => "false",
                                    "message" => "Du kannst nicht deinem eigenen Supportchat beitreten.",
                                    "title" => "Supportchat nicht betretbar"
                                );
                            }
                        }
                    } else {
                        $result = array(
                            "success" => "false",
                            "message" => "Du hast nicht die erforderliche Berechtigung um diese Aktion auszuführen.",
                            "title" => "Zugriff verweigert"
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 26) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.supportchat.use")) {
                        $chat = new SupportChat((int) $parameters['object']);
                        if($chat->chatID) {
                            $list = $chat->getMessages()->getObjects();
                            foreach($list as $key => $value) {
                                $account = new User($value->poster);
                                $list[$key]->poster = $account->username;
                                $list[$key]->badge = $account->getGroup()->getGroupBadge();
                            }
                            if($chat->getCreator()->userID == KuschelTickets::getUser()->userID) {
                                $result = array(
                                    "success" => "true",
                                    "message" => $list,
                                    "title" => null
                                );
                            } else if($chat->getUser() !== null) {
                                if($chat->getUser()->userID == KuschelTickets::getUser()->userID) {
                                    $result = array(
                                        "success" => "true",
                                        "message" => $list,
                                        "title" => null
                                    );
                                }
                            }
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 27) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.supportchat.use")) {
                        $chatID = null;
                        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE user = ? AND NOT state = 2  ORDER BY chatID DESC LIMIT 1");
                        $stmt->execute([KuschelTickets::getUser()->userID]);
                        $row = $stmt->fetch();
                        if($row !== false) {
                            $chatID = $row['chatID'];
                        } else {
                            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE creator = ? AND NOT state = 2 ORDER BY chatID DESC LIMIT 1");
                            $stmt->execute([KuschelTickets::getUser()->userID]);
                            $row = $stmt->fetch();
                            if($row !== false) {
                                $chatID = $row['chatID'];
                            }
                        }
                        if($chatID !== null) {
                            $chat = new SupportChat((int) $chatID);
                            if($chat->chatID) {
                                $chat->createMessage(KuschelTickets::getUser(), Utils::fromASCI($parameters['object']));
                                $result = array(
                                    "success" => "true",
                                    "message" => null,
                                    "title" => null
                                );
                            }
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 28) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    if(KuschelTickets::getUser()->hasPermission("general.supportchat.use")) {
                        $chat = new SupportChat($parameters['object']);
                        if($chat->chatID) {
                            if($chat->getUser()->userID == KuschelTickets::getUser()->userID) {
                                $chat->createSystemMessage("Der Benutzer ".$chat->getUser()->username." hat den Chat verlassen.");
                                $chat->createSystemMessage("Dieser Chat wird nun geschlossen.");
                                $chat->update(array(
                                    "state" => 2
                                ));
                                $result = array(
                                    "success" => "true",
                                    "message" => null,
                                    "title" => null
                                );
                            } else if($chat->getCreator()->userID == KuschelTickets::getUser()->userID) {
                                $chat->createSystemMessage("Der Benutzer ".$chat->getCreator()->username." hat den Chat verlassen.");
                                $chat->createSystemMessage("Dieser Chat wird nun geschlossen.");
                                $chat->update(array(
                                    "state" => 2
                                ));
                                $result = array(
                                    "success" => "true",
                                    "message" => null,
                                    "title" => null
                                );
                            }
                        }
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 29) {
            if(KuschelTickets::getUser()->userID) {
                
                if(KuschelTickets::getUser()->hasPermission("mod.supportchat.create")) {
                    $chat = SupportChat::openChat(KuschelTickets::getUser());
                    $result = array(
                        "success" => "true",
                        "message" => "Dein Supportchat wurde erfolgreich geöffnet.",
                        "title" => "Supportchat geöffnet",
                        "chatID" => $chat->chatID
                    );
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 30) {
            if(KuschelTickets::getUser()->userID) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("admin.acp.page.menuentries")) {
                        $entry = new MenuEntry($parameters['object']);
                        if(!$entry->isSystem()) {
                            $stmt = KuschelTickets::getDB()->prepare("UPDATE kuscheltickets".KT_N."_menu SET `parent`=NULL WHERE parent = ?");
                            $stmt->execute([$parameters['object']]);
                            $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_menu WHERE menuID = ?");
                            $stmt->execute([$parameters['object']]);
                            $result = array(
                                "success" => "true",
                                "message" => "Dieser Menüeintrag wurde erfolgreich gelöscht.",
                                "title" => "Menüeintrag gelöscht"
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
        } else if($type == 31) {
            if(KuschelTickets::getUser()->userID) {
                
                if(KuschelTickets::getUser()->hasPermission("admin.acp.page.menuentries")) {
                    if(isset($_POST['json']) && !empty($_POST['json'])) {
                        $json = json_decode($_POST['json']);
                        foreach($json as $key => $value) {
                            $stmt = KuschelTickets::getDB()->prepare("UPDATE kuscheltickets".KT_N."_menu SET `sorting`=? WHERE menuID = ?");
                            $stmt->execute([$key, $value]);
                        }
                        $result = array(
                            "success" => "true",
                            "message" => null,
                            "title" => null
                        );
                    }
                }
            } else {
                $result = array(
                    "code" => "403",
                    "message" => "access denied"
                );
            }
        } else if($type == 32) {
            if(KuschelTickets::getUser()->userID && $config['ticketRating']) {
                if(isset($parameters['object']) && !empty($parameters['object'])) {
                    
                    if(KuschelTickets::getUser()->hasPermission("general.ticket.rate")) {
                        $ticket = new Ticket($parameters['object']);
                        if($ticket->ticketID) {
                            if($ticket->getCreator()->userID == KuschelTickets::getUser()->userID && !$ticket->hasRating()) {
                                if(isset($_POST['rating']) && !empty($_POST['rating']) && is_numeric($_POST['rating'])) {
                                    $rating = (int) $_POST['rating'];
                                    $ratingIcon = "Sternen";
                                    if($config['ticketRatingIcon'] == "heart") {
                                        $ratingIcon = "Herzen";
                                    }
                                    if($rating > 0 && $rating < 6) {
                                        $ticket->update(array(
                                            "rating" => $rating
                                        ));
                                        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
                                        $stmt->execute([$ticket->ticketID]);
                                        $already = [];
                                        Notification::add("notification_ticket_rated", "Das Ticket ".$ticket->title." von ".KuschelTickets::getUser()->username." in der Kategorie ".$ticket->getCategory()->categoryName." wurde mit ".$rating." von 5 ".$ratingIcon." bewertet.", "ticket-".$ticket->ticketID, KuschelTickets::getUser());
                                        array_push($already, KuschelTickets::getUser()->userID);
                                        while($row = $stmt->fetch()) {
                                            $account = new User((int) $row['creator']);
                                            if(!$account->hasPermission("mod.view.ticket.all")) {
                                                if($account->userID == $ticket->getCreator()->userID) {
                                                    if(!in_array($account->userID, $already)) {
                                                        Notification::add("notification_ticket_rated", "Das Ticket ".$ticket->title." von ".KuschelTickets::getUser()->username." in der Kategorie ".$ticket->getCategory()->categoryName." wurde mit ".$rating." von 5 ".$ratingIcon." bewertet.", "ticket-".$ticket->ticketID, $account);
                                                    }
                                                }
                                            } else {
                                                if(!in_array($account->userID, $already)) {
                                                    Notification::add("notification_ticket_rated", "Das Ticket ".$ticket->title." von ".KuschelTickets::getUser()->username." in der Kategorie ".$ticket->getCategory()->categoryName." wurde mit ".$rating." von 5 ".$ratingIcon." bewertet.", "ticket-".$ticket->ticketID, $account);
                                                }
                                            }
                                        }
                                        $result = array(
                                            "success" => "true",
                                            "message" => "Du hast dieses Ticket erfolgreich bewertet.",
                                            "title" => "Ticket bewertet"
                                        );
                                    }
                                }
                            }
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