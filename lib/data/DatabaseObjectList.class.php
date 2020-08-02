<?php
namespace kt\data;
use kt\system\KuschelTickets;

abstract class DatabaseObjectList implements \Countable, \SeekableIterator {
    private $objects = [];
    private $position = 0;
    public $databaseObject = null;

    public function __construct(array $conditions = array(), String $orderBy = null, int $limit = null) {
        if($this->databaseObject == null) {
            throw new \InvalidArgumentException("you did not specify an database object");
        }
        $primaryKey = new $this->databaseObject(0);
        $primaryKey = $primaryKey->tablePrimaryKey;
        $tableName = new $this->databaseObject(0);
        $tableName = $tableName->tableName;

        $databaseObjectString = explode("\\", $this->databaseObject);
        $databaseObjectString = end($databaseObjectString);

        $conditionString = "";
        if(!empty($conditions)) {
            foreach($conditions as $key => $value) {
                if(empty($conditionString)) {
                    if($value == null) {
                        $conditionString = "WHERE ".$key." IS NULL";
                    } else {
                        $conditionString = "WHERE ".$key." = :".strtolower($key)."";
                    }
                } else {
                    if($value == null) {
                        $conditionString = $conditionString." AND ".$key." IS NULL";
                    } else {
                        $conditionString = $conditionString." AND ".$key." = :".strtolower($key)."";
                    }
                }
            }
        }
        if(!empty($orderBy)) {
            if(!preg_match("/^(?:ORDER\ BY)(?:.*)(?:ASC|DESC)$/", $orderBy)) {
                throw new \InvalidArgumentException("you did specify an invalid order by selector");
            }
            if(!empty($conditionString)) {
                $orderBy = " ".$orderBy;
            }
            $conditionString = $conditionString.$orderBy;
        }
        if(!empty($limit)) {
            if(!empty($conditionString)) {
                $limit = " LIMIT ".$limit;
            }
            $conditionString = $conditionString.$limit;
        }
        $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM ".$tableName." ".$conditionString);
        foreach($conditions as $key => &$value) {
            if($conditions[$key] == false) {
                continue;
            }
            $stmt->bindParam(":".strtolower($key), $value);
        }
        $stmt->execute();
        while($result = $stmt->fetch()) {
            array_push($this->objects, new $this->databaseObject($result[$primaryKey]));
        }
        $this->position = 0;
    }

    public function count() {
        return count($this->objects);
    }

    public function rewind() {
        if(isset($this->objects[0])) {
            $this->seek(0);
        }
        reset($this->objects);
    }

    public function current() {
        return $this->objects[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->position++;
    }

    public function valid() {
        return isset($this->objects[$this->position]);
    }

    public function seek(int $position) {
        $this->position = $position;
        if (!$this->valid()) {
			throw new \OutOfBoundsException();
		}
    }

    public function getObjects() {
        return $this->objects;
    }
}