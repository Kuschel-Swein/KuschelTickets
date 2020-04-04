<?php
namespace KuschelTickets\lib\system;

class FAQ {

    public $faqID;

    public function __construct(int $faqID) {
        $this->faqID = $faqID;
    }

    public function getQuestion() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq WHERE faqID = ?");
        $stmt->execute([$this->faqID]);
        $row = $stmt->fetch();
        return $row['question'];
    }

    public function getCategory() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq WHERE faqID = ?");
        $stmt->execute([$this->faqID]);
        $row = $stmt->fetch();
        return $row['category'];
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq WHERE faqID = ?");
        $stmt->execute([$this->faqID]);
        $row = $stmt->fetch();
        return $row !== false;
    }

    public function getCategoryName() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq_categorys WHERE categoryID = ?");
        $stmt->execute([$this->getCategory()]);
        $row = $stmt->fetch();
        return $row['name'];
    }

    public function getAnswer() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq WHERE faqID = ?");
        $stmt->execute([$this->faqID]);
        $row = $stmt->fetch();
        return $row['answer'];
    }

    public static function getCategorys() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq_categorys");
        $stmt->execute();
        $categorys = [];
        while($row = $stmt->fetch()) {
            $statement = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq WHERE category = ?");
            $statement->execute([$row['categoryID']]);
            $faqs = [];
            while($r = $statement->fetch()) {
                array_push($faqs, new FAQ($r['faqID']));
            }
            $data = array(
                "name" => $row['name'],
                "faqs" => $faqs
            );
            array_push($categorys, $data);
        }
        return $categorys;
    }

    public static function getAll() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq");
        $stmt->execute();
        $faqs = [];
        while($row = $stmt->fetch()) {
            array_push($faqs, new FAQ($row['faqID']));
        }
        return $faqs;
    }
}