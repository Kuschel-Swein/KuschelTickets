<?php
namespace KuschelTickets\lib\system;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\Mailer;

class Notification {

    public $notificationID;
    public $time;
    public $link;
    public $message;

    public function __construct(int $notificationID) {
        $this->notificationID = $notificationID;
        $this->time = date("d.m.Y", $this->getTime()).", ".date("H:i", $this->getTime())." Uhr";
        $this->link = $this->getLink();
        $this->message = $this->getMessage();
    }

    public function getLink() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
        $row = $stmt->fetch();
        return Link::get($row['linkIdentifier']);
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
        $row = $stmt->fetch();
        return $row['linkIdentifier'] !== false;
    }

    public function getMessage() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
        $row = $stmt->fetch();
        return $row['content'];
    }

    public function getTime() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
        $row = $stmt->fetch();
        return (int) $row['time'];
    }

    public function isDone() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
        $row = $stmt->fetch();
        return $row['done'] == "1";
    }

    public function markAsDone() {
        global $config;

        $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_notifications SET `done`=1 WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
    }

    public function isSent() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
        $row = $stmt->fetch();
        return $row['sent'] == "1";
    }

    public function markAsSent() {
        global $config;

        $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_notifications SET `sent`=1 WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
    }

    public function getUser() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_notifications WHERE notificationID = ?");
        $stmt->execute([$this->notificationID]);
        $row = $stmt->fetch();
        return new User((int) $row['userID']);
    }

    public static function create(String $identifier, String $content, String $link, User $user) {
        global $config;

        if($user->getNotificationType($identifier) !== "none") {  
            $content = strip_tags($content);
            $time = time(); 
            if($user->getNotificationType($identifier) == "email" && $config['emailnotifications']) {
                $mail = new Mailer($user->getEmail(), $config['pagetitle']." - Benachrichtigung", $user->getUserName());
                $message = "<p>Hey ".$user->getUserName().",</p>
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
            $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_notifications(`linkIdentifier`, `content`, `userID`, `time`, `done`, `sent`) VALUES (?, ?, ?, ?, 0, ?)");
            $stmt->execute([$link, $content, $user->userID, $time, $sent]);
        }
    }
}