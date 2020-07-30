<?php
namespace KuschelTickets\lib\data\menu;

use KuschelTickets\lib\data\DatabaseObjectList;

class MenuEntryList extends DatabaseObjectList {
    public $databaseObject = MenuEntry::class;
}