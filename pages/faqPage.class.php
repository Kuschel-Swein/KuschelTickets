<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\KuschelTickets;
use KuschelTickets\lib\data\faq\category\CategoryList;
use KuschelTickets\lib\exception\AccessDeniedException;

class faqPage extends Page {

    public function readParameters(Array $parameters) {
        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.view.faq")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
    }

    public function assign() {
        return array(
            "categorys" => new CategoryList()
        );
    }


}
?>