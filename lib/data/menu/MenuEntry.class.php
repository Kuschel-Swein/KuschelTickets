<?php
namespace KuschelTickets\lib\data\menu;

use KuschelTickets\lib\data\DatabaseObject;
use KuschelTickets\lib\data\menu\MenuEntryList;
use KuschelTickets\lib\KuschelTickets;
use KuschelTickets\lib\data\user\User;

class MenuEntry extends DatabaseObject {
    public $tableName = "menu";
    public $tablePrimaryKey = "menuID";

    public function getParent() {
        if($this->parent == null) {
            return null;
        } else {
            return new MenuEntry($this->parent);
        }
    }

    public function getChilds() {
        return new MenuEntryList(array(
            "parent" => $this->menuID
        ));
    }

    public function hasParent() {
        return $this->getParent() !== null;
    }

    public function isSystemPage() {
        $controller = $this->controller;
        $data = DATA;
        foreach($data['topnavigation'] as $entry) {
            if($entry['identifier'] == $controller) {
                return true;
            }
        }
        return false;
    }

    public function canView(User $user = null) {
        if($user == null) {
            $user = KuschelTickets::getUser();
        }
        if(!$this->isSystemPage()) {
            if(!$user->userID) {
                if(empty($this->groups)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if(empty($this->groups)) {
                    return true;
                } else if(in_array($user->getGroup()->groupID, $this->groups)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function getLink() {
        $link = $this->controller;
        if($link == "Index") {
            return "";
        }
        return $link;
    }

    public function getPermission() {
        if($this->isSystemPage()) {
            $data = DATA;
            foreach($data['topnavigation'] as $entry) {
                if($entry['identifier'] == $this->controller) {
                    return $entry['permission'];
                }
            }
            return "";
        } else {
            return "";
        }
    }
}