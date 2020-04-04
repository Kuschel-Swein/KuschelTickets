<?php
namespace KuschelTickets\lib\system;
use KuschelTickets\lib\system\Ticket;
use KuschelTickets\lib\system\Group;

class User {

    public $userID;

    public function __construct(int $userID) {
        $this->userID = $userID;
    }

    public function getUserName() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row['username'];
    }

    public function getEmail() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row['email'];
    }

    public function getSecurityToken() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row['token'];
    }

    public function getGroup() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return new Group((int) $row['userGroup']);
    }

    public function getHash() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row['password'];
    }

    public function hasPermission(String $permission) {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_group_permissions WHERE groupID = ? AND name = ?");
        $stmt->execute([$this->getGroup()->groupID, $permission]);
        $row = $stmt->fetch();
        return $row['value'] == "1";
    }

    public function isBanned() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row['banned'] == "1";
    }

    public function getBanReason() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row['banreason'];
    }

    public function validateHash() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        if($_SESSION['hash'] !== $hash) {
            session_destroy();
        }
    }

    public function getTickets() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE creator = ?");
        $stmt->execute([$this->userID]);
        $tickets = [];
        while($row = $stmt->fetch()) {
            $ticket = new Ticket($row['ticketID']);
            array_push($tickets, $ticket);
        }
        return $tickets;
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row !== false;
    }
}