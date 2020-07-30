<?php
use kt\system\Utils;
use kt\data\ticket\category\Category;
use kt\system\CRSF;
use kt\Exceptions\PageNotFoundException;
use kt\system\KuschelTickets;
use kt\data\ticket\category\CategoryList;

/**
 * 
 * Ticket Kategorie Admin Page Handler
 * 
 */
$colors = ['red', 'orange', 'yellow', 'olive', 'green', 'teal', 'blue', 'violet', 'purple', 'pink', 'brown', 'grey', 'black'];;
if(isset($parameters['add'])) {
    $subpage = "add";

    $errors = array(
        "text" => false,
        "token" => false,
        "custominput" => false,
        "color" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    if(isset($parameters['color']) && !empty($parameters['color'])) {
                        if(in_array($parameters['color'], $colors)) {
                            $color = $parameters['color'];
                            if(isset($parameters['custominputCounter']) && !empty($parameters['custominputCounter']) && is_numeric($parameters['custominputCounter'])) {
                                $count = (int) $parameters['custominputCounter'];
                                $inputdata = [];
                                for($i = 0; $i <= $count; $i++) {
                                    if(isset($parameters['customInputData'.$i])) {
                                        $json = $parameters['customInputData'.$i];
                                        $json = json_decode($json);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            if(isset($json->type)) {
                                                $types = ['number', 'email', 'checkbox', 'text', 'select'];
                                                if(in_array($json->type, $types)) {
                                                    if(isset($json->description) && isset($json->required) && isset($json->title)) {
                                                        $type = $json->type;
                                                        $validated = false;
                                                        if($type == "text" || $type == "email") {
                                                            if(isset($json->minlength) && isset($json->maxlength) && isset($json->pattern)) {
                                                                if(!empty($json->pattern)) {
                                                                    if(preg_match($json->pattern, null) !== false) {
                                                                        $validated = true;
                                                                    } else {
                                                                        $lastregexerror = preg_last_error();
                                                                        if($lastregexerror == PREG_NO_ERROR) {
                                                                            $validated = true;
                                                                        }
                                                                    }
                                                                } else {
                                                                    $validated = true;
                                                                }
                                                            }
                                                        } else if($type == "number") {
                                                            if(isset($json->min) && isset($json->max)) {
                                                                $validated = true;
                                                            }
                                                        } else if($type == "checkbox") {
                                                            $validated = true;
                                                        } else if($type == "select") {
                                                            if(isset($json->options)) {
                                                                $testpassed = true;
                                                                foreach($json->options as $option) {
                                                                    if(!isset($option->name) || !isset($option->value)) {
                                                                        $testpassed = false;
                                                                    }
                                                                }
                                                                if($testpassed) {
                                                                    $validated = true;
                                                                }
                                                            }
                                                        }
                                                        if($validated) {
                                                            array_push($inputdata, $json);
                                                        } else {
                                                            $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                                        }
                                                    } else {
                                                        $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                                    }
                                                } else {
                                                    $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                                }
                                            } else {
                                                $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                            }
                                        } else {
                                            $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                        }
                                    }
                                }
                                if($errors['custominput'] == false) {
                                    $text = strip_tags($parameters['text']);
                                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categories WHERE categoryName = ?");
                                    $stmt->execute([$text]);
                                    $row = $stmt->fetch();
                                    if($row) {
                                        $errors['text'] = "Dieser Name ist bereits vergeben.";
                                    } else {
                                        $inputs = json_encode($inputdata);
                                        $inputs = strip_tags($inputs);
                                        Category::create(array(
                                            "categoryName" => $text,
                                            "inputs" => $inputs,
                                            "color" => $color
                                        ));
                                        $success = "Diese Kategorie wurde erfolgreich erstellt.";
                                    }
                                }
                            } else {
                                $text = strip_tags($parameters['text']);
                                $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categories WHERE categoryName = ?");
                                $stmt->execute([$text]);
                                $row = $stmt->fetch();
                                if($row) {
                                    $errors['text'] = "Dieser Name ist bereits vergeben.";
                                } else {
                                    $inputs = "[]";
                                    Category::create(array(
                                        "categoryName" => $text,
                                        "inputs" => $inputs,
                                        "color" => $color
                                    ));
                                    $success = "Diese Kategorie wurde erfolgreich erstellt.";
                                }
                            }
                        } else {
                            $errors['color'] = "Bitte gib eine valide Farbe an.";
                        }
                    } else {
                        $errors['color'] = "Bitte wähle eine Farbe.";
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
        "colors" => $colors
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
        "token" => false,
        "custominput" => false,
        "color" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    if(isset($parameters['color']) && !empty($parameters['color'])) {
                        if(in_array($parameters['color'], $colors)) {
                            $color = $parameters['color'];
                            if(isset($parameters['custominputCounter']) && !empty($parameters['custominputCounter']) && is_numeric($parameters['custominputCounter'])) {
                                $count = (int) $parameters['custominputCounter'];
                                $inputdata = [];
                                for($i = 0; $i <= $count; $i++) {
                                    if(isset($parameters['customInputData'.$i])) {
                                        $json = $parameters['customInputData'.$i];
                                        $json = json_decode($json);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            if(isset($json->type)) {
                                                $types = ['number', 'email', 'checkbox', 'text', 'select'];
                                                if(in_array($json->type, $types)) {
                                                    if(isset($json->description) && isset($json->required) && isset($json->title)) {
                                                        $type = $json->type;
                                                        $validated = false;
                                                        if($type == "text" || $type == "email") {
                                                            if(isset($json->minlength) && isset($json->maxlength) && isset($json->pattern)) {
                                                                if(!empty($json->pattern)) {
                                                                    if(preg_match($json->pattern, null) !== false) {
                                                                        $validated = true;
                                                                    } else {
                                                                        $lastregexerror = preg_last_error();
                                                                        if($lastregexerror == PREG_NO_ERROR) {
                                                                            $validated = true;
                                                                        }
                                                                    }
                                                                } else {
                                                                    $validated = true;
                                                                }
                                                            }
                                                        } else if($type == "number") {
                                                            if(isset($json->min) && isset($json->max)) {
                                                                $validated = true;
                                                            }
                                                        } else if($type == "checkbox") {
                                                            $validated = true;
                                                        } else if($type == "select") {
                                                            if(isset($json->options)) {
                                                                $testpassed = true;
                                                                foreach($json->options as $option) {
                                                                    if(!isset($option->name) || !isset($option->value)) {
                                                                        $testpassed = false;
                                                                    }
                                                                }
                                                                if($testpassed) {
                                                                    $validated = true;
                                                                }
                                                            }
                                                        }
                                                        
                                                        if($validated) {
                                                            array_push($inputdata, $json);
                                                        } else {
                                                            $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                                        }
                                                    } else {
                                                        $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                                    }
                                                } else {
                                                    $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                                }
                                            } else {
                                                $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                            }
                                        } else {
                                            $errors['custominput'] = "Diese Anfrage war fehlerhaft, bitte wiederhole sie.";
                                        }
                                    }
                                } 
                                if($errors['custominput'] == false) {
                                    $text = strip_tags($parameters['text']);
                                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categories WHERE categoryName = ?");
                                    $stmt->execute([$text]);
                                    $row = $stmt->fetch();
                                    if($row && $text !== $category->categoryName) {
                                        $errors['text'] = "Dieser Name ist bereits vergeben.";
                                    } else {
                                        $inputs = json_encode($inputdata);
                                        $inputs = strip_tags($inputs);
                                        $category->update(array(
                                            "categoryName" => $text,
                                            "inputs" => $inputs,
                                            "color" => $color
                                        ));
                                        $success = "Diese Kategorie wurde erfolgreich bearbeitet.";
                                    }
                                } 
                            } else {
                                $text = strip_tags($parameters['text']);
                                $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categories WHERE categoryName = ?");
                                $stmt->execute([$text]);
                                $row = $stmt->fetch();
                                if($row && $text !== $category->categoryName) {
                                    $errors['text'] = "Dieser Name ist bereits vergeben.";
                                } else {
                                    $inputs = "[]";                           
                                    $category->update(array(
                                        "categoryName" => $text,
                                        "inputs" => $inputs,
                                        "color" => $color
                                    ));
                                    $success = "Diese Kategorie wurde erfolgreich bearbeitet.";
                                }
                            }
                        } else {
                            $errors['color'] = "Bitte gib eine valide Farbe an.";
                        }
                    } else {
                        $errors['color'] = "Bitte wähle eine Farbe.";
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
    $inputjson = $category->inputs;
    $inputnames = [];
    $inputs = [];
    foreach($inputjson as $input) {
        array_push($inputnames, $input->title);
        array_push($inputs, json_encode($input));
    }
    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "ticketcategory" => $category,
        "inputnames" => $inputnames,
        "inputjson" => $inputs,
        "colors" => $colors
    );
} else {
    $subpage = "index";

    $site = array(
        "ticketcategories" => new CategoryList(),
        "site" => $subpage
    );
}




