<?php
namespace kt\system\exception;

class AccessDeniedException extends SystemException implements IPrintableException {

    public function show() {
        header('HTTP/1.0 403 Forbidden');
        $show = new \kt\page\AccessDeniedPage("accessdenied");
        $show->readParameters($_REQUEST);
        $show->assignTPL($show->assign());
        $show->show();
    }   
}