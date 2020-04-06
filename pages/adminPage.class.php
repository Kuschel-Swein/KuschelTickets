<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\Group;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

class adminPage extends Page {

    private $file;
    private $title;
    private $site;

    public function readParameters(Array $parameters) {
        global $config;

        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("admin.acp.use")) {
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
            )
        ];

        $actualpage = null;
        foreach($pages as $page) {
            if(isset($parameters[$page['link']])) {
                if($user->hasPermission("admin.acp.page.".$page['permission'])) {
                    $actualpage = $page;
                } else {
                    throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
                }
            }
        }
        // use get here because we only want the index page
        if((isset($_GET['admin']) || isset($_GET['admin/'])) && $actualpage == null) {
            if($user->hasPermission("admin.acp.page.dashboard")) {
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
        include("pages/admin/".$actualpage['file'].".php");
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