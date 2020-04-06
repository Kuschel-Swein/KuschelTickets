<?php
namespace KuschelTickets\lib\system;
use KuschelTickets\lib\system\TicketCategory;

class Ticket {

    public $ticketID;

    public function __construct(int $ticketID) {
        $this->ticketID = $ticketID;     
    }

    public function getTitle() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return $row['title'];
    }

    public function getColor() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return $row['color'];
    }

    public function getCreator() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return new User($row['creator']);
    }

    public function getContent() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return $row['content'];
    }

    public function getState() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return $row['state'];
    }

    public function getFormattedState(String $type) {
        global $config;
        $ids = ["closed", "open", "done"];
        $names = ["Geschlossen", "Offen", "Erledigt"];
        $data = array(
            "color" => $config['state_colors'][$ids[$this->getState()]],
            "name" => $names[$this->getState()]
        );
        return $data[$type];
    }

    public function getTime() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return $row['time'];
    }

    public function setState(int $state) {
        global $config;

        $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_tickets SET `state`= ? WHERE ticketID = ?");
        $stmt->execute([$state, $this->ticketID]);
    }

    public function addLog(String $text) {
        global $config;

        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_ticket_answers(`ticketID`, `creator`, `content`, `time`) VALUES (?, 0, ?, ?)");
        $time = time();
        $stmt->execute([$this->ticketID, $text, $time]);
    }
    
    public function getCategory() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return $row['category'];
    }

    public function getCategoryObject() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryName = ?");
        $stmt->execute([$this->getCategory()]);
        $row = $stmt->fetch();
        return new TicketCategory((int) $row['categoryID']);
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $row = $stmt->fetch();
        return $row !== false;
    }

    public function getAnswers() {
        global $config;
        
        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
        $stmt->execute([$this->ticketID]);
        $answers = [];
        while($row = $stmt->fetch()) {
            $account = new User($row['creator']);
            if(!$account->exists()) {
                $account = "system";
            }
            $data = array(
                "id" => $row['answerID'],
                "creator" => $account,
                "content" => $row['content'],
                "time" => $row['time']
            );
            array_push($answers, $data);
        }
        return $answers;
    }
}