<?php
namespace KuschelTickets\lib\data\user\notification;

use KuschelTickets\lib\data\DatabaseObjectList;

class NotificationList extends DatabaseObjectList {
    public $databaseObject = Notification::class;
}