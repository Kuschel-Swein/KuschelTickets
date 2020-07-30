<?php
namespace kt\data\faq;

use kt\data\DatabaseObject;
use kt\data\faq\category\Category;

class FAQ extends DatabaseObject {
    public $tableName = "faq";

    public function getCategory() {
        return new Category($this->category);
    }
}