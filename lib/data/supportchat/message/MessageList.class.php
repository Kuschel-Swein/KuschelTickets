<?php
namespace kt\data\supportchat\message;

use kt\data\DatabaseObjectList;

class MessageList extends DatabaseObjectList {
    public $databaseObject = Message::class;
}