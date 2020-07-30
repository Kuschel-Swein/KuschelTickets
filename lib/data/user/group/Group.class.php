<?php
namespace kt\data\user\group;

use kt\data\DatabaseObject;
use kt\system\KuschelTickets;

class Group extends DatabaseObject {
    public $tableName = "groups";

    public function hasPermission(String $permission) {
        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_group_permissions WHERE groupID = ? AND name = ?");
        $stmt->execute([$this->groupID, $permission]);
        $row = $stmt->fetch();
        if($row == false) {
            if($this->groupID == 1) {
                return true;
            } else {
                return false;
            }
        }
        return $row['value'] == "1";
    }

    public function getGroupBadge() {
        return str_replace("%NAME%", $this->name, $this->badge);
    }

    public function getPermissions() {
        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_group_permissions WHERE groupID = ?");
        $stmt->execute([$this->groupID]);
        $permissions = [];
        while($row = $stmt->fetch()) {
            $data = array(
                "name" => $row['name'],
                "value" => ($row['value'] == "1")
            );
            array_push($permissions, $data);
        }
        return $permissions;
    }
}