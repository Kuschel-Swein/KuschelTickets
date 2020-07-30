<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\data\user\User;
use kt\system\UserUtils;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;

class emailactivatePage extends AbstractPage {

    private $result;

    public function readParameters(Array $parameters) {

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.account.manage")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        $this->result['type'] = "error";
        $this->result['message'] = "Deine Aktivierungsanfrage war Fehlerhaft, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];

        if(isset($parameters['token']) && !empty($parameters['token'])) {
            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE token = ?");
            $stmt->execute([$parameters['token']]);
            $row = $stmt->fetch();
            if($row) {
                $user = new User($row['userID']);
                $time = time();
                if($user->email_change_time >= $time) {
                    $user->update(array(
                        "token" => UserUtils::generateToken(),
                        "email" => $user->email_change_email,
                        "email_change_email" => null,
                        "email_change_time" => 0
                    ));
                    $this->result['type'] = "success";
                    $this->result['message'] = "Deine E-Mail Adresse wurde erfolgreich aktualisiert.";
                } else {
                    $this->result['type'] = "error";
                    $this->result['message'] = "Deine Aktivierungsanfrage ist abgelaufen, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];
                    $user->update(array(
                        "token" => UserUtils::generateToken(),
                        "email_change_email" => null,
                        "email_change_time" => 0
                    ));
                }
            } else {
                $this->result['type'] = "error";
                $this->result['message'] = "Deine Aktivierungsanfrage war Fehlerhaft, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];
            }
        } else {
            $this->result['type'] = "error";
            $this->result['message'] = "Deine Aktivierungsanfrage war Fehlerhaft, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];
        }
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "result" => $this->result
        ));
    }


}
?>