<?php
namespace KuschelTickets\lib\system;
use KuschelTickets\lib\system\Ticket;
use KuschelTickets\lib\system\Group;
use KuschelTickets\lib\system\Notification;
use KuschelTickets\lib\system\Oauth;

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
        if($row == false) {
            if($this->getGroup()->groupID == 1) {
                return true;
            } else {
                return false;
            }
        }
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

    public function getAuthProvider() {
        global $config;
        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return Oauth::getProvider($row['oauth']);
    }

    public function validateHash() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        if($_SESSION['hash'] !== $row['password']) {
            session_destroy();
        }
    }

    public function getTickets() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE creator = ? ORDER BY ticketID DESC");
        $stmt->execute([$this->userID]);
        $tickets = [];
        while($row = $stmt->fetch()) {
            $ticket = new Ticket($row['ticketID']);
            array_push($tickets, $ticket);
        }
        return $tickets;
    }

    public function getEditorTemplates() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $templates = [];
        while($row = $stmt->fetch()) {
            array_push($templates, $row);
        }
        return $templates;
    }

    public function getNotifications(bool $onlyNotDone = false) {
        global $config;

        if(!$onlyNotDone) {
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE userID = ?");
        } else {
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE userID = ? AND done = 0");
        }
        $stmt->execute([$this->userID]);
        $notifications = [];
        while($row = $stmt->fetch()) {
            array_push($notifications, new Notification((int) $row['notificationID']));
        }
        return $notifications;
    }

    public function getNotificationType(String $identifier) {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        if($row['notificationsettings'] !== "") {
            $data = json_decode($row['notificationsettings'], true);
            $result = $data[$identifier];
            if($result == null) {
                $result = "normal";
            }
        } else {
            $result = "normal";
        }
        return $result;
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE userID = ?");
        $stmt->execute([$this->userID]);
        $row = $stmt->fetch();
        return $row !== false;
    }

    public function getTicketCount() {
        return count($this->getTickets());
    }
}