<?php
namespace KuschelTickets\lib\Exceptions;

class AccessDeniedException extends \Exception {
    public function __construct() {
        require("pages/AccessDeniedPage.class.php");
        $show = new \AccessDeniedPage("accessdenied");
        $show->readParameters($_REQUEST);
        $show->assignTPL($show->assign());
        $show->show();
        parent::__construct("Access denied - 403", 403, null);
    }
       
}