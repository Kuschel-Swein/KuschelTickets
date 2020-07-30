<?php
use kt\system\Utils;
use kt\data\faq\category\Category;
use kt\data\faq\category\CategoryList;
use kt\system\CRSF;
use kt\system\exception\PageNotFoundException;
use kt\system\KuschelTickets;

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
                    Category::create(array(
                        "name" => $text
                    ));
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
    $category = new Category($parameters['edit']);
    if(!$category->categoryID) {
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
                    $category->update(array(
                        "name" => $text
                    ));
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
        "category" => $category
    );
} else {
    $subpage = "index";
    
    $site = array(
        "categories" => new CategoryList(),
        "site" => $subpage
    );
}




