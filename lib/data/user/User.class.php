<?php
namespace kt\data\user;

use kt\data\DatabaseObject;
use kt\data\user\group\Group;
use kt\data\user\editortemplate\EditorTemplateList;
use kt\data\user\notification\NotificationList;
use kt\data\ticket\TicketList;
use kt\system\Oauth;
use kt\system\Link;

class User extends DatabaseObject {
    public $tableName = "accounts";

    /**
     * workarround for older mysql versions that ignore default text values
     */
    public function __construct(int $primaryKeyValue) {
        parent::__construct($primaryKeyValue);
        if(empty($this->avatar)) {
            $this->avatar = "default.png";
        }
        if(!is_object($this->twofactor)) {
            $value = array(
                "use" => false,
                "code" => "",
                "backupcodes" => []
            );
            $value = (object) $value;
            $this->twofactor = $value;
        }
    }

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

    public function getAvatar() {
        if($this->banned == 1) {
            return Link::mainurl()."data/avatars/default.png";
        }
        if(!$this->hasPermission("general.account.avatar")) {
            return Link::mainurl()."data/avatars/default.png";
        }
        if(file_exists("./data/avatars/".$this->avatar)) {
            return Link::mainurl()."data/avatars/".$this->avatar;
        }
        return Link::mainurl()."data/avatars/default.png";
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

    public function getOauthProvider() {
        return Oauth::getProvider($this->oauth);
    }

    public function getTickets() {
        return new TicketList(array(
            "creator" => $this->userID
        ));
    }
}