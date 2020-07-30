<?php
namespace kt\page;

use kt\system\Page;
use kt\data\user\User;
use kt\system\Utils;
use kt\data\user\group\Group;
use kt\system\UserUtils;
use kt\system\CRSF;
use kt\system\exception\AccessDeniedException;
use kt\system\exception\PageNotFoundException;
use kt\system\KuschelTickets;

class adminPage extends Page {

    private $file;
    private $title;
    private $site;

    public function readParameters(Array $parameters) {
        global $config;

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("admin.acp.use")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $pages = [
            array(
                "permission" => "faq",
                "link" => "faq",
                "file" => "faq",
                "title" => "FAQ"
            ),
            array(
                "permission" => "pages",
                "link" => "pages",
                "file" => "pages",
                "title" => "Seiten"
            ),
            array(
                "permission" => "faqcategories",
                "link" => "faqcategories",
                "file" => "faqcategories",
                "title" => "FAQ Kategorien"
            ),
            array(
                "permission" => "settings",
                "link" => "settings",
                "file" => "settings",
                "title" => "Einstellungen"
            ),
            array(
                "permission" => "accounts",
                "link" => "accounts",
                "file" => "accounts",
                "title" => "Accounts"
            ),
            array(
                "permission" => "groups",
                "link" => "groups",
                "file" => "groups",
                "title" => "Gruppen"
            ),
            array(
                "permission" => "ticketcategories",
                "link" => "ticketcategories",
                "file" => "ticketcategories",
                "title" => "Ticket Kategorien"
            ),
            array(
                "permission" => "cleanup",
                "link" => "cleanup",
                "file" => "cleanup",
                "title" => "Aufräumarbeiten"
            ),
            array(
                "permission" => "errors",
                "link" => "errors",
                "file" => "errors",
                "title" => "Fehler"
            ),
            array(
                "permission" => "menuentries",
                "link" => "menuentries",
                "file" => "menuentries",
                "title" => "Menüeinträge"
            )
        ];

        $actualpage = null;
        foreach($pages as $page) {
            if(isset($parameters[$page['link']])) {
                if(KuschelTickets::getUser()->hasPermission("admin.acp.page.".$page['permission'])) {
                    $actualpage = $page;
                } else {
                    throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
                }
            }
        }
        // use get here because we only want the index page
        if((isset($_GET['admin']) || isset($_GET['admin/'])) && $actualpage == null) {
            if(KuschelTickets::getUser()->hasPermission("admin.acp.page.dashboard")) {
                $actualpage = array(
                    "file" => "index",
                    "title" => "Dashboard"
                );
            } else {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
        } 
        if($actualpage == null) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }
        $this->file = "admin/".$actualpage['file'].".tpl";
        $this->title = $actualpage['title'];
        include_once "pages/admin/".$actualpage['file'].".php";
        $this->site = $site;

    }

    public function assign() {
        return array(
            "title" => $this->title,
            "file" => $this->file,
            "site" => $this->site
        );
    }


}
?>