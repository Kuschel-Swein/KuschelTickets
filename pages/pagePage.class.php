<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\data\page\PageList;
use KuschelTickets\lib\exception\PageNotFoundException;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\KuschelTickets;

class pagePage extends Page {

    private $identifier;
    private $page;

    public function readParameters(Array $parameters) {
        global $templateengine;

        $identifier = null;
        foreach(new PageList() as $page) {
            if(isset($parameters[$page->url])) {
                $identifier = $page->identifier;
                $this->page = $page;
                $groups = $page->groups;
            }
        }

        if($identifier == null) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        } else {
            if($groups !== [] && KuschelTickets::getUser()->userID) {
                if(!in_array((String) KuschelTickets::getUser()->getGroup()->groupID, $groups)) {
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
            "content" => $this->page->getContent(),
            "title" => $this->page->title,
            "type" => $this->page->type
        );
    }


}
?>
