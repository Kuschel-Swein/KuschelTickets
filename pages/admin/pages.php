<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\system\Group;
use KuschelTickets\lib\exception\PageNotFoundException;
use KuschelTickets\lib\data\page\Page;
use KuschelTickets\lib\data\page\PageList;
use KuschelTickets\lib\KuschelTickets;
use KuschelTickets\lib\data\user\group\GroupList;

/**
 * 
 * Pages Admin Page Handler
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";
    if(empty($parameters['add'])) {
        $type = "0";
    } else {
        $type = $parameters['add'];
    }
    $errors = array(
        "title" => false,
        "url" => false,
        "text" => false,
        "token" => false
    );
    $groups = [];
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['groupsaccess']) && !empty($parameters['groupsaccess'])) {
                    $groups = strip_tags($parameters['groupsaccess']);
                    $groups = explode(",", $groups);
                } else {
                    $groups = [];
                }
                if(isset($parameters['title']) && !empty($parameters['title'])) {
                    if(isset($parameters['url']) && !empty($parameters['url'])) {
                        if(isset($parameters['text']) && !empty($parameters['text'])) {
                            $url = strip_tags($parameters['url']);
                            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE url = ?");
                            $stmt->execute([$url]);
                            $row = $stmt->fetch();
                            if($row == false) {
                                if($type !== "1" && $type !== "2") {
                                    $text = Utils::purify($parameters['text']);
                                } else {
                                    $text = $parameters['text'];
                                }
                                $title = strip_tags($parameters['title']);
                                $groups = json_encode($groups);
                                $stmt = KuschelTickets::getDB()->prepare("INSERT INTO kuscheltickets".KT_N."_pages(`identifier`, `url`, `title`, `content`, `groups`, `system`, `type`) VALUES (?, ?, ?, ?, ?, 0, ?)");
                                $stmt->execute([$url, $url, $title, $text, $groups, $type]);
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
        "selectedgroups" => $groups,
        "type" => $type,
        "allgroups" => new GroupList()
    );

} else if(isset($parameters['edit'])) {
    $subpage = "edit";
    if(empty($parameters['edit'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $page = new Page($parameters['edit']);

    if(!$page->pageID) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $type = $page->type;

    $groups = $page->groups;

    $errors = array(
        "title" => false,
        "url" => false,
        "text" => false,
        "token" => false
    );
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['groupsaccess']) && !empty($parameters['groupsaccess'])) {
                    $groups = strip_tags($parameters['groupsaccess']);
                    $groups = explode(",", $groups);
                } else {
                    $groups = [];
                }
                $groups = json_encode($groups);
                if(isset($parameters['title']) && !empty($parameters['title'])) {
                    if(isset($parameters['url']) && !empty($parameters['url'])) {
                        if(isset($parameters['text']) && !empty($parameters['text'])) {
                            $url = strip_tags($parameters['url']);
                            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE url = ?");
                            $stmt->execute([$url]);
                            $row = $stmt->fetch();
                            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE pageID = ?");
                            $stmt->execute([$parameters['edit']]);
                            $r = $stmt->fetch();
                            if($row == false || $row['url'] == $r['url']) {
                                if($r['system'] == "0") {
                                    if($type !== "1" && $type !== "2") {
                                        $text = Utils::purify($parameters['text']);
                                    } else {
                                        $text = $parameters['text'];
                                    }
                                    $title = strip_tags($parameters['title']);
                                    $login = strip_tags($parameters['loginneed']);
                                    $stmt = KuschelTickets::getDB()->prepare("UPDATE kuscheltickets".KT_N."_pages SET `identifier`=?,`url`=?,`title`=?,`content`=?,`groups`=? WHERE pageID = ?");
                                    $stmt->execute([$url, $url, $title, $text, $groups, $parameters['edit']]);
                                    $success = "Diese Seite wurde erfolgreich gespeichert.";
                                } else if($r['system'] == "1") {
                                    $text = Utils::purify($parameters['text']);
                                    $title = strip_tags($parameters['title']);
                                    $login = strip_tags($parameters['loginneed']);
                                    $stmt = KuschelTickets::getDB()->prepare("UPDATE kuscheltickets".KT_N."_pages SET `title`=?,`content`=? WHERE pageID = ?");
                                    $stmt->execute([$title, $text, $parameters['edit']]);
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
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }

    $page->load();

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "page" => $page,
        "selectedgroups" => $groups,
        "allgroups" => new GroupList()
    );
} else {
    $subpage = "index";

    $site = array(
        "pages" => new PageList(),
        "site" => $subpage
    );
}




