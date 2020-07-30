<?php
namespace kt\system;

class KuschelTickets {

    private static $templateEngine;
    private static $user;

    public function __construct() {
        ob_start();

        require "data/data.php";
        require "lib/3rdParty/smarty/Smarty.class.php";

        error_reporting(0);
        if($config['debugging']['php']) {
            error_reporting(-1);
        }
        if($config['debugging']['database']) {
            $config['db']->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        session_name($config['cookie']);
        session_start();
        KuschelTickets::$templateEngine = new \Smarty();

        set_exception_handler([KuschelTickets::class, 'handleException']);
        set_error_handler([KuschelTickets::class, 'handleError']);
        spl_autoload_register([KuschelTickets::class, 'autoloader']);

        KuschelTickets::$user = (isset($_SESSION['userID'])) ? new \kt\data\user\User($_SESSION['userID']) : new \kt\data\user\User(0);
        if(KuschelTickets::$user->userID) {
            KuschelTickets::$user->validateHash();
        }
        new \kt\system\page\PageHandler();
    }

    public static function autoloader(String $className) {
        $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
        $className = substr($className, 2);
        $className = "lib".$className.".class.php";
        if(file_exists($className)) {
            include_once $className;
        }
    }

    public static function handleError(int $errorNumber, String $errorString, String $errorFile, int $errorLine) {
        throw new \ErrorException($errorString, 0, $errorNumber, $errorFile, $errorLine);
    }

    public static function handleException(\Throwable $exception) {
        global $config;
        ob_end_clean();
        if($exception instanceof \kt\system\exception\IPrintableException) {
            $exception->show();
        } else {
            try {
                $errorcode = \kt\system\Utils::generateErrorCode();
                $file = fopen("./data/logs/".$errorcode.".txt", "w");
                fwrite($file, "======================================================================".PHP_EOL."EXCEPTION ".date("d.m.Y H:i:s").PHP_EOL."======================================================================".PHP_EOL.PHP_EOL.var_export($exception, true));
                fclose($file);
                if(!isset($_SERVER['HTTP_REFERER'])) {
                    $_SERVER['HTTP_REFERER'] = "-/-";
                }
                $version = $config['version'];
                $result = '<!DOCTYPE html><html><head><title>'.$exception->getMessage().' on line '.$exception->getLine().'</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><style>.exceptionBody {background-color: rgb(250, 250, 250);color: rgb(44, 62, 80);margin: 0;padding: 0;}.exceptionContainer {box-sizing: border-box;font-family: \'Segoe UI\', \'Lucida Grande\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;font-size: 14px;padding-bottom: 20px;}.exceptionContainer * {box-sizing: inherit;line-height: 1.5em;margin: 0;padding: 0;}.exceptionHeader {background-color: #2185d0;padding: 30px 0;}.exceptionTitle {color: #fff;font-size: 28px;font-weight: 300;}.exceptionErrorCode {color: #fff;margin-top: .5em;}.exceptionErrorCode .exceptionInlineCode {background-color: #1678c2;border-radius: 3px;color: #fff;font-family: monospace;padding: 3px 10px;white-space: nowrap;}.exceptionSubtitle {border-bottom: 1px solid rgb(238, 238, 238);font-size: 24px;font-weight: 300;margin-bottom: 15px;padding-bottom: 10px;}.exceptionContainer > .exceptionBoundary {margin-top: 30px;}.exceptionText .exceptionInlineCodeWrapper {border: 1px solid rgb(169, 169, 169);border-radius: 3px;padding: 2px 5px;}.exceptionText .exceptionInlineCode {font-family: monospace;white-space: nowrap;}.exceptionFieldTitle {color: #4183c4;}.exceptionFieldTitle .exceptionColon {opacity: 0;}.exceptionFieldValue {font-size: 18px;min-height: 1.5em;}.exceptionSystemInformation,.exceptionErrorDetails,.exceptionStacktrace {list-style-type: none;}.exceptionSystemInformation > li:not(:first-child),.exceptionErrorDetails > li:not(:first-child) {margin-top: 10px;}.exceptionStacktrace {display: block;margin-top: 5px;overflow: auto;padding-bottom: 20px;}.exceptionStacktraceFile,.exceptionStacktraceFile span,.exceptionStacktraceCall,.exceptionStacktraceCall span {font-family: monospace !important;white-space: nowrap !important;}.exceptionStacktraceCall + .exceptionStacktraceFile {margin-top: 5px;}.exceptionStacktraceCall {padding-left: 40px;}.exceptionStacktraceCall,.exceptionStacktraceCall span {color: rgb(102, 102, 102) !important;font-size: 13px !important;}/* mobile */@media (max-width: 767px) {.exceptionBoundary {min-width: 320px;padding: 0 10px;}.exceptionText .exceptionInlineCodeWrapper {display: inline-block;overflow: auto;}.exceptionErrorCode .exceptionInlineCode {font-size: 13px;padding: 2px 5px;}}/* desktop */@media (min-width: 768px) {.exceptionBoundary {margin: 0 auto;max-width: 1400px;min-width: 1200px;padding: 0 10px;}.exceptionSystemInformation {display: flex;flex-wrap: wrap;}.exceptionSystemInformation1,.exceptionSystemInformation3,.exceptionSystemInformation5 {flex: 0 0 200px;margin: 0 0 10px 0 !important;}.exceptionSystemInformation2,.exceptionSystemInformation4,.exceptionSystemInformation6 {flex: 0 0 calc(100% - 210px);margin: 0 0 10px 10px !important;max-width: calc(100% - 210px);}.exceptionSystemInformation1 { order: 1; }.exceptionSystemInformation2 { order: 2; }.exceptionSystemInformation3 { order: 3; }.exceptionSystemInformation4 { order: 4; }.exceptionSystemInformation5 { order: 5; }.exceptionSystemInformation6 { order: 6; }.exceptionSystemInformation .exceptionFieldValue {overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}}</style></head><body class="exceptionBody"><div class="exceptionContainer"><div class="exceptionHeader"><div class="exceptionBoundary"><p class="exceptionTitle">Ein Fehler ist aufgetreten</p><p class="exceptionErrorCode">Interner Fehlercode: <span class="exceptionInlineCodeWrapper"><span class="exceptionInlineCode">'.$errorcode.'</span></span></p></div></div><div class="exceptionBoundary"><p class="exceptionSubtitle">Was ist passiert?</p><p class="exceptionText">Leider ist es bei der Verarbeitung zu einem Fehler gekommen und die Ausführung wurde abgebrochen. Falls möglich, leite bitte den oben stehenden Fehlercode an den Administrator weiter.</p><p class="exceptionText">&nbsp;</p><p class="exceptionText">Administratoren können die vollständige Fehlermeldung mit Hilfe dieses Codes in der Administrationsoberfläche unter „Allgemein » Fehler“ einsehen. Zusätzlich wurden die Informationen in die Protokolldatei <span class="exceptionInlineCodeWrapper"><span class="exceptionInlineCode">*/data/log/'.$errorcode.'.txt</span></span> geschrieben und können beispielsweise mit Hilfe eines FTP-Programms abgerufen werden.</p><p class="exceptionText">&nbsp;</p><p class="exceptionText">Hinweis: Der Fehlercode wird zufällig generiert, erlaubt keinen Rückschluss auf die Ursache und ist daher für Dritte nutzlos.</p>				</div><div class="exceptionBoundary"><p class="exceptionSubtitle">System Information</p><ul class="exceptionSystemInformation"><li class="exceptionSystemInformation1"><p class="exceptionFieldTitle">PHP Version<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.phpversion().'</p></li><li class="exceptionSystemInformation3"><p class="exceptionFieldTitle">KuschelTickets<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$version.'</p></li><li class="exceptionSystemInformation5"><p class="exceptionFieldTitle">Peak Memory Usage<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.memory_get_peak_usage().'</p></li><li class="exceptionSystemInformation2"><p class="exceptionFieldTitle">Request URI<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">/'.$_SERVER['QUERY_STRING'].'</p></li><li class="exceptionSystemInformation4"><p class="exceptionFieldTitle">Referrer<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$_SERVER['HTTP_REFERER'].'</p></li><li class="exceptionSystemInformation6"><p class="exceptionFieldTitle">User Agent<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$_SERVER['HTTP_USER_AGENT'].'</p></li></ul></div><div class="exceptionBoundary"><p class="exceptionSubtitle">Error</p><ul class="exceptionErrorDetails"><li><p class="exceptionFieldTitle">Error Type<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.get_class($exception).'</p></li><li><p class="exceptionFieldTitle">Error Message<span class="exceptionColon">:</span></p><p class="exceptionFieldValue">'.$exception->getMessage().'</p></li><li><p class="exceptionFieldTitle">File<span class="exceptionColon">:</span></p><p class="exceptionFieldValue" style="word-break: break-all">'.$exception->getFile().' ('.$exception->getLine().')</p></li><li><p class="exceptionFieldTitle">Stack Trace<span class="exceptionColon">:</span></p><ul class="exceptionStacktrace">';                        
                foreach($exception->getTrace() as $key => $value) {
                    $result = $result.'<li class="exceptionStacktraceFile">#'.$key.' '.$value['file'].' ('.$value['line'].'):</li>';
                    $result = $result.'<li class="exceptionStacktraceCall">'.$value['function'].'</li>';
                }                
                $result = $result.'</li></ul></div></div></body></html>';
                die($result);
            } catch(\Exception $e) {
                die("Error while handling error: ".$exception->getMessage());
            }
        }
        return true;
    } 

    public static function getDB() {
        global $config;
        return $config['db'];
    }

    public static function getUser() {
        return self::$user;
    }

    public static function getTPL() {
        return self::$templateEngine;
    }
}