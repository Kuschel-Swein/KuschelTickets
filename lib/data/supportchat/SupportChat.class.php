<?php
namespace KuschelTickets\lib\data\supportchat;

use KuschelTickets\lib\data\DatabaseObject;
use KuschelTickets\lib\data\supportchat\message\MessageList;
use KuschelTickets\lib\KuschelTickets;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\Utils;

class SupportChat extends DatabaseObject {
    public $tableName = "supportchat";
    public $tablePrimaryKey = "chatID";

    public function getMessages() {
        return new MessageList(array(
            "chatID" => $this->chatID
        ));
    }

    public function getUser() {
        if($this->user == null) {
            return null;
        }
        return new User($this->user);
    }

    public function getCreator() {
        return new User($this->creator);
    }

    public function isJoinable() {
        return $this->getState() == 0;
    }

    public function join(User $user) {
        $stmt = KuschelTickets::getDB()->prepare("UPDATE kuscheltickets".KT_N."_supportchat SET `user`=?,`state`=1 WHERE chatID = ?");
        $stmt->execute([$user->userID, $this->chatID]);
    }

    public function createMessage(User $user, String $message) {
        $message = strip_tags($message);
        $message = Utils::makeClickableLinks($message);
        $time = time();
        $stmt = KuschelTickets::getDB()->prepare("INSERT INTO kuscheltickets".KT_N."_supportchat_messages(`chatID`, `poster`, `content`, `time`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->chatID, $user->userID, $message, $time]);
    }

    public function createSystemMessage(String $message) {
        $time = time();
        $stmt = KuschelTickets::getDB()->prepare("INSERT INTO kuscheltickets".KT_N."_supportchat_messages(`chatID`, `poster`, `content`, `time`) VALUES (?, 0, ?, ?)");
        $stmt->execute([$this->chatID, $message, $time]);
    }

    public static function openChat(User $user) {
        $stmt = KuschelTickets::getDB()->prepare("INSERT INTO kuscheltickets".KT_N."_supportchat(`creator`, `time`, `state`) VALUES (?, ?, 0)");
        $time = time();
        $stmt->execute([$user->userID, $time]);
        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE time = ? AND creator = ? LIMIT 1");
        $stmt->execute([$time, $user->userID]);
        $row = $stmt->fetch();
        return new SupportChat((int) $row['chatID']);
    }
}