<?php
namespace kt\data\ticket\change;

use kt\data\DatabaseObjectList;

class ChangeList extends DatabaseObjectList {
    public $databaseObject = Change::class;
}