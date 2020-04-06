<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\PageContent;
use KuschelTickets\lib\Exceptions\PageNotFoundException;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;

class PagePage extends Page {

    private $identifier;

    public function readParameters(Array $parameters) {
        global $templateengine;

        $identifier = null;
        foreach(PageContent::getAll() as $page) {
            if(isset($parameters[$page['url']])) {
                $identifier = $page['identifier'];
                $groups = $page['groups'];
            }
        }

        if($identifier == null) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        } else {
            if($groups !== [] && UserUtils::isLoggedIn()) {
                $account = new User(UserUtils::getUserID());
                if(!in_array((String) $account->getGroup()->groupID, $groups)) {
                    throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
                }
            } else if($groups !== []) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
            $this->identifier = $identifier;
        }
    }

    public function assign() {
        global $templateengine;
        return array(
            "content" => PageContent::get($this->identifier),
            "title" => PageContent::getTitle($this->identifier),
            "type" => PageContent::getType($this->identifier)
        );
    }


}
?>