<?php
namespace kt\system\exception;

class PageNotFoundException extends SystemException implements IPrintableException {

    public function show() {
        header("HTTP/1.0 404 Not Found");
        $show = new \kt\page\NotFoundPage("notfound");
        $show->readParameters($_REQUEST);
        $show->assign();
        $show->show();
    }  
}