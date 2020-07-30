<?php
namespace kt\data\user\notification;

use kt\data\DatabaseObjectList;

class NotificationList extends DatabaseObjectList {
    public $databaseObject = Notification::class;
}