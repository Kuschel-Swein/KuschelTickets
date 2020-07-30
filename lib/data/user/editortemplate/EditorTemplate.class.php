<?php
namespace kt\data\user\editortemplate;

use kt\data\DatabaseObject;

class EditorTemplate extends DatabaseObject {
    public $tableName = "editortemplates";
    public $tablePrimaryKey = "templateID";
}