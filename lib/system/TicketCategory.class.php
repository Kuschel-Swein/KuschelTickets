<?php
namespace KuschelTickets\lib\system;

class TicketCategory {

    public $categoryID;

    public function __construct(int $categoryID) {
        $this->categoryID = $categoryID;     
    }

    public function getName() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
        $stmt->execute([$this->categoryID]);
        $row = $stmt->fetch();
        return $row['categoryName'];
    }

    public function getColor() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
        $stmt->execute([$this->categoryID]);
        $row = $stmt->fetch();
        return $row['color'];
    }

    public function getInputJSON() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
        $stmt->execute([$this->categoryID]);
        $row = $stmt->fetch();
        return $row['inputs'];
    }

    public function exists() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
        $stmt->execute([$this->categoryID]);
        $row = $stmt->fetch();
        return isset($row);
    }

    public function getFormattedInputfields() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
        $stmt->execute([$this->categoryID]);
        $inputs = [];
        $row = $stmt->fetch();
        $data = $row['inputs'];
        $data = JSON_decode($data);
        $counter = 0;
        foreach($data as $input) {
            if($input->type == "text") {
                $helper = array(
                    "id" => $counter,
                    "title" => $input->title,
                    "required" => $input->required,
                    "field" => "<div class='ui input'><input type='text' name='customInput".$counter."'></div>",
                    "description" => $input->description
                );
                $counter++;
                array_push($inputs, $helper);
            } else if($input->type == "number") {
                $helper = array(
                    "id" => $counter,
                    "title" => $input->title,
                    "required" => $input->required,
                    "field" => "<div class='ui input'><input type='number' name='customInput".$counter."'></div>",
                    "description" => $input->description
                );
                $counter++;
                array_push($inputs, $helper);
            } else if($input->type == "email") {
                $helper = array(
                    "id" => $counter,
                    "title" => $input->title,
                    "required" => $input->required,
                    "field" => "<div class='ui input'><input type='email' name='customInput".$counter."'></div>",
                    "description" => $input->description
                );
                $counter++;
                array_push($inputs, $helper);
            } else if($input->type == "checkbox") {
                $helper = array(
                    "id" => $counter,
                    "title" => $input->title,
                    "required" => $input->required,
                    "field" => "<div class='ui checkbox'><input type='checkbox' name='customInput".$counter."'><label></label></div><br>",
                    "description" => $input->description
                );
                $counter++;
                array_push($inputs, $helper);
            } else if($input->type == "select") {
                $multiple = "";
                if(isset($input->multiple)) {
                    if($input->multiple) {
                        $multiple = " multiple";
                    }
                }
                $field = "<div class='ui selection dropdown custom".$multiple."'>
                        <input type='hidden' name='customInput".$counter."'>
                        <i class='dropdown icon'></i>
                        <div class='default text'></div>
                        <div class='menu'>";
                foreach($input->options as $option) {
                    $field = $field.'<div class="item" data-value="'.$option->value.'">'.$option->name.'</div>';
                }
                $field = $field."</div>
                </div>";
                $helper = array(
                    "id" => $counter,
                    "title" => $input->title,
                    "required" => $input->required,
                    "field" => $field,
                    "description" => $input->description
                );
                $counter++;
                array_push($inputs, $helper);
            }    
        }
        return $inputs;
    }

    public function getInputCount() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
        $stmt->execute([$this->categoryID]);
        $inputs = [];
        $row = $stmt->fetch();
        $data = $row['inputs'];
        $data = JSON_decode($data);
        $counter = 0;
        foreach($data as $input) {
            $counter++;
        }
        return $counter;
    }

    public function validateInputs(Array $parameters) {
        global $config;

        $result = array(
            "errors" => array()
        );

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_ticket_categorys WHERE categoryID = ?");
        $stmt->execute([$this->categoryID]);
        $inputs = [];
        $row = $stmt->fetch();
        $data = $row['inputs'];
        $data = JSON_decode($data);
        $counter = 0;
        
        for($i = 0; $i < $this->getInputCount(); $i++) {
            if($data[$i]->type == "checkbox" && isset($parameters['customInput'.$i])) {
                $parameters['customInput'.$i] = "ON";
            }
            if(isset($parameters['customInput'.$i]) && !empty($parameters['customInput'.$i])) {
                if($data[$i]->type == "text") {
                    if(strlen($parameters['customInput'.$i]) >= $data[$i]->minlength) {
                        if(strlen($parameters['customInput'.$i]) <= $data[$i]->maxlength) {
                            if($data[$i]->pattern !== "") {
                                if(preg_match($data[$i]->pattern, $parameters['customInput'.$i])) {
                                    $result['errors'][$i] = false;
                                    $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> ".$parameters['customInput'.$i]."</p>";
                                } else {
                                    $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." entspricht nicht dem geforderten Format.";
                                }
                            } else {
                                $result['errors'][$i] = false;
                                $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> ".$parameters['customInput'.$i]."</p>"; 
                            }
                        } else {
                            $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." ist leider zu lang, es sind maximal ".$data[$i]->maxlength." Zeichen erlaubt.";
                        }
                    } else {
                        $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." ist leider zu kurz, es werden mindestens ".$data[$i]->minlength." Zeichen benötigt.";
                    }
                } else if($data[$i]->type == "number") {
                    if(is_numeric($parameters['customInput'.$i])) {
                        if($parameters['customInput'.$i] <= $data[$i]->max) {
                            if($parameters['customInput'.$i] >= $data[$i]->min) {
                                $result['errors'][$i] = false;
                                $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> ".$parameters['customInput'.$i]."</p>";
                            } else {
                                $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." darf minimal ".$data[$i]->min." sein.";
                            }
                        } else {
                            $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." darf maximal ".$data[$i]->max." sein.";
                        }
                    } else {
                        $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." ist keine Zahl.";
                    }
                } else if($data[$i]->type == "email") {
                    if(filter_var($parameters['customInput'.$i], FILTER_VALIDATE_EMAIL)) {
                        if(strlen($parameters['customInput'.$i]) >= $data[$i]->minlength) {
                            if(strlen($parameters['customInput'.$i]) <= $data[$i]->maxlength) {
                                if($data[$i]->pattern !== "") {
                                    if(preg_match($data[$i]->pattern, $parameters['customInput'.$i])) {
                                        $result['errors'][$i] = false;
                                        $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> ".$parameters['customInput'.$i]."</p>";
                                    } else {
                                        $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." entspricht nicht dem geforderten Format.";
                                    }
                                } else {
                                    $result['errors'][$i] = false;
                                    $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> ".$parameters['customInput'.$i]."</p>"; 
                                }
                            } else {
                                $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." ist leider zu lang, es sind maximal ".$data[$i]->maxlength." Zeichen erlaubt.";
                            }
                        } else {
                            $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." ist leider zu kurz, es werden mindestens ".$data[$i]->minlength." Zeichen benötigt.";
                        }
                    } else {
                        $result['errors'][$i] = "Deine Eingabe für das Feld ".$data[$i]->title." ist keine E-Mail Adresse";
                    }
                } else if($data[$i]->type == "checkbox") {
                    $result['errors'][$i] = false;
                    $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> "."Ja"."</p>"; 
                } else if($data[$i]->type == "select") {
                    $values = [];
                    foreach($data[$i]->options as $option) {
                        array_push($values, $option->value);
                    }
                    if(isset($data[$i]->multiple) && $data[$i]->multiple == true) {
                        $valid = false;
                        $selections = explode(",", $parameters['customInput'.$i]);
                        if(count($selections) > 0) {
                            $valid = true;
                        }
                        foreach($selections as $selection) {
                            if(!in_array($selection, $values)) {
                                $valid = false;
                                break;
                            }
                        }
                        if(!$valid) {
                            $result['errors'][$i] = "Bitte wähle einen Wert für das Feld ".$data[$i]->title.".";
                        } else {
                            $result['errors'][$i] = false;
                            $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> ".$parameters['customInput'.$i]."</p>"; 
                        }
                    } else {
                        if(in_array($parameters['customInput'.$i], $values)) {
                            $result['errors'][$i] = false;
                            $result['results'][$i] = "<p><strong>".$data[$i]->title."</strong> ".$parameters['customInput'.$i]."</p>"; 
                        } else {
                            $result['errors'][$i] = "Bitte wähle einen Wert für das Feld ".$data[$i]->title.".";
                        }
                    }
                }
            } else {
                if($data[$i]->required) {
                    $result['errors'][$i] = "Bitte gib für das Feld ".$data[$i]->title." einen Wert an.";
                }
            }
        }
        return $result;
    }
}