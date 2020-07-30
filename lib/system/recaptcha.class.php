<?php
namespace kt\system;
use kt\system\Utils;
use kt\system\HttpRequest;

class recaptcha {

    public static function build(String $usecase) {
        global $config;

        if($config['recaptcha']['use'] == true && in_array($usecase, $config['recaptcha']['cases'])) {
            $version = $config['recaptcha']['version'];
            if($version == 2) {
                // recaptcha v2
                $return = '<div class="g-recaptcha" data-sitekey="'.$config['recaptcha']['public'].'"></div><br>';
                $return = $return.'<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
            } else if($version == 3) {
                // recaptcha v3
                $return = '<input name="g-recaptcha-response" type="hidden" id="g-recaptcha-response">';
                $return = $return.'<script src="https://www.google.com/recaptcha/api.js?render='.$config['recaptcha']['public'].'"></script>';
                $return = $return.'<script>
                        grecaptcha.ready(function() {
                            grecaptcha.execute("'.$config['recaptcha']['public'].'", {action: "homepage"}).then(function(token) {
                                document.getElementById("g-recaptcha-response").value = token;
                            });
                        });
                    </script>';
            }
            return $return;
        } else {
            return "";
        }
    }

    public static function validate(String $usecase) {
        global $config;

        if($config['recaptcha']['use'] == true && in_array($usecase, $config['recaptcha']['cases'])) {
            $version = $config['recaptcha']['version'];
            if($version == 2) {
                $httpRequest = new HttpRequest("https://www.google.com/recaptcha/api/siteverify");
                $httpRequest->enableSSL();
                $httpRequest->setRequestType(HttpRequest::POST);
                $httpRequest->setPostFields(array(
                    "secret" => $config['recaptcha']['secret'],
                    "response" => $_POST['g-recaptcha-response']
                ));
                $httpRequest->execute();
                $response = $httpRequest->getResponse();
                $response = json_decode($response, true);
                return $response['success'];
            } else if($version == 3) {
                $httpRequest = new HttpRequest("https://www.google.com/recaptcha/api/siteverify?secret=".$config['recaptcha']['secret']."&response=".$_POST['g-recaptcha-response']);
                $httpRequest->enableSSL();
                $httpRequest->setRequestType(HttpRequest::GET);
                $httpRequest->execute();
                $response = $httpRequest->getResponse();
                $response = json_decode($response);
                if($response->success == true){
                    if($response->score >= 0.6){
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return true;
        }
    }
}