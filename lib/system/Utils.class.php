<?php
namespace kt\system;
use kt\system\Link;
use kt\system\TicketCategory;

class Utils {

    public static function redirect(String $url) {
        header("Location: ".$url);
        echo '<meta http-equiv="refresh" content="0; URL='.$url.'">';
        die();
    }

    public static function purify(String $userinput) {
        require_once "lib/3rdParty/HTMLPurifier/HTMLPurifier.auto.php";
        $secure = \HTMLPurifier_Config::createDefault();
        $secure->set('HTML.Doctype', 'XHTML 1.0 Transitional');
        $secure->set('URI.DisableExternalResources', false);
        $secure->set('URI.DisableResources', false);
        $secure->set('HTML.Allowed', 'u,a,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
        $secure->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
        $secure->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
        $secure->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
        $secure->set('HTML.SafeIframe', true);
        $secure->set('Core.EscapeInvalidTags', true);
        $secure->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
        $purifier = new \HTMLPurifier($secure);
        $text = $purifier->purify($userinput);
        $text = preg_replace("~\[ticket=(.*?)\](.*?)\[/ticket\]~s", "<a href=\"".Link::get("ticket-$1")."\" data-tooltip=\"Ticket #$1\">$2</a>", $text);
        $text = preg_replace("~\[answer=(.*?)\](.*?)\[/answer\]~s", "<a href=\"#ticketanswer$1\" data-tooltip=\"Ticketantwort #$1\">$2</a>", $text);
        return $text;
    }

    public static function randomString($length = 50) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!?/()[]#+-<>';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function httpPost($url, $data) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function generateErrorCode() {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $errorcode = "";
        for ($i = 0; $i < 40; $i++) {
            $errorcode .= $characters[rand(0, $charactersLength - 1)];
        }
        if(file_exists("./data/logs/".$errorcode.".txt")) {
            return Utils::generateErrorCode();
        }
        return $errorcode;
    }

    public static function toASCI(String $string) {
        $integer = "";
        foreach (str_split($string) as $char) {
            $integer .= sprintf("%03s", ord($char));
        }
        return $integer;
    }

    public static function fromASCI(String $integer) {
        $string = "";
        foreach (str_split($integer, 3) as $number) {
            $string .= chr($number);
        }
        return $string;
    }

    public static function makeClickableLinks(String $s) {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
    }
}