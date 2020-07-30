<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\recaptcha;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\KuschelTickets;

class loginPage extends Page {

    private $errors;

    public function readParameters(Array $parameters) {
        global $config;

        $this->errors = array(
            "username" => false,
            "password" => false,
            "token" => false
        );

        if(KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du kannst diese Seite nicht öffnen");
        }

        if(isset($parameters['submit'])) {
            if(recaptcha::validate("login")) {
                if(isset($parameters['username']) && !empty($parameters['username'])) {
                    if(isset($parameters['password']) && !empty($parameters['password'])) {
                        $username = $parameters['username'];
                        $password = $parameters['password'];
                        $db = $config['db'];
                        $stmt = $db->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE username = ?");
                        $stmt->execute([$username]);
                        $row = $stmt->fetch();
                        if($row) {
                            if(password_verify($password, $row['password'])) {
                                $account = new User($row['userID']);
                                if($account->banned == 1) {
                                    $this->errors['username'] = "Dein Benutzerkonto wurde aus folgenden gründen gesperrt:";
                                    $this->errors['password'] = $account->banreason;
                                } else {
                                    if($account->hasPermission("general.login")) {
                                        UserUtils::loginAs($account, $row['password']);
                                        Utils::redirect(Link::get(""));
                                    } else {
                                        throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung um diese Seite zu sehen.");
                                    }
                                }
                            } else {
                                $this->errors['password'] = "Dieses Passwort ist falsch.";
                            }
                        } else {
                            $this->errors['username'] = "Dieser Benutzername existiert nicht.";
                        }
                    } else {
                        $this->errors['password'] = "Bitte gib ein Passwort an.";
                    }
                } else {
                    $this->errors['username'] = "Bitte gib einen Benutzernamen an.";
                }
            } else {
                $this->errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
            }
        }
    }

    public function assign() {
        return array(
            "errors" => $this->errors,
            "recaptcha" => recaptcha::build("login")
        );
    }


}
?>