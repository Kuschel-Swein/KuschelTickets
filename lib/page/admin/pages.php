<?php
use kt\system\Utils;
use kt\system\CRSF;
use kt\system\Group;
use kt\system\exception\PageNotFoundException;
use kt\data\page\Page;
use kt\data\page\PageList;
use kt\system\KuschelTickets;
use kt\data\user\group\GroupList;

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
                                Page::create(array(
                                    "identifier" => $url,
                                    "url" => $url,
                                    "title" => $title,
                                    "content" => $text,
                                    "groups" => $groups,
                                    "system" => 0,
                                    "type" => $type
                                ));
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
                    $errors['title'] = "Bitte gib einen Titel an.";
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
                                    $page->update(array(
                                        "identifier" => $url,
                                        "url" => $url,
                                        "title" => $title,
                                        "content" => $text,
                                        "groups" => $groups
                                    ));
                                    $success = "Diese Seite wurde erfolgreich gespeichert.";
                                } else if($r['system'] == "1") {
                                    $text = Utils::purify($parameters['text']);
                                    $title = strip_tags($parameters['title']);
                                    $page->update(array(
                                        "title" => $title,
                                        "content" => $text
                                    ));
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

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "page" => $page,
        "selectedgroups" => $page->groups,
        "allgroups" => new GroupList()
    );
} else {
    $subpage = "index";

    $site = array(
        "pages" => new PageList(),
        "site" => $subpage
    );
}




