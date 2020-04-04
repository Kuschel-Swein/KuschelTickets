<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

/**
 * 
 * Pages Admin Page Handler
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";

    $errors = array(
        "loginneed" => false,
        "title" => false,
        "url" => false,
        "text" => false,
        "token" => false
    );
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['loginneed'])) {
                    if(in_array($parameters['loginneed'], ["0", "1"])) {
                        if(isset($parameters['title']) && !empty($parameters['title'])) {
                            if(isset($parameters['url']) && !empty($parameters['url'])) {
                                if(isset($parameters['text']) && !empty($parameters['text'])) {
                                    $url = strip_tags($parameters['url']);
                                    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE url = ?");
                                    $stmt->execute([$url]);
                                    $row = $stmt->fetch();
                                    if($row == false) {
                                        $text = Utils::purify($parameters['text']);
                                        $title = strip_tags($parameters['title']);
                                        $login = strip_tags($parameters['loginneed']);
                                        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_pages(`identifier`, `url`, `title`, `content`, `login`, `system`) VALUES (?, ?, ?, ?, ?, 0)");
                                        $stmt->execute([$url, $url, $title, $text, $login]);
                                        $success = "Diese Seite wurde erfolgreich gespeichert.";
                                    } else {
                                        $errors['url'] = "Diese URL ist bereits vergeben.";
                                    }
                                } else {
                                    $errors['text'] = "Bitte gib einen Inhalt an.";
                                }
                            } else {
                                $errors['url'] = "Bitte gib eine URL an.";
                            }
                        } else {
                            $errors['title'] = "Bitte gib einen Title an.";
                        }
                    } else {
                        $errors['loginneed'] = "Bitte wähle eine valide Auswahl.";
                    }
                } else {
                    $errors['loginneed'] = "Bitte wähle ob die Seite nur mit Login sichtbar sein soll..";
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

    $page = null;
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE pageID = ?");
    $stmt->execute([$parameters['edit']]);
    $row = $stmt->fetch();
    if($row) {
        $page = $row;
    }
    if($page == null) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $errors = array(
        "loginneed" => false,
        "title" => false,
        "url" => false,
        "text" => false,
        "token" => false
    );
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['loginneed'])) {
                    if(in_array($parameters['loginneed'], ["0", "1"])) {
                        if(isset($parameters['title']) && !empty($parameters['title'])) {
                            if(isset($parameters['url']) && !empty($parameters['url'])) {
                                if(isset($parameters['text']) && !empty($parameters['text'])) {
                                    $url = strip_tags($parameters['url']);
                                    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE url = ?");
                                    $stmt->execute([$url]);
                                    $row = $stmt->fetch();
                                    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE pageID = ?");
                                    $stmt->execute([$parameters['edit']]);
                                    $r = $stmt->fetch();
                                    if($row == false || $row['url'] == $r['url']) {
                                        if($r['system'] == "0") {
                                            $text = Utils::purify($parameters['text']);
                                            $title = strip_tags($parameters['title']);
                                            $login = strip_tags($parameters['loginneed']);
                                            $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_pages SET `identifier`=?,`url`=?,`title`=?,`content`=?,`login`=? WHERE pageID = ?");
                                            $stmt->execute([$url, $url, $title, $text, $login, $parameters['edit']]);
                                            $success = "Diese Seite wurde erfolgreich gespeichert.";
                                        } else if($r['system'] == "1") {
                                            $text = Utils::purify($parameters['text']);
                                            $title = strip_tags($parameters['title']);
                                            $login = strip_tags($parameters['loginneed']);
                                            $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_pages SET `title`=?,`content`=?,`login`=? WHERE pageID = ?");
                                            $stmt->execute([$title, $text, $login, $parameters['edit']]);
                                            $success = "Diese Seite wurde erfolgreich gespeichert.";
                                        }
                                    } else {
                                        $errors['url'] = "Diese URL ist bereits vergeben.";
                                    }
                                } else {
                                    $errors['text'] = "Bitte gib einen Inhalt an.";
                                }
                            } else {
                                $errors['url'] = "Bitte gib eine URL an.";
                            }
                        } else {
                            $errors['title'] = "Bitte gib einen Title an.";
                        }
                    } else {
                        $errors['loginneed'] = "Bitte wähle eine valide Auswahl.";
                    }
                } else {
                    $errors['loginneed'] = "Bitte wähle ob die Seite nur mit Login sichtbar sein soll..";
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }

    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE pageID = ?");
    $stmt->execute([$parameters['edit']]);
    $row = $stmt->fetch();
    $page = $row;

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "page" => $page
    );
} else {
    $subpage = "index";
    $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
    $pages = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($pages, $row);
    }


    $site = array(
        "pages" => $pages,
        "site" => $subpage,
        "mainurl" => $mainurl
    );
}




