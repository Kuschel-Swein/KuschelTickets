<?php
namespace KuschelTickets\lib\data\faq;

use KuschelTickets\lib\data\DatabaseObject;
use KuschelTickets\lib\data\faq\category\Category;

class FAQ extends DatabaseObject {
    public $tableName = "faq";

    public function getCategory() {
        return new Category($this->category);
    }
}