<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\FAQ;
use KuschelTickets\lib\Exceptions\AccessDeniedException;

class faqPage extends Page {

    public function readParameters(Array $parameters) {
        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.view.faq")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
    }

    public function assign() {
        return array(
            "categorys" => FAQ::getCategorys()
        );
    }


}
?>