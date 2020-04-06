<?php
namespace KuschelTickets\lib\system;

class Group {

    public $groupID;

    public function __construct(int $groupID) {
        $this->groupID = $groupID;
    }

    public function getGroupName() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE groupID = ?");
        $stmt->execute([$this->groupID]);
        $row = $stmt->fetch();
        return $row['name'];
    }

    public function isSystem() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE groupID = ?");
        $stmt->execute([$this->groupID]);
        $row = $stmt->fetch();
        return $row['system'] == "1";
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE groupID = ?");
        $stmt->execute([$this->groupID]);
        $row = $stmt->fetch();
        return isset($row);
    }

    public function getGroupBadge() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE groupID = ?");
        $stmt->execute([$this->groupID]);
        $row = $stmt->fetch();
        return str_replace("%NAME%", $this->getGroupName(), $row['badge']);
    }

    public function getUnformattedBadge() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE groupID = ?");
        $stmt->execute([$this->groupID]);
        $row = $stmt->fetch();
        return $row['badge'];
    }

    public function getPermissions() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_group_permissions WHERE groupID = ?");
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

    public static function getAllGroups() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups");
        $stmt->execute();
        $groups = [];
        while($row = $stmt->fetch()) {
            array_push($groups, new Group($row['groupID']));
        }
        return $groups;
    }
}