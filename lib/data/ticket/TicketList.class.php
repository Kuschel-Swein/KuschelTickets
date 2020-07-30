<?php
namespace KuschelTickets\lib\data\ticket;

use KuschelTickets\lib\data\DatabaseObjectList;

class TicketList extends DatabaseObjectList {
    public $databaseObject = Ticket::class;
}