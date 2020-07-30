<?php
namespace kt\page;

use kt\system\Page;
use kt\system\KuschelTickets;
use kt\data\faq\category\CategoryList;
use kt\system\exception\AccessDeniedException;

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
            "categories" => new CategoryList()
        );
    }


}
?>