<?php
namespace KuschelTickets\lib\system;

class MenuEntry {

    public $menuID;

    public function __construct(int $menuID) {
        $this->menuID = $menuID;
    }

    public function getTitle() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu WHERE menuID = ?");
        $stmt->execute([$this->menuID]);
        $row = $stmt->fetch();
        return $row['title'];
    }

    public function getController() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu WHERE menuID = ?");
        $stmt->execute([$this->menuID]);
        $row = $stmt->fetch();
        return $row['controller'];
    }

    public function isSystemPage() {
        global $config;

        $controller = $this->getController();
        $data = DATA;
        foreach($data['topnavigation'] as $entry) {
            if($entry['identifier'] == $controller) {
                return true;
            }
        }
        return false;
    }

    public function canView($user) {
        global $config;

        if(!$this->isSystemPage()) {
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE identifier = ?");
            $stmt->execute([$this->getController()]);
            $row = $stmt->fetch();
            $data = [];
            if($row !== false) {
                $data = json_decode($row['groups']);
            }
            if($user == null) {
                if(empty($data)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if(empty($data)) {
                    return true;
                } else if(in_array($user->getGroup()->groupID, $data)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function getLink() {
        $link = $this->getController();
        if($link == "Index") {
            return "";
        }
        return $link;
    }

    public function getPermission() {
        if($this->isSystemPage()) {
            $data = DATA;
            foreach($data['topnavigation'] as $entry) {
                if($entry['identifier'] == $this->getController()) {
                    return $entry['permission'];
                }
            }
            return "";
        } else {
            return "";
        }
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu WHERE menuID = ?");
        $stmt->execute([$this->menuID]);
        $row = $stmt->fetch();
        return $row !== false;
    }

    public function isSystem() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu WHERE menuID = ?");
        $stmt->execute([$this->menuID]);
        $row = $stmt->fetch();
        return $row['system'] == "1";
    }

    public function getParent() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu WHERE menuID = ?");
        $stmt->execute([$this->menuID]);
        $row = $stmt->fetch();
        $parent = $row['parent'];
        if($parent == null) {
            return null;
        } else {
            return new MenuEntry($row['parent']);
        }
    }

    public function getChilds() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu WHERE parent = ?");
        $stmt->execute([$this->menuID]);
        $result = [];
        while($row = $stmt->fetch()) {
            array_push($result, new MenuEntry($row['menuID']));
        }
        return $result;
    }

    public function hasParent() {
        return $this->getParent() !== null;
    }
}