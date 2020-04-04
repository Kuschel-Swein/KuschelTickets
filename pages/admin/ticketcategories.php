<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\TicketCategory;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

/**
 * 
 * Ticket Kategorie Admin Page Handler
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";

    $errors = array(
        "text" => false,
        "token" => false,
        "custominput" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
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
                            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryName = ?");
                            $stmt->execute([$text]);
                            $row = $stmt->fetch();
                            if($row) {
                                $errors['text'] = "Dieser Name ist bereits vergeben.";
                            } else {
                                $inputs = json_encode($inputdata);
                                $inputs = strip_tags($inputs);
                                $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_ticket_categorys(`categoryName`, `inputs`) VALUES (?, ?)");
                                $stmt->execute([$text, $inputs]);
                                $success = "Diese Kategorie wurde erfolgreich erstellt.";
                            }
                        }
                    } else {
                        $text = strip_tags($parameters['text']);
                        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryName = ?");
                        $stmt->execute([$text]);
                        $row = $stmt->fetch();
                        if($row) {
                            $errors['text'] = "Dieser Name ist bereits vergeben.";
                        } else {
                            $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_ticket_categorys(`categoryName`, `inputs`) VALUES (?, ?)");
                            $inputs = "[]";
                            $stmt->execute([$text, $inputs]);
                            $success = "Diese Kategorie wurde erfolgreich erstellt.";
                        }
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
        "errors" => $errors
    );

} else if(isset($parameters['edit'])) {
    $subpage = "edit";
    if(empty($parameters['edit'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }
    $category = new TicketCategory($parameters['edit']);
    
    if(!$category->exists()) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }


    $errors = array(
        "text" => false,
        "token" => false,
        "custominput" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
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
                            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryName = ?");
                            $stmt->execute([$text]);
                            $row = $stmt->fetch();
                            if($row && $text !== $category->getName()) {
                                $errors['text'] = "Dieser Name ist bereits vergeben.";
                            } else {
                                $inputs = json_encode($inputdata);
                                $inputs = strip_tags($inputs);
                                $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_ticket_categorys SET `categoryName`=?,`inputs`=? WHERE categoryID = ?");
                                $stmt->execute([$text, $inputs, $category->categoryID]);
                                $success = "Diese Kategorie wurde erfolgreich bearbeitet.";
                            }
                        } 
                    } else {
                        $text = strip_tags($parameters['text']);
                        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryName = ?");
                        $stmt->execute([$text]);
                        $row = $stmt->fetch();
                        if($row && $text !== $category->getName()) {
                            $errors['text'] = "Dieser Name ist bereits vergeben.";
                        } else {
                            $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_ticket_categorys SET `categoryName`=?,`inputs`=? WHERE categoryID = ?");  
                            $inputs = "[]";                           
                            $stmt->execute([$text, $inputs, $category->categoryID]);
                            $success = "Diese Kategorie wurde erfolgreich bearbeitet.";
                        }
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
    $inputjson = $category->getInputJSON();
    $inputjson = json_decode($inputjson);
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
        "inputjson" => $inputs
    );
} else {
    $subpage = "index";

    $ticketcategories = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($ticketcategories, new TicketCategory($row['categoryID']));
    }

    $site = array(
        "ticketcategories" => $ticketcategories,
        "site" => $subpage
    );
}




