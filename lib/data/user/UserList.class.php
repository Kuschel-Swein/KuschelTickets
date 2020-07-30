<?php
namespace kt\data\user;

use kt\data\DatabaseObjectList;

class UserList extends DatabaseObjectList {
    public $databaseObject = User::class;
}