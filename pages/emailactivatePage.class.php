<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\KuschelTickets;

class emailactivatePage extends Page {

    private $result;

    public function readParameters(Array $parameters) {
        global $config;

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.account.manage")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        $this->result['type'] = "error";
        $this->result['message'] = "Deine Aktivierungsanfrage war Fehlerhaft, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];

        if(isset($parameters['token']) && !empty($parameters['token'])) {
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE token = ?");
            $stmt->execute([$parameters['token']]);
            $row = $stmt->fetch();
            if($row) {
                $time = time();
                if($row['email_change_time'] >= $time) {
                    $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `token`= ?,`email`= ?, `email_change_email`=NULL, `email_change_time`=0 WHERE token = ?");
                    $newtoken = UserUtils::generateToken();
                    $oldtoken = $parameters['token'];
                    $stmt->execute([$newtoken, $row['email_change_email'], $oldtoken]);
                    $this->result['type'] = "success";
                    $this->result['message'] = "Deine E-Mail Adresse wurde erfolgreich aktualisiert.";
                } else {
                    $this->result['type'] = "error";
                    $this->result['message'] = "Deine Aktivierungsanfrage ist abgelaufen, solltest du dies für einen Fehler halten wende dich an ".$config['adminmail'];
                    $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `token`= ?,`email_change_email`=NULL, `email_change_time`=0 WHERE token = ?");
                    $newtoken = UserUtils::generateToken();
                    $oldtoken = $parameters['token'];
                    $stmt->execute([$newtoken, $oldtoken]);
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
        return array(
            "result" => $this->result
        );
    }


}
?>