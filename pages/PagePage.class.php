<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\PageContent;
use KuschelTickets\lib\Exceptions\PageNotFoundException;
use KuschelTickets\lib\Exceptions\AccessDenied;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;

class PagePage extends Page {

    private $identifier;

    public function readParameters(Array $parameters) {
        $identifier = null;
        foreach(PageContent::getAll() as $page) {
            if(isset($parameters[$page['url']])) {
                $identifier = $page['identifier'];
            }
        }

        if($identifier == null) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        } else {
            if($page['login']) {
                $account = new User(UserUtils::getUserID());
                if(!$account->hasPermission("general.view.pages")) {
                    throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
                }
            }
            $this->identifier = $identifier;
        }
    }

    public function assign() {
        return array(
            "content" => PageContent::get($this->identifier),
            "title" => PageContent::getTitle($this->identifier)
        );
    }


}
?>