<?php
namespace KuschelTickets\lib\data\user;

use KuschelTickets\lib\data\DatabaseObjectList;

class UserList extends DatabaseObjectList {
    public $databaseObject = User::class;
}