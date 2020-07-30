<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\exception\PageNotFoundException;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\Notification;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\recaptcha;
use KuschelTickets\lib\KuschelTickets;

class notificationsPage extends Page {

    private $subpage;
    private $notificationreasons;
    private $errors = false;
    private $success = false;

    public function readParameters(Array $parameters) {
        global $config;

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
        }

        $subpage = null;
        if(isset($parameters['settings'])) {
            $subpage = "settings";
            if(!KuschelTickets::getUser()->hasPermission("general.notifications.settings")) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
            }
        }
        if(isset($_GET['notifications']) || isset($_GET['notifications/'])) {
            $subpage = "index";
            if(!KuschelTickets::getUser()->hasPermission("general.notifications.view")) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
            }
        }

        if($subpage == null) {
            throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
        } else {
            $this->subpage = $subpage;
        }
        $notificationreasons = [
            array(
                "identifier" => "notification_ticket_answer",
                "display" => "auf ein Ticket wurde geantwortet"
            ),
            array(
                "identifier" => "notification_ticket_answer_content",
                "display" => "auf ein Ticket welches deine Inhalte enthält wurde geantwortet"
            ),
            array(
                "identifier" => "notification_ticket_new",
                "display" => "ein neues Ticket wurde erstellt"
            )
        ];
        if($config['ticketRating']) {
            array_push($notificationreasons, array(
                "identifier" => "notification_ticket_rated",
                "display" => "ein Ticket mit deinem Inhalt wurde bewertet"
            ));
        }
        $this->notificationreasons = $notificationreasons;

        if($subpage == "settings") {
            $errors = array(
                "token" => false
            );

            foreach($notificationreasons as $reason) {
                $errors[$reason['identifier']] = false;
            }
            $types = ["normal", "none"];
            if($config['emailnotifications']) {
                array_push($types, "email");
            }
            $success = false;
            if(isset($parameters['submit'])) {
                if(recaptcha::validate("notificationsettings")) {
                    if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                        if(CRSF::validate($parameters['CRSF'])) {
                            $goon = true;
                            $result = array();
                            foreach($notificationreasons as $reason) {
                                if(isset($parameters[$reason['identifier']]) && !empty($parameters[$reason['identifier']])) {
                                    if(in_array($parameters[$reason['identifier']], $types)) {
                                        // everything worked, we can go on
                                        $result[$reason['identifier']] = $parameters[$reason['identifier']];
                                    } else {
                                        $errors[$reason['identifier']] = "Bitte wähle für ".$reason['display']." einen Typ aus.";
                                        $goon = false;
                                    }
                                } else {
                                    $errors[$reason['identifier']] = "Bitte wähle für ".$reason['display']." einen Typ aus.";
                                    $goon = false;
                                }
                            }
                            if($goon) {
                                $stmt = KuschelTickets::getDB()->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `notificationsettings`=? WHERE userID = ?");
                                $settings = json_encode($result);
                                $stmt->execute([$settings, KuschelTickets::getUser()->userID]);
                                $success = "Deine Einstellungen wurden erfolgreich gespeichert.";
                            }
                        } else {
                            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                        }
                    } else {
                        $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                    }
                } else {
                    $errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
                }
            }

            $this->errors = $errors;
            $this->success = $success;
        }
    }

    public function assign() {
        return array(
            "subpage" => $this->subpage,
            "notificationreasons" => $this->notificationreasons,
            "recaptcha" => recaptcha::build('notificationsettings'),
            "errors" => $this->errors,
            "success" => $this->success
        );
    }


}
?>