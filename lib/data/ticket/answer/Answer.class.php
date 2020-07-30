<?php
namespace kt\data\ticket\answer;

use kt\data\DatabaseObject;
use kt\data\user\User;

class Answer extends DatabaseObject {
    public $tableName = "ticket_answers";

    public function getCreator() {
        return new User($this->creator);
    }
}
