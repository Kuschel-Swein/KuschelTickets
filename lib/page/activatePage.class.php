<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\data\user\User;
use kt\system\UserUtils;
use kt\system\exception\PageNotFoundException;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;

class activatePage extends AbstractPage {

    private $result;

    public function readParameters(Array $parameters) {
        global $config;

        if(KuschelTickets::getUser()->userID) {
            if(KuschelTickets::getUser()->userGroup !== 3) {
                throw new AccessDeniedException();
            }
        }

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
                    KuschelTickets::getUser()->load();
                    $this->result = "Dein Benutzerkonto wurde erfolgreich aktiviert und du wurdest automatisch eingeloggt.";
                    UserUtils::loginAs($user, $user->password);
                } else {
                    throw new PageNotFoundException();
                }
            } else {
                throw new PageNotFoundException();
            }
        } else {
            throw new PageNotFoundException();
        }
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "result" => $this->result
        ));
    }


}
?>