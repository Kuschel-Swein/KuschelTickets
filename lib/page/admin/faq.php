<?php
use kt\system\Utils;
use kt\data\faq\FAQ;
use kt\system\CRSF;
use kt\system\exception\PageNotFoundException;
use kt\system\KuschelTickets;
use kt\data\faq\category\CategoryList;
use kt\data\faq\FAQList;

/**
 * 
 * FAQ Admin Page Hander
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";

    $categorys = new CategoryList();
    $categorysID = [];
    foreach($categorys as $category) {
        array_push($categorysID, $row['categoryID']);
    }

    $errors = array(
        "category" => false,
        "question" => false,
        "text" => false,
        "token" => false
    );
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['category']) && !empty($parameters['category'])) {
                    if(in_array($parameters['category'], $categorysID)) {
                        if(isset($parameters['question']) && !empty($parameters['question'])) {
                            if(isset($parameters['text']) && !empty($parameters['text'])) {
                                $text = Utils::purify($parameters['text']);
                                $question = strip_tags($parameters['question']);
                                $category = strip_tags($parameters['category']);
                                FAQ::create(array(
                                    "question" => $question,
                                    "answer" => $text,
                                    "category" => $category
                                ));
                                $success = "Dieser FAQ Eintrag wurde erfolgreich hinzugefügt.";
                            } else {
                                $errors['text'] = "Bitte gib eine Antwort an.";
                            }
                        } else {
                            $errors['question'] = "Bitte gib eine Frage an.";
                        }
                    } else {
                        $errors['category'] = "Bitte wähle eine valide Kategorie.";
                    }
                } else {
                    $errors['category'] = "Bitte wähle eine Kategorie.";
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "categorys" => $categorys,
        "errors" => $errors
    );

} else if(isset($parameters['edit'])) {
    $subpage = "edit";
    if(empty($parameters['edit'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }
    $faq = new FAQ($parameters['edit']);
    if(!$faq->faqID) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $categorys = new CategoryList();
    $categorysID = [];
    foreach($categorys as $category) {
        array_push($categorysID, $category->categoryID);
    }

    $errors = array(
        "category" => false,
        "question" => false,
        "text" => false,
        "token" => false
    );
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['category']) && !empty($parameters['category'])) {
                    if(in_array($parameters['category'], $categorysID)) {
                        if(isset($parameters['question']) && !empty($parameters['question'])) {
                            if(isset($parameters['text']) && !empty($parameters['text'])) {
                                $text = Utils::purify($parameters['text']);
                                $question = strip_tags($parameters['question']);
                                $category = strip_tags($parameters['category']);
                                $faq->update(array(
                                    "question" => $question,
                                    "answer" => $text,
                                    "category" => $category
                                ));
                                $success = "Dieser FAQ Eintrag wurde erfolgreich gespeichert.";
                            } else {
                                $errors['text'] = "Bitte gib eine Antwort an.";
                            }
                        } else {
                            $errors['question'] = "Bitte gib eine Frage an.";
                        }
                    } else {
                        $errors['category'] = "Bitte wähle eine valide Kategorie.";
                    }
                } else {
                    $errors['category'] = "Bitte wähle eine Kategorie.";
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }
    $site = array(
        "success" => $success,
        "site" => $subpage,
        "categorys" => $categorys,
        "errors" => $errors,
        "faq" => $faq
    );
} else {
    $subpage = "index";

    $site = array(
        "faqs" => new FAQList(),
        "site" => $subpage
    );
}




