<?php
namespace KuschelTickets\lib\data\user;

use KuschelTickets\lib\data\DatabaseObject;
use KuschelTickets\lib\data\user\group\Group;
use KuschelTickets\lib\data\user\editortemplate\EditorTemplateList;
use KuschelTickets\lib\data\user\notification\NotificationList;
use KuschelTickets\lib\data\ticket\TicketList;

class User extends DatabaseObject {
    public $tableName = "accounts";

    public function getGroup() {
        return new Group($this->userGroup);
    }

    public function hasPermission(String $permission) {
        return $this->getGroup()->hasPermission($permission);
    }

    public function validateHash() {
        if($_SESSION['hash'] !== $this->password) {
            session_destroy();
        }
    }

    public function getEditorTemplates() {
        return new EditorTemplateList(array(
            "userID" => $this->userID
        ));
    }

    public function getNotifications() {
        return new NotificationList(array(
            "userID" => $this->userID
        ), "ORDER BY notificationID DESC");
    }

    public function getNotificationType(String $identifier) {
        if($this->notificationsettings !== "") {
            $settings = array_keys((array) $this->notificationsettings);
            if(!in_array($identifier, $settings)) {
                $result = "normal";
            } else {
                $result = $this->notificationsettings->$identifier;
                if($result == null) {
                    $result = "normal";
                }
            }
        } else {
            $result = "normal";
        }
        return $result;
    }

    public function getTicketCount() {
        return count(new TicketList(array(
            "creator" => $this->userID
        )));
    }
}