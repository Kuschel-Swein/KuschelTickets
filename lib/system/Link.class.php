<?php
namespace kt\system;

use kt\system\Utils;

class Link {

    public static function get(String $url) {
        global $config;

        if(filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        if(!$config['seourls']) {
            $url = str_replace("?", "&", $url);
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
        if(Utils::endsWith($endurl, "//")) {
            return self::mainurl();
        }
        return $endurl;
    }

    public static function mainurl() {
        $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        $mainurl = str_replace("index.php", "", $mainurl);
        return $mainurl;
    }
}