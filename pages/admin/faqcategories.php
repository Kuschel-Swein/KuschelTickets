<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\FAQ;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

/**
 * 
 * FAQ Kategorie Admin Page Handler
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";

    $errors = array(
        "text" => false,
        "token" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    $text = strip_tags($parameters['text']);
                    $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_faq_categorys(`name`) VALUES (?)");
                    $stmt->execute([$text]);
                    $success = "Diese Kategorie wurde erfolgreich hinzugefügt.";
                } else {
                    $errors['text'] = "Bitte gib einen Namen an.";
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
        "errors" => $errors
    );

} else if(isset($parameters['edit'])) {
    $subpage = "edit";
    if(empty($parameters['edit'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq_categorys");
    $stmt->execute();
    $text = null;
    $id = null;
    while($row = $stmt->fetch()) {
        if($row['categoryID'] == $parameters['edit']) {
            $text = $row['name'];
            $id = $row['categoryID'];
            break;
        }
    }
    if($text == null) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }


    $errors = array(
        "text" => false,
        "token" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    $text = strip_tags($parameters['text']);
                    $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_faq_categorys SET `name`=? WHERE categoryID = ?");
                    $stmt->execute([$text, $parameters['edit']]);
                    $success = "Diese Kategorie wurde erfolgreich gespeichert.";
                } else {
                    $errors['text'] = "Bitte gib einen Namen an.";
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
        "errors" => $errors,
        "id" => $id,
        "text" => $text
    );
} else {
    $subpage = "index";

    $categories = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_faq_categorys");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        $statement = $config['db']->prepare("SELECT count(*) as total FROM kuscheltickets".KT_N."_faq WHERE category = ?");
        $statement->execute([$row['categoryID']]);
        $r = $statement->fetch();
        $data = array(
            "id" => $row['categoryID'],
            "name" => $row['name'],
            "faqs" => $r['total']
        );
        array_push($categories, $data);
    }

    $site = array(
        "categories" => $categories,
        "site" => $subpage
    );
}




