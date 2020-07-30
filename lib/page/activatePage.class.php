<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\data\user\User;
use kt\system\UserUtils;
use kt\system\KuschelTickets;

class activatePage extends AbstractPage {

    private $result;

    public function readParameters(Array $parameters) {
        global $config;

        if(isset($parameters['token']) && !empty($parameters['token'])) {
            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE token = ?");
            $stmt->execute([$parameters['token']]);
            $row = $stmt->fetch();
            if($row) {
                $user = new User($row['userID']);
                if($user->userGroup == 3) {
                    $user->update(array(
                        "token" => UserUtils::generateToken(),
                        "userGroup" => 2
                    ));
                    $this->result['type'] = "success";
                    $this->result['message'] = "Dein Benutzerkonto wurde erfolgreich aktiviert und du wurdest automatisch eingeloggt.";
                    UserUtils::loginAs($user, $user->password);
                } else {
                    $this->result['type'] = "error";
                    $this->result['message'] = "Deine Aktivierungsanfrage war Fehlerhaft, deshalb konnte kein Account aktiviert werden, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];
                }
            } else {
                $this->result['type'] = "error";
                $this->result['message'] = "Deine Aktivierungsanfrage war Fehlerhaft, deshalb konnte kein Account aktiviert werden, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];
            }
        } else {
            $this->result['type'] = "error";
            $this->result['message'] = "Deine Aktivierungsanfrage war Fehlerhaft, deshalb konnte kein Account aktiviert werden, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];
        }
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "result" => $this->result
        ));
    }


}
?>