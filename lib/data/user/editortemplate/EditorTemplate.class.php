<?php
namespace KuschelTickets\lib\data\user\editortemplate;

use KuschelTickets\lib\data\DatabaseObject;

class EditorTemplate extends DatabaseObject {
    public $tableName = "editortemplates";
    public $tablePrimaryKey = "templateID";
}