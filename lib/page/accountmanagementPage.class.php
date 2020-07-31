<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\data\user\User;
use kt\system\UserUtils;
use kt\system\CRSF;
use kt\system\Utils;
use kt\system\exception\AccessDeniedException;
use kt\system\mailer\Mailer;
use kt\system\Link;
use kt\system\recaptcha;
use kt\system\KuschelTickets;
use kt\system\TOTP;

class accountmanagementPage extends AbstractPage {

    private $errors;
    private $success;

    public function readParameters(Array $parameters) {
        global $config;

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.account.manage")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        $this->errors = array(
            "password" => false,
            "username" => false,
            "email" => false,
            "password_new" => false,
            "password_new_confirm" => false,
            "twofactor" => false,
            "token" => false
        );
        $this->success = array(
            "username" => false,
            "password" => false,
            "twofactor" => false,
            "email" => false
        );

        if(isset($parameters['submit'])) {
            if(recaptcha::validate("accountmanagement")) {
                if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                    if(CRSF::validate($parameters['CRSF'])) {
                        if(isset($parameters['password']) && !empty($parameters['password'])) {
                            if(password_verify($parameters['password'], KuschelTickets::getUser()->password)) {
                                if(isset($parameters['username']) && !empty($parameters['username'])) {
                                    if(UserUtils::exists($parameters['username'], "username") && $parameters['username'] !== KuschelTickets::getUser()->username) {
                                        $this->errors['username'] = "Dieser Benutzername ist bereits vergeben.";
                                        $this->twofactor($parameters);
                                        $this->emailChange($parameters);
                                        $this->passwordChange($parameters);
                                    } else {
                                        if($parameters['username'] !== KuschelTickets::getUser()->username) {
                                            $username = strip_tags($parameters['username']);
                                            KuschelTickets::getUser()->update(array(
                                                "username" => $username
                                            ));
                                            $this->success["username"] = "Dein neuer Benutzername wurde erfolgreich gespeichert.";
                                        }
                                        $this->twofactor($parameters);
                                        $this->emailChange($parameters);
                                        $this->passwordChange($parameters);
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

    public function twofactor(Array $parameters) {
        global $config;

        if(KuschelTickets::getUser()->hasPermission("general.account.twofactor")) {
            if(isset($parameters['twofactor_enabled'])) {
                if($parameters['twofactor_enabled'] == "on") {
                    if(isset($parameters['twofactor_secret']) && !empty($parameters['twofactor_secret'])) {
                        if(isset($parameters['twofactor']) && !empty($parameters['twofactor'])) {
                            $totp = new TOTP();
                            $validCode = $totp->verifyCode($parameters['twofactor_secret'], $parameters['twofactor'], 2);
                            if($validCode) {
                                $backupcodes = [];
                                for($i = 0; $i < 9; $i++) {
                                    array_push($backupcodes, Utils::randomString());
                                }
                                // use cast to array for better performance
                                $twofactor = (Array) KuschelTickets::getUser()->twofactor;
                                $twofactor['use'] = true;
                                $twofactor['backupcodes'] = $backupcodes;
                                $twofactor['code'] = $parameters['twofactor_secret'];
                                $twofactor = (object) $twofactor;
                                KuschelTickets::getUser()->update(array(
                                    "twofactor" => $twofactor
                                ));
                                $mail = new Mailer(KuschelTickets::getUser()->email, $config['pagetitle']." - Backupcodes", KuschelTickets::getUser()->username);
                                $message = "<p>Hey ".KuschelTickets::getUser()->username.",</p>
                                <p>auf der Website ".$config['pagetitle']." wurde die 2-Faktor Authentisierung aktiviert. Hier erhälst du nun zur Sicherheit deine Backupcodes.</p>
                                <table>
                                    <tbody>
                                        <tr><td>".$backupcodes[0]."</td><td>".$backupcodes[1]."</td><td>".$backupcodes[2]."</td></tr>
                                        <tr><td>".$backupcodes[3]."</td><td>".$backupcodes[4]."</td><td>".$backupcodes[5]."</td></tr>
                                        <tr><td>".$backupcodes[6]."</td><td>".$backupcodes[7]."</td><td>".$backupcodes[8]."</td></tr>
                                    </tbody>
                                </table>
                                <p></p>
                                <p><hr></p>
                                <p>Mit freundlichen Grüßen,</p>
                                <p>dein ".$config['pagetitle']." Team</p>";
                                $mail->setMessage($message);
                                $mail->send();
                                $this->success['twofactor'] = "Du hast die 2-Faktor Authentisierung erfolgreich aktiviert. Die Backupcodes wurden dir zusätzlich per E-Mail zugesandt.";
                            } else {
                                $this->errors['twofactor'] = "Bitte gib einen validen Sicherheitscode an.";
                            }
                        } else {
                            $this->errors['twofactor'] = "Bitte gib den Sicherheitscode an.";
                        }
                    } else {
                        $this->errors['twofactor'] = "Bitte sende eine valide Anfrage.";
                    }
                }
            } else {
                // use cast to array for better performance
                $twofactor = (Array) KuschelTickets::getUser()->twofactor;
                $twofactor['use'] = false;
                $twofactor['backupcodes'] = [];
                $twofactor['code'] = "";
                $twofactor = (object) $twofactor;
                KuschelTickets::getUser()->update(array(
                    "twofactor" => $twofactor
                ));
                $this->success['twofactor'] = "Du hast die 2-Faktor Authentisierung erfolgreich deaktiviert.";
            }
        }
    }

    public function passwordChange(Array $parameters) {
        global $config;

        if(isset($parameters['password_new']) && !empty($parameters['password_new'])) {
            if(isset($parameters['password_new_confirm']) && !empty($parameters['password_new_confirm'])) {
                if($parameters['password_new'] == $parameters['password_new_confirm']) {
                    KuschelTickets::getUser()->update(array(
                        "password" => password_hash($parameters['password_new'], PASSWORD_BCRYPT)
                    ));
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

        if(isset($parameters['email']) && !empty($parameters['email'])) {
            if(UserUtils::exists($parameters['email'], "email") && $parameters['email'] !== KuschelTickets::getUser()->email) {
                $this->errors['email'] = "Diese E-Mail Adresse ist bereits vergeben.";
            } else {
                if(filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
                    if($parameters['email'] !== KuschelTickets::getUser()->email) {
                        $email = strip_tags($parameters['email']);
                        $time = strtotime("+15minutes");
                        KuschelTickets::getUser()->update(array(
                            "email_change_email" => $email,
                            "email_change_time" => $time
                        ));
                        $this->success["email"] = "Es wurde ein Aktivierungslink an deine neue E-Mail Adresse versendet, sobald du diesem folgst, kannst du deine neue E-Mail Adresse nutzen.";
                        $mail = new Mailer($email, $config['pagetitle']." - E-Mail Aktualisierung", KuschelTickets::getUser()->username);
                        $message = "<p>Hey ".KuschelTickets::getUser()->username.",</p>
                        <p>deine E-Mail Adressse auf der Website ".$config['pagetitle']." soll geändert werden. Um diese neue E-Mail Adresse zu aktivieren, folge bitte diesem Link. Beachte allerdings dass dieser Link nur <b>15 Minuten</b> gültig ist.</p>
                        <p><b>Link:</b> <a href='".Link::get("emailactivate/token-".KuschelTickets::getUser()->token)."'>".Link::get("emailactivate/token-".KuschelTickets::getUser()->token)."</a></p>
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
        KuschelTickets::getTPL()->assign(array(
            "errors" => $this->errors,
            "success" => $this->success,
            "twofactor" => new TOTP(),
            "recaptcha" => recaptcha::build("accountmanagement")
        ));
    }


}
?>