<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\Oauth;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\Exceptions\PageNotFoundException;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\Link;

class oauthPage extends Page {

    private $messages = null;
    private $type = null;

    public function readParameters(Array $parameters) {
        global $config;
        
        if(UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $type = null;
        if(isset($parameters['oauth']) && !empty($parameters['oauth'])) {
            $type = $parameters['oauth'];
        }
        /*
         * 1 => Google Oauth
         * 2 => GitHub Oauth
        */
        if($type == "1") {
            if($config['oauth']['google']['use']) {
                require_once(Oauth::getGooglePath());
                if(isset($_GET['code'])) {
                    $client = Oauth::getGoogle();
                    var_dump($_GET);
                    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    if(!isset($token['access_token'])) {
                        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
                    }
                    try {
                        $client->setAccessToken($token['access_token']);
                    } catch(InvalidArgumentException $e) {
                        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
                    }
                    $google_oauth = new Google_Service_Oauth2($client);
                    $google_account_info = $google_oauth->userinfo->get();
                    $email =  $google_account_info->email;
                    $name =  $google_account_info->name;
                    if(UserUtils::exists($email, "email") && UserUtils::exists($name, "username")) {
                        $user = UserUtils::getByMail($email);
                        if($user->hasPermission("general.login")) {
                            UserUtils::loginAs($user, $user->getHash());
                            Utils::redirect(Link::get(""));
                            $this->type = "success";
                            $this->messages = ["Du wurdest erfolgreich eingeloggt, und wirst auch gleich weitergeleitet."];
                        } else {
                            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
                        }
                    } else {
                        $password = Utils::randomString(700);
                        $name = Oauth::getUserName($name);
                        UserUtils::create($name, $email, $password, "1");
                        $this->type = "success";
                        $this->messages = ["Es wurde erfolgreich ein Account mit der E-Mail Adresse ".$email." angelegt.", "Bitte bestätige nun deine E-Mail Adresse, danach kannst du deinen Account komplett nutzen."];
                    }
                }
            }
        } else if($type == "2") {
            if($config['oauth']['github']['use']) {
                if(isset($_GET['code']) && isset($_GET['state'])) {
                    if(CRSF::validate($_GET['state'])) {
                        $client = Oauth::getGitHub();
                        $token = $client->getAccessToken($_GET['state'], $_GET['code']);
                        $github_user = $client->apiRequest($token);
                        $email = $github_user->email;
                        $name = $github_user->login;
                        if(!empty($email) && !empty($name)) {
                            if(UserUtils::exists($email, "email") && UserUtils::exists($name, "username")) { 
                                $user = UserUtils::getByMail($email);
                                if($user->hasPermission("general.login")) {
                                    UserUtils::loginAs($user, $user->getHash());
                                    Utils::redirect(Link::get(""));
                                    $this->type = "success";
                                    $this->messages = ["Du wurdest erfolgreich eingeloggt, und wirst auch gleich weitergeleitet."];
                                } else {
                                    throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
                                }
                            } else {
                                $password = Utils::randomString(700);
                                $name = Oauth::getUserName($name);
                                UserUtils::create($name, $email, $password, "2");
                                $this->type = "success";
                                $this->messages = ["Es wurde erfolgreich ein Account mit der E-Mail Adresse ".$email." angelegt.", "Bitte bestätige nun deine E-Mail Adresse, danach kannst du deinen Account komplett nutzen."];
                            }
                        } else {
                            throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
                        }
                    } else {
                        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
                    }
                } else {
                    throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
                }
            }
        } else {
            throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
        }
    }

    public function assign() {
       return array(
           "messages" => $this->messages,
           "type" => $this->type
       );
    }


}
?>