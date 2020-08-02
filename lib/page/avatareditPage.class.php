<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;
use kt\system\Utils;
use kt\system\Link;
use kt\system\HttpRequest;
use kt\system\CRSF;
use kt\system\recaptcha;

class avatareditPage extends AbstractPage {

    private $type;
    private $gravatarurl;
    private $errors;
    private $success;

    public function readParameters(Array $parameters) {
        global $config;

        if(!KuschelTickets::getUser()->hasPermission("general.account.avatar")) {
            throw new AccessDeniedException();
        }

        $this->errors = array(
            "avatar" => false,
            "upload" => false,
            "token" => false
        );
        $this->success = false;
        
        $this->type = "none";
        if(Utils::startsWith(KuschelTickets::getUser()->avatar, "gravatar_")) {
            if($config['gravatar']) {
                $this->type = "gravatar";
            } else {
                $this->type = "upload";
            }
        } else if(KuschelTickets::getUser()->avatar !== "default.png") {
            $this->type = "upload";
        }

        if($config['gravatar']) {
            $this->gravatarurl = "https://www.gravatar.com/avatar/".md5(strtolower(trim(KuschelTickets::getUser()->email)))."?d=".urlencode("https://www.gravatar.com/avatar/")."&s=100";
            $this->gravatarurl = Utils::toASCII($this->gravatarurl);
            $this->gravatarurl = Link::get("imageproxy/url-".$this->gravatarurl);
        }

        $avatarTypes = ['none', 'upload'];
        if($config['gravatar']) {
            array_push($avatarTypes, "gravatar");
        }

        if(isset($parameters['submit'])) {
            if(recaptcha::validate("avataredit")) {
                if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                    if(CRSF::validate($parameters['CRSF'])) {
                        if(isset($parameters['avatar']) && !empty($parameters['avatar'])) {
                            if(in_array($parameters['avatar'], $avatarTypes)) {
                                $avatarContent = null;
                                $avatarType = null;
                                if($parameters['avatar'] == "gravatar") {
                                    $url = "https://www.gravatar.com/avatar/".md5(strtolower(trim(KuschelTickets::getUser()->email)))."?d=".urlencode("https://www.gravatar.com/avatar/")."&s=100";
                                    $httpRequest = new HttpRequest($url);
                                    $httpRequest->enableSSL();
                                    $httpRequest->setRequestType(HttpRequest::GET);
                                    $httpRequest->execute();
                                    $type = explode("/", $httpRequest->getContentType());
                                    $type = end($type);
                                    $this->type = "gravatar";
                                    $avatarType = $type;
                                    $avatarContent = $httpRequest->getResponse();
                                } else if($parameters['avatar'] == "upload") {
                                    if(isset($_FILES['upload']) && $_FILES['upload']['error'] > UPLOAD_ERR_OK) {
                                        $this->type = "upload";
                                        $this->errors['upload'] = "Bitte wähle einen Avatar aus.";
                                    } else {
                                        $fileName = $_FILES['upload']['name'];
                                        $size = $_FILES['upload']['size'];
                                        $fileTmp = $_FILES['upload']['tmp_name'];
                                        $extension = explode('.', $fileName);
                                        $extension = end($extension);
                                        if(in_array(strtolower($extension), $config['avatarextensions'])) {
                                            if($size <= $config['avatarsize']) {
                                                $this->type = "upload";
                                                $avatarContent = file_get_contents($fileTmp);
                                                $avatarType = $extension;
                                            } else {
                                                $this->type = "upload";
                                                $this->errors['upload'] = "Diese Datei ist zu groß. Bitte wähle eine andere Datei.";
                                            }
                                        } else {
                                            $this->type = "upload";
                                            $this->errors['upload'] = "Diese Dateiendung ist nicht erlaubt. Bitte wähle eine andere Datei.";
                                        }
                                    }
                                }
                                $worked = true;
                                foreach($this->errors as $error) {
                                    if($error !== false) {
                                        $worked = false;
                                        break;
                                    }
                                }
                                if($worked) {
                                    $fileName = "";
                                    if(KuschelTickets::getUser()->avatar !== "default.png") {
                                        if(file_exists("./data/avatars/".KuschelTickets::getUser()->avatar)) {
                                            unlink("./data/avatars/".KuschelTickets::getUser()->avatar);
                                        }
                                    }
                                    if($parameters['avatar'] == "none") {
                                        $fileName = "default.png";
                                        $this->type = "none";
                                    } else {
                                        $fileName = $this->generateFileName(($this->type == "gravatar"), $avatarType);
                                        file_put_contents("./data/avatars/".$fileName, $avatarContent);
                                    }
                                    KuschelTickets::getUser()->update(array(
                                        "avatar" => $fileName
                                    ));
                                    $this->success = "Dein Avatar wrde erfolgreich gespeichert.";
                                }
                            } else {
                                $this->errors['avatar'] = "Bitte wähle einen validen Avatartyp aus.";
                            }
                        } else {
                            $this->errors['avatar'] = "Bitte wähle den Typ deines Avatars aus.";
                        }
                    } else {
                        $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                    }
                } else {
                    $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                }
            } else {
                $this->errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
            }
        }
    }

    public function generateFileName(bool $isGravatar, String $extension) {
        $fileName = ($isGravatar) ? "gravatar_" : "";
        $fileName = $fileName.Utils::randomString(50, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ').".".$extension;
        if(file_exists("./data/avatars/".$fileName)) {
            return $this->generateFileName($isGravatar, $extension);
        }
        return $fileName;
    }

    public function assign() {
        KuschelTickets::getTPL()->assign(array(
            "type" => $this->type,
            "gravatarurl" => $this->gravatarurl,
            "success" => $this->success,
            "errors" => $this->errors,
            "recaptcha" => recaptcha::build("avataredit")
        ));
    }
}