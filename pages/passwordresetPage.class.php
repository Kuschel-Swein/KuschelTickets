<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\Mailer;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\recaptcha;

class passwordresetPage extends Page {

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

        if(UserUtils::isLoggedIn()) {
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
                            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE email = ?");
                            $stmt->execute([$parameters['email']]);
                            $row = $stmt->fetch();
                            $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
                            $mail = new Mailer($row['email'], $config['pagetitle']." - Passwortzurücksetzung", $row['username']);
                            $message = "<p>Hey ".$row['username'].",</p>
                            <p>Für deinen Account bei ".$config['pagetitle'].". wurde eine Passwortzurücksetzung angefordert. Um dein Passwort zu ändern folge innerhalb der nächsten <b>15 Minuten</b> dem folgenden Link, solltest du diese Zurücksetzung nicht angefordert haben,
                            kannst du diese E-Mail ignorieren.</p>
                            <p><b>Link:</b> <a href='".$mainurl."?passwordreset/token-".$row['token']."'>".$mainurl."?passwordreset/token-".$row['token']."</a></p>
                            <p></p>
                            <p><hr></p>
                            <p>Mit freundlichen Grüßen,</p>
                            <p>dein ".$config['pagetitle']." Team</p>";
                            $mail->setMessage($message);
                            $mail->send();    
                            $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `password_reset`= ? WHERE userID = ?");
                            $time = strtotime("+15minutes");
                            $stmt->execute([$time, $row['userID']]);
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
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE token = ?");
            $stmt->execute([$parameters['token']]);
            $row = $stmt->fetch();
            if($row) {
                $time = time();
                if($row['password_reset'] > $time) {
                    $this->tokenkey = $row['token'];
                    if(isset($parameters['submit'])) {
                        if(recaptcha::validate("passwordreset")) {
                            if(isset($parameters['password']) && !empty($parameters['password'])) {
                                if(isset($parameters['password_confirm']) && !empty($parameters['password_confirm'])) {
                                    if($parameters['password'] == $parameters['password_confirm']) {
                                        $stmt = $config['db']->prepare("UPDATE `kuscheltickets1_accounts` SET `password`= ?,`token`= ?,`password_reset`= 0 WHERE token = ?");
                                        $oldtoken = $parameters['token'];
                                        $newtoken = UserUtils::generateToken();
                                        $password = password_hash($parameters['password'], PASSWORD_BCRYPT);
                                        $stmt->execute([$password, $newtoken, $oldtoken]);
                                        $this->result = "Dein Passwort wurde erfolgreich geändert. Du kannst dich nun <a href='index.php?login'>einloggen</a>.";
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
                    $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_accounts SET `token`= ?,`password_reset`= 0 WHERE userID = ?");
                    $token = UserUtils::generateToken();
                    $stmt->execute([$token, $row['userID']]);
                    $this->errors['token'] = "Deine Anfrage ist leider abgelaufen, du hast nur <b>15 Minuten</b> zeit dein Passwort zu ändern.";
                }
            } else {
                $this->errors['token'] = "Dies ist eine falsche Anfrage.";
            }
        }
    }

    public function assign() {
        return array(
            "errors" => $this->errors,
            "token" => $this->token,
            "result" => $this->result,
            "tokenkey" => $this->tokenkey,
            "recaptcha" => recaptcha::build("passwordreset")
        );
    }


}
?>