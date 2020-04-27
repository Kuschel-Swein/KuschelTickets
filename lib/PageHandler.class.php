<?php
namespace KuschelTickets\lib;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

class PageHandler {

    public function __construct() {
        global $config;
        // prepare parameters

        $querystring = $_SERVER['QUERY_STRING'];
        $querystring = str_replace("%2F", "/", $querystring);
        $parameters = explode("/", $querystring);
        $parameters = array_reverse($parameters);
        $page = null;
        foreach($parameters as $parameter) {
            $value = "";
            if(strpos($parameter, "-") !== false) {
                $paths = explode("-", $parameter);
                $value = end($paths);
                $lastIndex = count($paths) - 1;
                if(!is_numeric($value)) {
                    $value = "";
                } else {
                    unset($paths[$lastIndex]);
                }
                $key = implode("-", $paths);
            } else {
                $key = $parameter;
            }

            
            
            $_REQUEST[$key] = $value;
            if(!empty($key)) {
                $page = $key;
            }
            
        }

        if($page == "") {
            $page = "Index";
        }

        if(!file_exists("pages/".$page."Page.class.php")) {
            throw new PageNotFoundException();
        }
        require_once("pages/".$page."Page.class.php");
        $identifier = $page;
        $page = $page."Page";
        $show = new $page($identifier);
        $show->readParameters($_REQUEST);
        $show->assignTPL($show->assign());
        $show->show();
        


    }
}