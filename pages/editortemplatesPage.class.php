<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\data\user\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\recaptcha;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\exception\PageNotFoundException;
use KuschelTickets\lib\KuschelTickets;

class editortemplatesPage extends Page {

    private $subpage;
    private $errors;
    private $success;
    private $recaptcha;

    public function readParameters(Array $parameters) {
        global $config;

        if(!KuschelTickets::getUser()->userID) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        $recaptcha = recaptcha::build("editortemplates");
        $this->recaptcha = $recaptcha;

        $errors = array(
            "text" => false,
            "title" => false,
            "token" => false,
            "description" => false
        );
        $success = false;
        $editortpl = null;

        if(!KuschelTickets::getUser()->hasPermission("general.editor.templates")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(isset($parameters['add'])) {
            $subpage = "add";
            
            if(isset($parameters['submit'])) {
                if(recaptcha::validate("editortemplates")) {
                    if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                        if(CRSF::validate($parameters['CRSF'])) {
                            if(isset($parameters['title']) && !empty($parameters['title'])) {
                                $title = strip_tags($parameters['title']);
                                if(!empty($title)) {
                                    if(isset($parameters['text']) && !empty($parameters['text'])) {
                                        $text = Utils::purify($parameters['text']);
                                        if(!empty($text) && $text !== "<p></p>") {
                                            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE title = ? AND userID = ?");
                                            $stmt->execute([$title, KuschelTickets::getUser()->userID]);
                                            $row = $stmt->fetch();
                                            if($row === false) {
                                                if(isset($parameters['description']) && !empty($parameters['description'])) {
                                                    $description = strip_tags($parameters['description']);
                                                    if(!empty($description)) {
                                                        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_editortemplates(`title`, `content`, `description`, `userID`) VALUES (?, ?, ?, ?)");
                                                        $stmt->execute([$title, $text, $description, KuschelTickets::getUser()->userID]);
                                                        $success = "Deine Editorvorlage wurde erfolgreich gespeichert.";
                                                    } else {
                                                        $errors['description'] = "Bitte gib eine Beschreibung an.";
                                                    }
                                                } else {
                                                    $errors['description'] = "Bitte gib eine Beschreibung an.";
                                                }
                                            } else {
                                                $errors['title'] = "Du verwendest diesen Titel bereits in einer anderen Vorlage.";
                                            }
                                        } else {
                                            $errors['text'] = "Bitte gib einen Text an.";
                                        }
                                    } else {
                                        $errors['text'] = "Bitte gib einen Text an.";
                                    }
                                } else {
                                    $errors['title'] = "Bitte gib einen Titel an.";
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
                } else {
                    $errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
                }
            }

        } else if(isset($parameters['edit'])) {
            $subpage = "edit";
            if(empty($parameters['edit'])) {
                throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
            }

            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE userID = ? AND templateID = ?");
            $stmt->execute([KuschelTickets::getUser()->userID, $parameters['edit']]);
            $row = $stmt->fetch();
            if($row == false) {
                throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
            } else {
                $editortpl = $row;
            }

            if(isset($parameters['submit'])) {
                if(recaptcha::validate("editortemplates")) {
                    if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                        if(CRSF::validate($parameters['CRSF'])) {
                            if(isset($parameters['title']) && !empty($parameters['title'])) {
                                $title = strip_tags($parameters['title']);
                                if(!empty($title)) {
                                    if(isset($parameters['text']) && !empty($parameters['text'])) {
                                        $text = Utils::purify($parameters['text']);
                                        if(!empty($text) && $text !== "<p></p>") {
                                            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE title = ? AND userID = ?");
                                            $stmt->execute([$title, KuschelTickets::getUser()->userID]);
                                            $row = $stmt->fetch();
                                            if($row === false || $row['title'] == $title) {
                                                if(isset($parameters['description']) && !empty($parameters['description'])) {
                                                    $description = strip_tags($parameters['description']);
                                                    if(!empty($description)) {
                                                        $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_editortemplates SET `title`=?,`content`=?,`description`=? WHERE templateID = ?");
                                                        $stmt->execute([$title, $text, $description, $parameters['edit']]);
                                                        $success = "Deine Editorvorlage wurde erfolgreich gespeichert.";
                                                        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE userID = ? AND templateID = ?");
                                                        $stmt->execute([KuschelTickets::getUser()->userID, $parameters['edit']]);
                                                        $row = $stmt->fetch();
                                                        $editortpl = $row;
                                                    } else {
                                                        $errors['description'] = "Bitte gib eine Beschreibung an.";
                                                    }
                                                } else {
                                                    $errors['description'] = "Bitte gib eine Beschreibung an.";
                                                }
                                            } else {
                                                $errors['title'] = "Du verwendest diesen Titel bereits in einer anderen Vorlage.";
                                            }
                                        } else {
                                            $errors['text'] = "Bitte gib einen Text an.";
                                        }
                                    } else {
                                        $errors['text'] = "Bitte gib einen Text an.";
                                    }
                                } else {
                                    $errors['title'] = "Bitte gib einen Titel an.";
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
                } else {
                    $errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
                }
            }

        } else if(isset($_GET['editortemplates']) || isset($_GET['editortemplates/'])) {
            $subpage = "index";
        } else {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }

        $this->subpage = $subpage;
        $this->errors = $errors;
        $this->success = $success;
        $this->editortpl = $editortpl;
    }

    public function assign() {
        return array(
            "subpage" => $this->subpage,
            "errors" => $this->errors,
            "success" => $this->success,
            "recaptcha" => $this->recaptcha,
            "editortpl" => $this->editortpl
        );
    }


}
?>