<?php
namespace kt\data\menu;

use kt\data\DatabaseObject;
use kt\data\menu\MenuEntryList;
use kt\system\KuschelTickets;
use kt\data\user\User;
use kt\system\Link;

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
        ), "ORDER BY sorting ASC");
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

    
    public static function buildMenu(String $activePage) {
        global $config;
        $menu = new MenuEntryList([], "ORDER BY sorting ASC");
        $result = "";
        foreach($menu as $entry) {
            if(!$entry->hasParent()) {
                $active = "";
                if($activePage == $entry->controller) {
                    $active = " active";
                }
                if($entry->isSystemPage()) {
                    if(KuschelTickets::getUser()->userID) {
                        if(!KuschelTickets::getUser()->hasPermission($entry->getPermission())) {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    if(!$entry->canView(KuschelTickets::getUser())) {
                        continue;
                    }
                }
                $childs = $entry->getChilds();
                if(count($childs) == 0) {
                    $result = $result.'<a class="item'.$active.'" href="'.Link::get($entry->getLink()).'">'.$entry->title.'</a>';
                } else {
                    if($active == " active") {
                        $active = " visualactive";
                    }
                    $childData = "";
                    foreach($childs as $child) {
                        $data = MenuEntry::menuHelper($activePage, $child);
                        if(empty($active)) {
                            $active = $data['active'];
                        }
                        $childData = $childData.$data['result'];
                    }

                    $result = $result.'
                        <div class="ui dropdown navigation item'.$active.'">
                            <a class="menuLink" href="'.Link::get($entry->getLink()).'">'.$entry->title.'</a> <i class="dropdown icon"></i>
                            <div class="menu mainmenu">
                                '.$childData.'
                            </div>
                        </div>';
               
                }
            }
        }
        return $result;
    }

    public static function menuHelper(String $activePage, MenuEntry $child) {
        $result = "";
        $active = "";
        if($child->isSystemPage()) {
            if(KuschelTickets::getUser()->userID) {
                if(!KuschelTickets::getUser()->hasPermission($child->getPermission())) {
                    return $result;
                }
            } else {
                return $result;
            }
        } else {
            if(!$child->canView(KuschelTickets::getUser())) {
                return $result;
            }
        }
        $activeChild = "";
        if($activePage == $child->controller) {
            $activeChild = " active";
            $active = " visualactive";
        }
        $childsOfChild = $child->getChilds();
        if(count($childsOfChild) == 0) {
            $result = $result.'<a class="item'.$activeChild.'" href="'.Link::get($child->getLink()).'">'.$child->title.'</a>';
        } else {
            if($activeChild == " active") {
                $activeChild = " visualactive";
            }
            $result = $result.'
                <div class="ui dropdown child navigation item'.$activeChild.'">
                    <a class="menuLink" href="'.Link::get($child->getLink()).'">'.$child->title.' <i class="dropdown icon"></i></a>
                    <div class="menu">';
            foreach($childsOfChild as $childOfChild) {
                $data = MenuEntry::menuHelper($activePage, $childOfChild);
                if(empty($active)) {
                    $active = $data['active'];
                }
                $result = $result.$data['result'];
            }
            $result = $result.'</div></div>';
        }
        return array(
            "active" => $active,
            "result" => $result
        );
    }
}