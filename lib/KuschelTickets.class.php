<?php
namespace KuschelTickets\lib;
use KuschelTickets\lib\system\MenuEntry;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\system\UserUtils;

class KuschelTickets {
    public static function buildMenu(String $activePage) {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu ORDER BY sorting ASC");
        $stmt->execute();
        $result = "";
        $menu = [];
        while($row = $stmt->fetch()) {
            array_push($menu, new MenuEntry($row['menuID']));
        }
        foreach($menu as $entry) {
            if(!$entry->hasParent()) {
                $active = "";
                if($activePage == $entry->getController()) {
                    $active = " active";
                }
                if($entry->isSystemPage()) {
                    if(UserUtils::isLoggedIn()) {
                        if(!UserUtils::getUser()->hasPermission($entry->getPermission())) {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    if(!$entry->canView(UserUtils::getUser())) {
                        continue;
                    }
                }
                $childs = $entry->getChilds();
                if(empty($childs)) {
                    $result = $result.'<a class="item'.$active.'" href="'.Link::get($entry->getLink()).'">'.$entry->getTitle().'</a>';
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
                            <a class="menuLink" href="'.Link::get($entry->getLink()).'">'.$entry->getTitle().'</a> <i class="dropdown icon"></i>
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
            if(UserUtils::isLoggedIn()) {
                if(!UserUtils::getUser()->hasPermission($child->getPermission())) {
                    return $result;
                }
            } else {
                return $result;
            }
        } else {
            if(!$child->canView(UserUtils::getUser())) {
                return $result;
            }
        }
        $activeChild = "";
        if($activePage == $child->getController()) {
            $activeChild = " active";
            $active = " visualactive";
        }
        $childsOfChild = $child->getChilds();
        if(empty($childsOfChild)) {
            $result = $result.'<a class="item'.$activeChild.'" href="'.Link::get($child->getLink()).'">'.$child->getTitle().'</a>';
        } else {
            if($activeChild == " active") {
                $activeChild = " visualactive";
            }
            $result = $result.'
                <div class="ui dropdown child navigation item'.$activeChild.'">
                    <a class="menuLink" href="'.Link::get($child->getLink()).'">'.$child->getTitle().'</a> <i class="dropdown icon"></i>
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
}