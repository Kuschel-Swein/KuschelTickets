<?php
namespace kt\data\ticket\answer;

use kt\data\DatabaseObject;
use kt\data\user\User;
use kt\data\ticket\change\ChangeList;

class Answer extends DatabaseObject {
    public $tableName = "ticket_answers";

    public function getCreator() {
        return new User($this->creator);
    }

    public function getLatestChange() {
        $changesList = new ChangeList(array(
            "ticketID" => $this->ticketID,
            "answerID" => $this->answerID
        ), "ORDER BY changeID DESC", 1);
        if(count($changesList) == 0) {
            return null;
        } else {
            return $changesList->getObjects()[0];
        }
    }

    public function getChanges() {
        return new ChangeList(array(
            "ticketID" => $this->ticketID,
            "answerID" => $this->answerID
        ), "ORDER BY changeID DESC");
    }
}
