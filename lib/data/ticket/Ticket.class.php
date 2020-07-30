<?php
namespace kt\data\ticket;

use kt\data\DatabaseObject;
use kt\data\ticket\answer\AnswerList;
use kt\data\user\User;
use kt\data\ticket\category\Category;
use kt\system\KuschelTickets;

class Ticket extends DatabaseObject {
    public $tableName = "tickets";

    public function getCreator() {
        return new User($this->creator);
    }

    public function getCategory() {
        return new Category($this->creator);
    }

    public function getFormattedState(String $type) {
        global $config;
        $ids = ["closed", "open", "done"];
        $names = ["Geschlossen", "Offen", "Erledigt"];
        $data = array(
            "color" => $config['state_colors'][$ids[$this->state]],
            "name" => $names[$this->state]
        );
        return $data[$type];
    }

    public function getAnswers() {
        return new AnswerList(array(
            "ticketID" => $this->ticketID
        ));
    }

    public function hasRating() {
        return $this->rating !== null;
    }

    public function addLog(String $text) {
        $stmt = KuschelTickets::getDB()->prepare("INSERT INTO kuscheltickets".KT_N."_ticket_answers(`ticketID`, `creator`, `content`, `time`) VALUES (?, 0, ?, ?)");
        $time = time();
        $stmt->execute([$this->ticketID, $text, $time]);
    }
}