<?php
namespace kt\data\menu;

use kt\data\DatabaseObjectList;

class MenuEntryList extends DatabaseObjectList {
    public $databaseObject = MenuEntry::class;
}