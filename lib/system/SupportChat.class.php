<?php
namespace KuschelTickets\lib\system;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\Utils;

class SupportChat {

    public $chatID;

    public function __construct(int $chatID) {
        $this->chatID = $chatID;
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE chatID = ?");
        $stmt->execute([$this->chatID]);
        $row = $stmt->fetch();
        return $row !== false;
    }

    public function getState() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE chatID = ?");
        $stmt->execute([$this->chatID]);
        $row = $stmt->fetch();
        return (int) $row['state'];
    }

    public function setState(int $state) {
        global $config;

        $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_supportchat SET state = ? WHERE chatID = ?");
        $stmt->execute([$state, $this->chatID]);
        $row = $stmt->fetch();
    }

    public function close() {
        $this->setState(2);
    }

    public function getCreator() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE chatID = ?");
        $stmt->execute([$this->chatID]);
        $row = $stmt->fetch();
        return new User((int) $row['creator']);;
    }

    public function getUser() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE chatID = ?");
        $stmt->execute([$this->chatID]);
        $row = $stmt->fetch();
        if($row['user'] == null) {
            return null;
        }
        return new User((int) $row['user']);
    }

    public function getMessages() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat_messages WHERE chatID = ?");
        $stmt->execute([$this->chatID]);
        $result = [];
        $state = $this->getState();
        while($row = $stmt->fetch()) {
            $poster = "System";
            $badge = "";
            if($row['poster'] !== "0") {
                $poster = new User((int) $row['poster']);
                $badge = $poster->getGroup()->getGroupBadge();
                $poster = $poster->getUserName();
            }
            $data = array(
                "poster" => $poster,
                "state" => $state,
                "badge" => $badge,
                "content" => $row['content'],
                "time" => date("d.m.Y", $row['time']).", ".date("H:i", $row['time'])." Uhr"
            );
            array_push($result, $data);
        }
        return $result;
    }

    public function isJoinable() {
        return $this->getState() == 0;
    }

    public function createMessage(User $user, String $message) {
        global $config;

        $message = strip_tags($message);
        $message = Utils::makeClickableLinks($message);
        $time = time();
        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_supportchat_messages(`chatID`, `poster`, `content`, `time`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->chatID, $user->userID, $message, $time]);
    }

    public function createSystemMessage(String $message) {
        global $config;

        $time = time();
        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_supportchat_messages(`chatID`, `poster`, `content`, `time`) VALUES (?, 0, ?, ?)");
        $stmt->execute([$this->chatID, $message, $time]);
    }

    public function join(User $user) {
        global $config;

        $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_supportchat SET `user`=?,`state`=1 WHERE chatID = ?");
        $stmt->execute([$user->userID, $this->chatID]);
    }

    public static function create(User $user) {
        global $config;

        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_supportchat(`creator`, `time`, `state`) VALUES (?, ?, 0)");
        $time = time();
        $stmt->execute([$user->userID, $time]);
        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat WHERE time = ? AND creator = ? LIMIT 1");
        $stmt->execute([$time, $user->userID]);
        $row = $stmt->fetch();
        return new SupportChat((int) $row['chatID']);
    }
}