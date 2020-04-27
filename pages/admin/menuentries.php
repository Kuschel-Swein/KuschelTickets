<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\system\MenuEntry;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

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

    $navigation = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($navigation, new MenuEntry($row['menuID']));
    }

    $controllers = DATA['topnavigation'];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($controllers, array(
            "identifier" => "page/".$row['identifier'],
            "text" => $row['title']
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
                                $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_menu(`title`, `controller`, `parent`) VALUES (?, ?, ?)");
                                $stmt->execute([$text, $parameters['controller'], $parent]);
                                $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu WHERE title=? ORDER BY menuID DESC LIMIT 1");
                                $stmt->execute([$text]);
                                $row = $stmt->fetch();
                                $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_menu SET `sorting`=? WHERE menuID = ?");
                                $stmt->execute([$row['menuID'], $row['menuID']]);
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
    $entry = new MenuEntry((int) $parameters['edit']);
    if(!$entry->exists()) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $navigation = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu");
    $stmt->execute();
    $childs = $entry->getChilds();
    $childIDs = [];
    if(!empty($childs)) {
        foreach($childs as $child) {
            array_push($childIDs, $child->menuID);
        }
    }

    while($row = $stmt->fetch()) {
        if($row['menuID'] !== $parameters['edit'] && !in_array($row['menuID'], $childIDs)) {
            array_push($navigation, new MenuEntry((int) $row['menuID']));
        }
    }

    $controllers = DATA['topnavigation'];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($controllers, array(
            "identifier" => "page/".$row['identifier'],
            "text" => $row['title']
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
                                $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_menu SET `title`=?, `controller`=?, `parent`=? WHERE menuID = ?");
                                $stmt->execute([$text, $parameters['controller'], $parent, $parameters['edit']]);
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

    $entry = new MenuEntry((int) $parameters['edit']);

    $navigation = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu");
    $stmt->execute();
    $childs = $entry->getChilds();
    $childIDs = [];
    if(!empty($childs)) {
        foreach($childs as $child) {
            array_push($childIDs, $child->menuID);
        }
    }
    while($row = $stmt->fetch()) {
        if($row['menuID'] !== $parameters['edit'] && !in_array($row['menuID'], $childIDs)) {
            array_push($navigation, new MenuEntry((int) $row['menuID']));
        }
    }

    $controllers = DATA['topnavigation'];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($controllers, array(
            "identifier" => "page/".$row['identifier'],
            "text" => $row['title']
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

    $navigation = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_menu ORDER BY sorting ASC");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($navigation, new MenuEntry($row['menuID']));
    }

    $site = array(
        "navigation" => $navigation,
        "site" => $subpage
    );
}




