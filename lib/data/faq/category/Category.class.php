<?php
namespace kt\data\faq\category;

use kt\data\DatabaseObject;
use kt\data\faq\FAQList;

class Category extends DatabaseObject {
    public $tableName = "faq_categories";

    public function getFAQs() {
        return new FAQList(array(
            "category" => $this->categoryID
        ));
    }
}