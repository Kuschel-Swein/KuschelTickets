<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\Mailer;
use KuschelTickets\lib\recaptcha;

class accountmanagementPage extends Page {

    private $errors;
    private $success;

    public function readParameters(Array $parameters) {
        global $config;

        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.account.manage")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        $this->errors = array(
            "password" => false,
            "username" => false,
            "email" => false,
            "password_new" => false,
            "password_new_confirm" => false,
            "token" => false
        );
        $this->success = array(
            "username" => false,
            "password" => false,
            "email" => false
        );

        if(isset($parameters['submit'])) {
            if(recaptcha::validate("accountmanagement")) {
                if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                    if(CRSF::validate($parameters['CRSF'])) {
                        if(isset($parameters['password']) && !empty($parameters['password'])) {
                            if(password_verify($parameters['password'], $user->getHash())) {
                                if(isset($parameters['username']) && !empty($parameters['username'])) {
                                    if(UserUtils::exists($parameters['username'], "username") && $parameters['username'] !== $user->getUserName()) {
                                        $this->errors['username'] = "Dieser Benutzername ist bereits vergeben.";
                                        $this->passwordChange($parameters);
                                        $this->emailChange($parameters);
                                    } else {
                                        if($parameters['username'] !== $user->getUserName()) {
                                            $stmt = $config['db']->prepare("UPDATE `kuscheltickets1_accounts` SET `username`= ? WHERE userID = ?");
                                            $username = strip_tags($parameters['username']);
                                            $stmt->execute([$username, $user->userID]);
                                            $this->success["username"] = "Dein neuer Benutzername wurde erfolgreich gespeichert.";
                                        }
                                        $this->passwordChange($parameters);
                                        $this->emailChange($parameters);
                                    }
                                } else {
                                    $this->errors['username'] = "Bitte gib einen Benutzernamen an.";
                                }
                            } else {
                                $this->errors['password'] = "Dein aktuelles Passwort ist leider falsch.";
                            }
                        } else {
                            $this->errors['password'] = "Bitte gib dein aktuelles Passwort ein.";
                        }
                    }  else {
                        $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                    }
                }  else {
                    $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                }
            } else {
                $this->errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
            }
        }
    }

    public function passwordChange(Array $parameters) {
        global $config;

        $user = new User(UserUtils::getUserID());
        if(isset($parameters['password_new']) && !empty($parameters['password_new'])) {
            if(isset($parameters['password_new_confirm']) && !empty($parameters['password_new_confirm'])) {
                if($parameters['password_new'] == $parameters['password_new_confirm']) {
                    $stmt = $config['db']->prepare("UPDATE `kuscheltickets1_accounts` SET `password`= ? WHERE userID = ?");
                    $password = password_hash($parameters['password_new'], PASSWORD_BCRYPT);
                    $stmt->execute([$password, $user->userID]);
                    $this->success["password"] = "Dein neues Passwort wurde gespeichert.";
                    UserUtils::logOut();
                } else {
                    $this->errors['password_new_confirm'] = "Die beiden Passwörter stimmen nicht überein.";
                }
            } else {   
                $this->errors['password_new_confirm'] = "Bitte bestätige dein neues Passwort.";
            }
        }
    }

    public function emailChange(Array $parameters) {
        global $config;

        $user = new User(UserUtils::getUserID());
        if(isset($parameters['email']) && !empty($parameters['email'])) {
            if(UserUtils::exists($parameters['email'], "email") && $parameters['email'] !== $user->getEmail()) {
                $this->errors['email'] = "Diese E-Mail Adresse ist bereits vergeben.";
            } else {
                if(filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
                    if($parameters['email'] !== $user->getEmail()) {
                        $stmt = $config['db']->prepare("UPDATE `kuscheltickets1_accounts` SET `email_change_email`= ?, `email_change_time`= ? WHERE userID = ?");
                        $email = strip_tags($parameters['email']);
                        $time = strtotime("+15minutes");
                        $stmt->execute([$email, $time, $user->userID]);
                        $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
                        $this->success["email"] = "Es wurde ein Aktivierungslink an deine neue E-Mail Adresse versendet, sobald du diesem folgst, kannst du deine neue E-Mail Adresse nutzen.";
                        $mail = new Mailer($email, $config['pagetitle']." - E-Mail Aktualisierung", $user->getUserName());
                        $message = "<p>Hey ".$user->getUserName().",</p>
                        <p>deine E-Mail Adressse auf der Website ".$config['pagetitle']." soll geändert werden. Um diese neue E-Mail Adresse zu aktivieren, folge bitte diesem Link. Beachte allerdings dass dieser Link nur <b>15 Minuten</b> gültig ist.</p>
                        <p><b>Link:</b> <a href='".$mainurl."?emailactivate/token-".$user->getSecurityToken()."'>".$mainurl."?emailactivate/token-".$user->getSecurityToken()."</a></p>
                        <p></p>
                        <p><hr></p>
                        <p>Mit freundlichen Grüßen,</p>
                        <p>dein ".$config['pagetitle']." Team</p>";
                        $mail->setMessage($message);
                        $mail->send();
                    } else {
                        $this->errors['email'] = "Dies ist deine aktuelle E-Mail Adresse.";
                    }
                } else {
                    $this->errors['email'] = "Das ist keine valide E-Mail Adresse";
                }
            }
        }
    }

    public function assign() {
        return array(
            "errors" => $this->errors,
            "success" => $this->success,
            "recaptcha" => recaptcha::build("accountmanagement")
        );
    }


}
?>