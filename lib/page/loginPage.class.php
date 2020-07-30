<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\Utils;
use kt\system\Link;
use kt\system\recaptcha;
use kt\data\user\User;
use kt\system\UserUtils;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;

class loginPage extends AbstractPage {

    private $errors;

    public function readParameters(Array $parameters) {
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
                        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE username = ?");
                        $stmt->execute([$username]);
                        $row = $stmt->fetch();
                        if($row) {
                            $user = new User($row['userID']);
                            if(password_verify($password, $user->password)) {
                                if($user->banned == 1) {
                                    $this->errors['username'] = "Dein Benutzerkonto wurde aus folgenden gründen gesperrt:";
                                    $this->errors['password'] = $user->banreason;
                                } else {
                                    if($user->hasPermission("general.login")) {
                                        UserUtils::loginAs($user, $user->password);
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
        KuschelTickets::getTPL()->assign(array(
            "errors" => $this->errors,
            "recaptcha" => recaptcha::build("login")
        ));
    }


}
?>