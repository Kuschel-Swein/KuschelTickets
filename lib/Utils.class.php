<?php
namespace KuschelTickets\lib;
use KuschelTickets\lib\system\TicketCategory;

class Utils {

    public static function redirect(String $url) {
        echo '<meta http-equiv="refresh" content="0; URL='.$url.'">';
    }

    public static function getCategorys() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys");
        $stmt->execute();
        $categorys = [];
        while($row = $stmt->fetch()) {
            array_push($categorys, new TicketCategory($row['categoryID']));
        }
        return $categorys;
    }

    public static function purify(String $userinput) {
        require("lib/HTMLPurifier/HTMLPurifier.auto.php");
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
        return $text;
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
}