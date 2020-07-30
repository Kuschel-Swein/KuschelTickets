<?php
namespace KuschelTickets\lib;

class HttpRequest {

    const POST = 'POST';
    const GET = 'GET';

    private $address;

    public $userAgent = 'Mozilla/5.0 (compatible; KuschelTickets)';
    public $connectTimeout = 10;
    public $timeout = 15;

    private $cookiesEnabled = false;
    private $cookiePath;

    private $ssl = false;

    private $requestType;
    private $postFields;

    private $userpwd;
    private $latency;
    private $responseBody;
    private $responseHeader;
    private $httpCode;
    private $error;

    public function __construct($address) {
        if (!isset($address)) {
            throw new Exception("Error: Address not provided.");
        }
        $this->address = $address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setBasicAuthCredentials($username, $password) {
        $this->userpwd = $username . ':' . $password;
    }

    public function enableCookies($cookie_path) {
        $this->cookiesEnabled = true;
        $this->cookiePath = $cookie_path;
    }

    public function disableCookies() {
        $this->cookiesEnabled = false;
        $this->cookiePath = '';
    }

    public function enableSSL() {
        $this->ssl = true;
    }

    public function disableSSL() {
        $this->ssl = false;
    }

    public function setTimeout($timeout = 15) {
        $this->timeout = $timeout;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function setConnectTimeout($connectTimeout = 10) {
        $this->connectTimeout = $connectTimeout;
    }

    public function getConnectTimeout() {
        return $this->connectTimeout;
    }

    public function setRequestType($type) {
        $this->requestType = $type;
    }

    public function setPostFields($fields = array()) {
        $this->postFields = $fields;
    }

    public function getResponse() {
        return $this->responseBody;
    }
    public function getHeader() {
        return $this->responseHeader;
    }

    public function getHttpCode() {
        return $this->httpCode;
    }

    public function getLatency() {
        return $this->latency;
    }

    public function getError() {
        return $this->error;
    }

    public function checkResponseForContent($content = '') {
        if ($this->httpCode == 200 && !empty($this->responseBody)) {
            if (strpos($this->responseBody, $content) !== false) {
                return true;
            }
        }
        return false;
    }

    public function execute() {
        $latency = 0;

        $ch = curl_init();
        if (isset($this->userpwd)) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->userpwd);
        }
        if ($this->cookiesEnabled) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);
        }
        if (isset($this->requestType)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->requestType);
            if ($this->requestType == 'POST' && isset($this->postFields)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postFields);
            }
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->address);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        curl_close($ch);

        $this->responseHeader = substr($response, 0, $header_size);
        $this->responseBody = substr($response, $header_size);
        $this->error = $error;
        $this->httpCode = $http_code;

        $this->latency = round($time * 1000);
    }
}