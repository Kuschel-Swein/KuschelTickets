<?php
namespace KuschelTickets\lib;

class Link {

    public static function get(String $url) {
        global $config;

        if(filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        if($url !== "") {
            $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
            if($config['seourls']) {
                $mainurl = str_replace("index.php", "", $mainurl);
                $endurl = $mainurl.$url."/";
            } else {
                $endurl = $mainurl."?".$url."/";
            }
        } else {
            $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
            if($config['seourls']) {
                $mainurl = str_replace("index.php", "", $mainurl);
                $endurl = $mainurl."/";
            } else {
                $endurl = $mainurl."/";
            }
        }
        return $endurl;
    }

    public static function mainurl() {
        $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        $mainurl = str_replace("index.php", "", $mainurl);
        return $mainurl;
    }
}