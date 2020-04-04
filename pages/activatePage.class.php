<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;

class activatePage extends Page {

    private $result;

    public function readParameters(Array $parameters) {
        global $config;

        if(isset($parameters['token']) && !empty($parameters['token'])) {
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE token = ?");
            $stmt->execute([$parameters['token']]);
            $row = $stmt->fetch();
            if($row) {
                if($row['userGroup'] == "3") {
                    $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `token`= ?,`userGroup`= 2 WHERE token = ?");
                    $newtoken = UserUtils::generateToken();
                    $oldtoken = $parameters['token'];
                    $stmt->execute([$newtoken, $oldtoken]);
                    $this->result['type'] = "success";
                    $this->result['message'] = "Dein Benutzerkonto wurde erfolgreich aktiviert und du wurdest automatisch eingeloggt.";
                    $user = new User((int) $row['userID']);
                    UserUtils::loginAs($user, $row['password']);
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
        return array(
            "result" => $this->result
        );
    }


}
?>