<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\recaptcha;

class registerPage extends Page {

    private $errors;
    private $success;

    public function readParameters(Array $parameters) {
        global $config;

        $this->errors = array(
            "username" => false,
            "password" => false,
            "password_confirm" => false,
            "email" => false,
            "legal_notice" => false,
            "token" => false
        );
        $this->success = false;

        if(UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du kannst diese Seite nicht öffnen");
        }

        if(isset($parameters['submit'])) {
            if(recaptcha::validate("registration")) {
                if(isset($parameters['username']) && !empty($parameters['username'])) {
                    if(isset($parameters['email']) && !empty($parameters['email'])) {
                        if(filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
                            if(isset($parameters['password']) && !empty($parameters['password'])) {
                                if(isset($parameters['password_confirm']) && !empty($parameters['password_confirm'])) {
                                    if($parameters['password'] == $parameters['password_confirm']) {
                                        if(isset($parameters['legal_notice']) && !empty($parameters['legal_notice'])) {
                                            if(!UserUtils::exists($parameters['username'], "username")) {
                                                if(!UserUtils::exists($parameters['email'], "email")) {
                                                    UserUtils::create($parameters['username'], $parameters['email'], $parameters['password']);
                                                    $this->success = true;
                                                } else {
                                                    $this->errors['email'] = "Diese E-Mail Adresse ist bereits vergeben.";
                                                }
                                            } else {
                                                $this->errors['username'] = "Dieser Benutzername ist bereits vergeben.";
                                            }
                                        } else {
                                            $this->errors['legal_notice'] = "Bitte akzeptiere die Datenschutzerklärung.";
                                        }
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
                            $this->errors['email'] = "Diese E-Mail Adresse ist keine gültige E-Mail Adresse.";
                        }
                    } else {
                        $this->errors['email'] = "Bitte gib eine E-Mail Adresse an.";
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
            "success" => $this->success,
            "recaptcha" => recaptcha::build("registration")
        );
    }


}
?>