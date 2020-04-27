<?php
namespace KuschelTickets\lib;
use KuschelTickets\lib\Link;
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

    public static function error($error) {
        global $config; 

        if(get_class($error) !== "KuschelTickets\lib\Exceptions\PageNotFoundException" && get_class($error) !== "KuschelTickets\lib\Exceptions\AccessDeniedException") {
            $errorcode = Utils::generateErrorCode();
            $file = fopen("./data/logs/".$errorcode.".txt", "w");
            fwrite($file, "======================================================================".PHP_EOL."EXCEPTION ".date("d.m.Y H:i:s").PHP_EOL."======================================================================".PHP_EOL.PHP_EOL.var_export($error, true));
            fclose($file);
            if(!isset($_SERVER['HTTP_REFERER'])) {
                $_SERVER['HTTP_REFERER'] = "-/-";
            }
            $version = $config['version'];
            $result = '<!DOCTYPE html><html><head><title>'.$error->getMessage().' on line '.$error->getLine().'</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><style>.exceptionBody {background-color: rgb(250, 250, 250);color: rgb(44, 62, 80);margin: 0;padding: 0;}.exceptionContainer {box-sizing: border-box;font-family: \'Segoe UI\', \'Lucida Grande\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;padding-bottom: 20px;}.exceptionContainer * {box-sizing: inherit;line-height: 1.5em;margin: 0;padding: 0;}.exceptionHeader {background-color: #2185d0;padding: 30px 0;}.exceptionTitle {color: #fff;font-size: 28px;font-weight: 300;}.exceptionErrorCode {color: #fff;margin-top: .5em;}.exceptionErrorCode .exceptionInlineCode {background-color: #1678c2;border-radius: 3px;color: #fff;font-family: monospace;padding: 3px 10px;white-space: nowrap;}.exceptionSubtitle {border-bottom: 1px solid rgb(238, 238, 238);font-size: 24px;font-weight: 300;margin-bottom: 15px;padding-bottom: 10px;}.exceptionContainer > .exceptionBoundary {margin-top: 30px;}.exceptionText .exceptionInlineCodeWrapper {border: 1px solid rgb(169, 169, 169);border-radius: 3px;padding: 2px 5px;}.exceptionText .exceptionInlineCode {font-family: monospace;white-space: nowrap;}.exceptionFieldTitle {color: #4183c4;}.exceptionFieldTitle .exceptionColon {opacity: 0;}.exceptionFieldValue {font-size: 18px;min-height: 1.5em;}.exceptionSystemInformation,.exceptionErrorDetails,.exceptionStacktrace {list-style-type: none;}.exceptionSystemInformation > li:not(:first-child),.exceptionErrorDetails > li:not(:first-child) {margin-top: 10px;}.exceptionStacktrace {display: block;margin-top: 5px;overflow: auto;padding-bottom: 20px;}.exceptionStacktraceFile,.exceptionStacktraceFile span,.exceptionStacktraceCall,.exceptionStacktraceCall span {font-family: monospace !important;white-space: nowrap !important;}.exceptionStacktraceCall + .exceptionStacktraceFile {margin-top: 5px;}.exceptionStacktraceCall {padding-left: 40px;}.exceptionStacktraceCall,.exceptionStacktraceCall span {color: rgb(102, 102, 102) !important;font-size: 13px !important;}/* mobile */@media (max-width: 767px) {.exceptionBoundary {min-width: 320px;padding: 0 10px;}.exceptionText .exceptionInlineCodeWrapper {display: inline-block;overflow: auto;}.exceptionErrorCode .exceptionInlineCode {font-size: 13px;padding: 2px 5px;}}/* desktop */@media (min-width: 768px) {.exceptionBoundary {margin: 0 auto;max-width: 1400px;min-width: 1200px;padding: 0 10px;}.exceptionSystemInformation {display: flex;flex-wrap: wrap;}.exceptionSystemInformation1,.exceptionSystemInformation3,.exceptionSystemInformation5 {flex: 0 0 200px;margin: 0 0 10px 0 !important;}.exceptionSystemInformation2,.exceptionSystemInformation4,.exceptionSystemInformation6 {flex: 0 0 calc(100% - 210px);margin: 0 0 10px 10px !important;max-width: calc(100% - 210px);}.exceptionSystemInformation1 { order: 1; }.exceptionSystemInformation2 { order: 2; }.exceptionSystemInformation3 { order: 3; }.exceptionSystemInformation4 { order: 4; }.exceptionSystemInformation5 { order: 5; }.exceptionSystemInformation6 { order: 6; }.exceptionSystemInformation .exceptionFieldValue {overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}}</style></head><body class="exceptionBody"><div class="exceptionContainer"><div class="exceptionHeader"><div class="exceptionBoundary"><p class="exceptionTitle">Ein Fehler ist aufgetreten</p><p class="exceptionErrorCode">Interner Fehlercode: <span class="exceptionInlineCodeWrapper"><span class="exceptionInlineCode">'.$errorcode.'</span></span></p></div></div><div class="exceptionBoundary"><p class="exceptionSubtitle">Was ist passiert?</p><p class="exceptionText">Leider ist es bei der Verarbeitung zu einem Fehler gekommen und die Ausführung wurde abgebrochen. Falls möglich, leite bitte den oben stehenden Fehlercode an den Administrator weiter.</p><p class="exceptionText">&nbsp;</p><p class="exceptionText">Administratoren können die vollständige Fehlermeldung mit Hilfe dieses Codes in der Administrationsoberfläche unter „Allgemein » Fehler“ einsehen. Zusätzlich wurden die Informationen in die Protokolldatei <span class="exceptionInlineCodeWrapper"><span class="exceptionInlineCode">*/data/log/'.$errorcode.'.txt</span></span> geschrieben und können beispielsweise mit Hilfe eines FTP-Programms abgerufen werden.</p><p class="exceptionText">&nbsp;</p><p class="exceptionText">Hinweis: Der Fehlercode wird zufällig generiert, erlaubt keinen Rückschluss auf die Ursache und ist daher für Dritte nutzlos.</p>				</div><div class="exceptionBoundary"><p class="exceptionSubtitle">System Information</p><ul class="exceptionSystemInformation"><li class="exceptionSystemInformation1"><p class="exceptionFieldTitle">PHP Version<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.phpversion().'</p></li><li class="exceptionSystemInformation3"><p class="exceptionFieldTitle">KuschelTickets<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$version.'</p></li><li class="exceptionSystemInformation5"><p class="exceptionFieldTitle">Peak Memory Usage<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.memory_get_peak_usage().'</p></li><li class="exceptionSystemInformation2"><p class="exceptionFieldTitle">Request URI<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">/'.$_SERVER['QUERY_STRING'].'</p></li><li class="exceptionSystemInformation4"><p class="exceptionFieldTitle">Referrer<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$_SERVER['HTTP_REFERER'].'</p></li><li class="exceptionSystemInformation6"><p class="exceptionFieldTitle">User Agent<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$_SERVER['HTTP_USER_AGENT'].'</p></li></ul></div><div class="exceptionBoundary"><p class="exceptionSubtitle">Error</p><ul class="exceptionErrorDetails"><li><p class="exceptionFieldTitle">Error Type<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.get_class($error).'</p></li><li><p class="exceptionFieldTitle">Error Message<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$error->getMessage().'</p></li><li><p class="exceptionFieldTitle">File<span class="exceptionColon">:</span></p><p class="exceptionFieldValue" style="word-break: break-all">'.$error->getFile().' ('.$error->getLine().')</p></li><li><p class="exceptionFieldTitle">Stack Trace<span class="exceptionColon">:</span></p><ul class="exceptionStacktrace">';                        
            foreach($error->getTrace() as $key => $value) {
                $result = $result.'<li class="exceptionStacktraceFile">#'.$key.' '.$value['file'].' ('.$value['line'].'):</li>';
                $result = $result.'<li class="exceptionStacktraceCall">'.$value['function'].'</li>';
            }                
            $result = $result.'</li></ul></div></div></body></html>';
            die($result);
        }
        return true;
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