<?php
use kt\system\Utils;
use kt\system\CRSF;
use kt\data\menu\MenuEntry;
use kt\data\menu\MenuEntryList;
use kt\data\page\PageList;
use kt\system\exception\PageNotFoundException;
use kt\system\KuschelTickets;

/**
 * 
 * Menu Admin Page Handler
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";

    $errors = array(
        "text" => false,
        "controller" => false,
        "parent" => false,
        "token" => false
    );

    $navigation = new MenuEntryList([], "ORDER BY sorting ASC");
    $controllers = DATA['topnavigation'];
    foreach(new PageList() as $page) {
        array_push($controllers, array(
            "identifier" => "page/".$page->identifier,
            "text" => $page->title
        ));
    }

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    if(isset($parameters['controller']) && !empty($parameters['controller'])) {
                        $controllerExists = false;
                        foreach($controllers as $controller) {
                            if($controller['identifier'] == $parameters['controller']) {
                                $controllerExists = true;
                                break;
                            }
                        }
                        if($controllerExists || filter_var($parameters['controller'], FILTER_VALIDATE_URL)) {
                            $validParent = true;
                            $parent = null;
                            if(isset($parameters['parent']) && !empty($parameters['parent'])) {
                                $validParent = false;
                                foreach($navigation as $entry) {
                                    if($entry->menuID == (int) $parameters['parent']) {
                                        $validParent = true;
                                        $parent = $parameters['parent'];
                                        break;
                                    }
                                }
                            }
                            if($validParent) {
                                $text = strip_tags($parameters['text']);
                                $entry = MenuEntry::create(array(
                                    "title" => $text,
                                    "controller" => $parameters['controller'],
                                    "parent" => $parent
                                ));
                                $entry->update(array(
                                    "sorting" => $entry->menuID
                                ));
                                $success = "Dieser Menüeintrag wurde erfolgreich hinzugefügt.";
                            }
                        } else {
                            $errors['controller'] = "Bitte wähle eine valide Seite für diesen Menüeintrag.";
                        }
                    } else {
                        $errors['controller'] = "Bitte wähle eine Seite für diesen Menüeintrag.";
                    }
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
    $navigation = new MenuEntryList([], "ORDER BY sorting ASC");
    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "navigation" => $navigation,
        "controllers" => $controllers
    );

} else if(isset($parameters['edit'])) {
    $subpage = "edit";
    if(empty($parameters['edit'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }
    $entry = new MenuEntry($parameters['edit']);
    if(!$entry->menuID) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $navigation = new MenuEntryList([], "ORDER BY sorting ASC");
    $childs = $entry->getChilds();
    $childIDs = [];
    if(!empty($childs)) {
        foreach($childs as $child) {
            array_push($childIDs, $child->menuID);
        }
    }

    $controllers = DATA['topnavigation'];
    foreach(new PageList() as $page) {
        array_push($controllers, array(
            "identifier" => "page/".$page->identifier,
            "text" => $page->title
        ));
    }

    if(filter_var($entry->getLink(), FILTER_VALIDATE_URL)) {
        array_push($controllers, array(
            "identifier" => $entry->getLink(),
            "text" => $entry->getLink()
        ));
    }

    $errors = array(
        "text" => false,
        "controller" => false,
        "parent" => false,
        "token" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    if(isset($parameters['controller']) && !empty($parameters['controller'])) {
                        $controllerExists = false;
                        foreach($controllers as $controller) {
                            if($controller['identifier'] == $parameters['controller']) {
                                $controllerExists = true;
                                break;
                            }
                        }
                        if($controllerExists || filter_var($parameters['controller'], FILTER_VALIDATE_URL)) {
                            $validParent = true;
                            $parent = null;
                            if(isset($parameters['parent']) && !empty($parameters['parent'])) {
                                $validParent = false;
                                foreach($navigation as $entry) {
                                    if($entry->menuID == (int) $parameters['parent']) {
                                        $validParent = true;
                                        $parent = $parameters['parent'];
                                        break;
                                    }
                                }
                            }
                            if($validParent) {
                                $text = strip_tags($parameters['text']);
                                $entry->update(array(
                                    "title" => $text,
                                    "controller" => $parameters['controller'],
                                    "parent" => $parent
                                ));
                                $success = "Dieser Menüeintrag wurde erfolgreich bearbeitet.";
                            }
                        } else {
                            $errors['controller'] = "Bitte wähle eine valide Seite für diesen Menüeintrag.";
                        }
                    } else {
                        $errors['controller'] = "Bitte wähle eine Seite für diesen Menüeintrag.";
                    }
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

    $navigation = new MenuEntryList([], "ORDER BY sorting ASC");
    $childs = $entry->getChilds();
    $childIDs = [];
    if(!empty($childs)) {
        foreach($childs as $child) {
            array_push($childIDs, $child->menuID);
        }
    }

    $controllers = DATA['topnavigation'];
    foreach(new PageList() as $page) {
        array_push($controllers, array(
            "identifier" => "page/".$page->identifier,
            "text" => $page->title
        ));
    }

    if(filter_var($entry->getLink(), FILTER_VALIDATE_URL)) {
        array_push($controllers, array(
            "identifier" => $entry->getLink(),
            "text" => $entry->getLink()
        ));
    }


    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "entry" => $entry,
        "controllers" => $controllers,
        "navigation" => $navigation
    );
} else {
    $subpage = "index";

    $site = array(
        "navigation" => new MenuEntryList([], "ORDER BY sorting ASC"),
        "site" => $subpage
    );
}




