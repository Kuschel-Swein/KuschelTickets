<?php
namespace kt\page;

use kt\system\page\AbstractPage;
use kt\system\Utils;
use kt\data\user\User;
use kt\system\UserUtils;
use kt\system\CRSF;
use kt\system\recaptcha;
use kt\system\exception\AccessDeniedException;
use kt\system\exception\PageNotFoundException;
use kt\system\KuschelTickets;
use kt\data\user\editortemplate\EditorTemplate;

class editortemplatesPage extends AbstractPage {

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
                                            $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_editortemplates WHERE title = ? AND userID = ?");
                                            $stmt->execute([$title, KuschelTickets::getUser()->userID]);
                                            $row = $stmt->fetch();
                                            if($row === false) {
                                                if(isset($parameters['description']) && !empty($parameters['description'])) {
                                                    $description = strip_tags($parameters['description']);
                                                    if(!empty($description)) {
                                                        EditorTemplate::create(array(
                                                            "title" => $title,
                                                            "content" => $text,
                                                            "description" => $description,
                                                            "userID" => KuschelTickets::getUser()->userID
                                                        ));
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

            $editortpl = new EditorTemplate($parameters['edit']);
            if(!$editortpl->templateID) {
                throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
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
                                                        $editortpl->update(array(
                                                            "title" => $title,
                                                            "content" => $text,
                                                            "description" => $description
                                                        ));
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
        KuschelTickets::getTPL()->assign(array(
            "subpage" => $this->subpage,
            "errors" => $this->errors,
            "success" => $this->success,
            "recaptcha" => $this->recaptcha,
            "editortpl" => $this->editortpl
        ));
    }


}
?>