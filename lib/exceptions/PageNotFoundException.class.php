<?php
namespace KuschelTickets\lib\Exceptions;

class PageNotFoundException extends \Exception {
    public function __construct() {
        require("pages/NotFoundPage.class.php");
        header("HTTP/1.0 404 Not Found");
        $show = new \NotFoundPage("notfound");
        $show->readParameters($_REQUEST);
        $show->assignTPL($show->assign());
        $show->show();
        parent::__construct("Page not found - 404", 404, null);
    }
       
}