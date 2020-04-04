<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\FAQ;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

/**
 * 
 * FAQ Admin Page Hander
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";

    $categorys = [];
    $categorysID = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq_categorys");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        $data = array(
            "id" => $row['categoryID'],
            "name" => $row['name']
        );
        array_push($categorys, $data);
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
                                $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_faq(`question`, `answer`, `category`) VALUES (?, ?, ?)");
                                $stmt->execute([$question, $text, $category]);
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
    if(!$faq->exists()) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $categorys = [];
    $categorysID = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq_categorys");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        $data = array(
            "id" => $row['categoryID'],
            "name" => $row['name']
        );
        array_push($categorys, $data);
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
                                $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_faq SET `question`=?,`answer`=?,`category`=? WHERE faqID = ?");
                                $stmt->execute([$question, $text, $category, $faq->faqID]);
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
        "faqs" => FAQ::getAll(),
        "site" => $subpage
    );
}




