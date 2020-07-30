<?php
namespace KuschelTickets\lib\data\ticket\category;

use KuschelTickets\lib\data\DatabaseObjectList;

class CategoryList extends DatabaseObjectList {
    public $databaseObject = Category::class;
}