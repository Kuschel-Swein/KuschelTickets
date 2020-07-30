<?php
namespace KuschelTickets\lib\data\user\notification;

use KuschelTickets\lib\data\DatabaseObject;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\Mailer;
use KuschelTickets\lib\KuschelTickets;

class Notification extends DatabaseObject {
    public $tableName = "notifications";

    public function getUser() {
        return new User($this->userID);
    }

    public function getLink() {
        return Link::get($this->linkIdentifier);
    }

    public static function add(String $identifier, String $content, String $link, User $user) {
        global $config;
        
        if($user->getNotificationType($identifier) !== "none") {  
            $content = strip_tags($content);
            $time = time(); 
            if($user->getNotificationType($identifier) == "email" && $config['emailnotifications']) {
                $mail = new Mailer($user->email, $config['pagetitle']." - Benachrichtigung", $user->username);
                $message = "<p>Hey ".$user->username.",</p>
                <p>Diese E-Mail ist eine Benachrichtigung, da du E-Mail Benachrichtigungen für bestimmte Typen aktiviert hast.</p>
                <p></p>
                <p><i>".$content."</i></p>
                <p></p>
                <p><b>Link:</b> <a href='".Link::get($link)."'>".Link::get($link)."</a></p>
                <p></p>
                <p><hr></p>
                <p>Mit freundlichen Grüßen,</p>
                <p>dein ".$config['pagetitle']." Team</p>";
                $mail->setMessage($message);
                $mail->send();
            }
            if($config['useDesktopNotification']) {
                $sent = 0;
            } else {
                $sent = 1;
            }
            $stmt = KuschelTickets::getDB()->prepare("INSERT INTO kuscheltickets".KT_N."_notifications(`linkIdentifier`, `content`, `userID`, `time`, `done`, `sent`) VALUES (?, ?, ?, ?, 0, ?)");
            $stmt->execute([$link, $content, $user->userID, $time, $sent]);
        }
    }
}