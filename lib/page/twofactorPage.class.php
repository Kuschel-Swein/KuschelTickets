<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;
use kt\system\recaptcha;
use kt\system\CRSF;
use kt\system\Utils;
use kt\system\TOTP;
use kt\system\mailer\Mailer;

class twofactorPage extends AbstractPage {

    private $errors;
    private $success;

    public function readParameters(Array $parameters) {
        global $config;

        $this->errors = array(
            "code" => false,
            "token" => false
        );
        $this->success = false;

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.account.twofactor")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        
        if(KuschelTickets::getUser()->twofactor->use !== true) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        
        if(isset($_SESSION['twofactor'])) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(isset($parameters['submit'])) {
            if(recaptcha::validate("twofactor")) {
                if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                    if(CRSF::validate($parameters['CRSF'])) {
                        if(isset($parameters['code']) && !empty($parameters['code'])) {
                            $totp = new TOTP();
                            $validCode = $totp->verifyCode(KuschelTickets::getUser()->twofactor->code, $parameters['code'], 2);
                            $backupCode = in_array($parameters['code'], KuschelTickets::getUser()->twofactor->backupcodes);
                            if($validCode || $backupCode) {
                                $_SESSION['twofactor'] = time();
                                if($backupCode) {
                                    $search = array_search($parameters['code'], KuschelTickets::getUser()->twofactor->backupcodes);
                                    $backupCodes = KuschelTickets::getUser()->twofactor->backupcodes;
                                    $backupCodes[$search] = Utils::randomString();
                                    $twofactor = (Array) KuschelTickets::getUser()->twofactor;
                                    $twofactor['backupcodes'] = $backupcodes;
                                    $twofactor = (object) $twofactor;
                                    KuschelTickets::getUser()->update(array(
                                        "twofactor" => $twofactor
                                    ));
                                    $mail = new Mailer(KuschelTickets::getUser()->email, $config['pagetitle']." - Backupcodes", KuschelTickets::getUser()->username);
                                    $message = "<p>Hey ".KuschelTickets::getUser()->username.",</p>
                                    <p>auf der Website ".$config['pagetitle']." wurde für die 2-Faktor Authentisierung ein Backupcode benutzt. Du erhälst hier nun deine neuen Backupcodes.</p>
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
                                }
                                $this->success = "Du wurdest erfolgreich verifiziert.";
                            } else {
                                $this->errors['code'] = "Bitte gib einen validen Code an.";
                            }
                        } else {
                            $this->errors['code'] = "Bitte gib einen Code an.";
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

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "errors" => $this->errors,
            "success" => $this->success,
            "recaptcha" => recaptcha::build("twofactor")
        ));
    }
}