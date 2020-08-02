<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\mailer\Mailer;
use kt\system\Link;
use kt\system\UserUtils;
use kt\system\exception\AccessDeniedException;
use kt\system\exception\PageNotFoundException;
use kt\system\recaptcha;
use kt\data\user\User;
use kt\system\KuschelTickets;

class passwordresetPage extends AbstractPage {

    private $errors;
    private $token = false;
    private $result = false;
    private $tokenkey = "";

    public function readParameters(Array $parameters) {
        global $config;

        $this->errors = array(
            "email" => false,
            "token" => false,
            "password" => false,
            "password_confirm" => false,
            "recaptcha" => false
        );

        if(KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du kannst diese Seite nicht öffnen");
        }
        if(isset($parameters['token']) && !empty($parameters['token'])) {
            $this->token = true;
        }
        if(!$this->token) {
            if(isset($parameters['submit'])) {
                if(recaptcha::validate("passwordreset")) {
                    if(isset($parameters['email']) && !empty($parameters['email'])) {
                        if(filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
                            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE email = ?");
                            $stmt->execute([$parameters['email']]);
                            $row = $stmt->fetch();
                            $user = new User($row['userID']);
                            $mail = new Mailer($user->email, $config['pagetitle']." - Passwortzurücksetzung", $user->username);
                            $message = "<p>Hey ".$user->username.",</p>
                            <p>Für deinen Account bei ".$config['pagetitle'].". wurde eine Passwortzurücksetzung angefordert. Um dein Passwort zu ändern folge innerhalb der nächsten <b>15 Minuten</b> dem folgenden Link, solltest du diese Zurücksetzung nicht angefordert haben,
                            kannst du diese E-Mail ignorieren.</p>
                            <p><b>Link:</b> <a href='".Link::get("passwordreset/token-".$user->token)."'>".Link::get("passwordreset/token-".$user->token)."</a></p>
                            <p></p>
                            <p><hr></p>
                            <p>Mit freundlichen Grüßen,</p>
                            <p>dein ".$config['pagetitle']." Team</p>";
                            $mail->setMessage($message);
                            $mail->send();    
                            $user->update(array(
                                "password_reset" => strtotime("+15minutes")
                            ));
                            $this->result = "Sollte ein Account mit dieser E-Mail Adresse existieren, wurde eine E-Mail mit der Anleitung das Passwort zurückzusetzen versendet.";
                        } else {
                            $this->errors['email'] = "Bitte gib eine gültige E-Mail Adresse an.";
                        }
                    } else {
                        $this->errors['email'] = "Bitte gib eine E-Mail Adresse an.";
                    }
                } else {
                    $this->errors['captcha'] = "Du wurdest von reCaptcha als Bot erkannt.";
                }
            }
        } else {
            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE token = ?");
            $stmt->execute([$parameters['token']]);
            $row = $stmt->fetch();
            if($row) {
                $user = new User($row['userID']);
                $time = time();
                if($user->password_reset > $time) {
                    $this->tokenkey = $user->token;
                    if(isset($parameters['submit'])) {
                        if(recaptcha::validate("passwordreset")) {
                            if(isset($parameters['password']) && !empty($parameters['password'])) {
                                if(isset($parameters['password_confirm']) && !empty($parameters['password_confirm'])) {
                                    if($parameters['password'] == $parameters['password_confirm']) {
                                        $user->update(array(
                                            "password" => password_hash($parameters['password'], PASSWORD_BCRYPT),
                                            "token" => UserUtils::generateToken(),
                                            "password_reset" => 0
                                        ));
                                        $this->result = "Dein Passwort wurde erfolgreich geändert. Du kannst dich nun <a href='index.php?login' class='noticeLink'>einloggen</a>.";
                                    } else {
                                        $this->errors['password_confirm'] = "Dieses Passwort stimmt nicht mit dem obrigen überein.";
                                    }
                                } else {
                                    $this->errors['password_confirm'] = "Bitte bestätige dein Passwort.";
                                }
                            } else {
                                $this->errors['password'] = "Bitte gib ein Passwort an.";
                            }
                        } else {
                            $this->errors['captcha'] = "Du wurdest von reCaptcha als Bot erkannt.";
                        }
                    }
                } else {
                    $user->update(array(
                        "token" => UserUtils::generateToken(),
                        "password_reset" => 0
                    ));
                    $this->errors['token'] = "Deine Anfrage ist leider abgelaufen, du hast nur <b>15 Minuten</b> zeit dein Passwort zu ändern.";
                }
            } else {
                throw new PageNotFoundException();
            }
        }
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "errors" => $this->errors,
            "token" => $this->token,
            "result" => $this->result,
            "tokenkey" => $this->tokenkey,
            "recaptcha" => recaptcha::build("passwordreset")
        ));
    }


}
?>