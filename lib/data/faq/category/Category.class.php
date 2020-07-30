<?php
namespace KuschelTickets\lib\data\faq\category;

use KuschelTickets\lib\data\DatabaseObject;
use KuschelTickets\lib\data\faq\FAQList;

class Category extends DatabaseObject {
    public $tableName = "faq_categories";

    public function getFAQs() {
        return new FAQList(array(
            "category" => $this->categoryID
        ));
    }
}