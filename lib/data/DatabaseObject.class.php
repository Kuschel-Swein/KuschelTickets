<?php
namespace KuschelTickets\lib\data;
use KuschelTickets\lib\KuschelTickets;

abstract class DatabaseObject {
    public $tableName = "";
    public $tablePrimaryKey = "";
    public $tablePrimaryKeyValue = 0;

    public function __construct(int $primaryKeyValue) {
        if(empty($this->tableName)) {
            $this->tableName = "kuscheltickets".KT_N."_".self::formatTableName($this);
        } else {
            $this->tableName = "kuscheltickets".KT_N."_".$this->tableName;
        }
        if(empty($this->tablePrimaryKey)) {
            $primaryKey = get_class($this);
            $primaryKey = explode("\\", $primaryKey);
            $primaryKey = end($primaryKey);
            $this->tablePrimaryKey = strtolower($primaryKey)."ID";
        }
        $this->tablePrimaryKeyValue = $primaryKeyValue;
        $this->load();
    }

    public function load() {
        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM ".$this->tableName." WHERE ".$this->tablePrimaryKey." = ?");
        $stmt->execute([$this->tablePrimaryKeyValue]);
        $result = $stmt->fetch();
        
        if(!empty($result) && $result !== false) {
            foreach($result as $key => $value) {
                $jsonCheck = json_decode($value);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->$key = $jsonCheck;
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    public function update(Array $data) {
        $updateString = "UPDATE ".$this->tableName." SET ";
        $counter = 0;
        foreach($data as $key => $value) {
            $this->$key = $value;
            $updateString = $updateString."`".$key."`= :".strtolower($key);
            $counter++;
            if ($counter !== count($data)) $updateString .= ", ";
        }
        $updateString = $updateString." WHERE ".$this->tablePrimaryKey." = :".strtolower($this->tablePrimaryKey);
        KuschelTickets::getDB()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = KuschelTickets::getDB()->prepare($updateString);
        foreach($data as $key => &$value) {
            $stmt->bindParam(":".strtolower($key), $value, \PDO::PARAM_STR);
        }
        $stmt->bindParam(":".strtolower($this->tablePrimaryKey), $this->tablePrimaryKeyValue);
        $stmt->execute();
    }

    public static function create(Array $data) {
        $testObject = new static(0);
        $insertString = "INSERT INTO ".$testObject->tableName."(";
        $counter = 0;
        foreach($data as $key => $value) {
            $insertString = $insertString."`".$key."`";
            $counter++;
            if ($counter !== count($data)) $insertString .= ", ";
        }
        $insertString = $insertString.") VALUES (";
        $counter = 0;
        foreach($data as $key => $value) {
            $insertString = $insertString.":".strtolower($key);
            $counter++;
            if ($counter !== count($data)) $insertString .= ", ";
        }
        $insertString = $insertString.")";
        $stmt = KuschelTickets::getDB()->prepare($insertString);
        foreach($data as $key => &$value) {
            $stmt->bindParam(":".strtolower($key), $value);
        }
        $stmt->execute();
        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM ".$testObject->tableName." ORDER BY ".$testObject->tablePrimaryKey." DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();
        return new static($row[$testObject->tablePrimaryKey]);
    }

    public function delete() {
        $stmt = KuschelTickets::getDB()->prepare("DELETE FROM ".$this->tableName." WHERE ".$this->tablePrimaryKey." = ?");
        $stmt->execute([$this->tablePrimaryKeyValue]);
        foreach($this as $key => $value) {
            unset($this->$key);
        }
    }

    public function __get($name) {
        if(isset($this->$name)) {
            return $this->$name;
        }
        return false;
    }

    protected static function formatTableName(object $object) {
        $name = get_class($object);
        $name = explode("\\", $name);
        $name = end($name);
        $name = strtolower($name);
        $name = preg_replace('/(?<!\ )[A-Z][a-z]/', '_$0', $name);
        return $name;
    }
}