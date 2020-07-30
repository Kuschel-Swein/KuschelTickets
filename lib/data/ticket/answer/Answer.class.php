<?php
namespace KuschelTickets\lib\data\ticket\answer;

use KuschelTickets\lib\data\DatabaseObject;
use KuschelTickets\lib\data\user\User;

class Answer extends DatabaseObject {
    public $tableName = "ticket_answers";

    public function getCreator() {
        return new User($this->creator);
    }
}
