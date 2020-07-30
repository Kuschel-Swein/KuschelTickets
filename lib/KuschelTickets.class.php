<?php
namespace KuschelTickets\lib;

use KuschelTickets\lib\data\menu\MenuEntry;
use KuschelTickets\lib\data\menu\MenuEntryList;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\KuschelTickets;

class KuschelTickets {
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
                        $data = KuschelTickets::menuHelper($activePage, $child);
                        if(empty($active)) {
                            $active = $data['active'];
                        }
                        $childData = $childData.$data['result'];
                    }

                    $result = $result.'
                        <div class="ui dropdown navigation item'.$active.'">
                            <a class="menuLink" href="'.Link::get($entry->getLink()).'">'.$entry->title.'</a> <i class="dropdown icon"></i>
                            <div class="menu">
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
                    <a class="menuLink" href="'.Link::get($child->getLink()).'">'.$child->title.'</a> <i class="dropdown icon"></i>
                    <div class="menu">';
            foreach($childsOfChild as $childOfChild) {
                $data = KuschelTickets::menuHelper($activePage, $childOfChild);
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

    public static function getDB() {
        global $config;
        return $config['db'];
    }

    public static function getUser() {
        return (isset($_SESSION['userID'])) ? new User($_SESSION['userID']) : new User(0);
    }
}