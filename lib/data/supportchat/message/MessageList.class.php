<?php
namespace KuschelTickets\lib\data\supportchat\message;

use KuschelTickets\lib\data\DatabaseObjectList;

class MessageList extends DatabaseObjectList {
    public $databaseObject = Message::class;
}