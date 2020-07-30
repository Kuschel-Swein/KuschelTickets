<?php
use KuschelTickets\lib\HttpRequest;
$site = false;
$httpRequest = new HttpRequest("https://api.github.com/repos/Kuschel-Swein/KuschelTickets/releases/latest");
$httpRequest->enableSSL();
$httpRequest->setRequestType(HttpRequest::GET);
$httpRequest->execute();
$response = $httpRequest->getResponse();
try {
    $response = json_decode($response);
    if($config['version'] !== $response->tag_name) {
        $site = true;
    }
} catch(Exception $e) {
    // ignore
}