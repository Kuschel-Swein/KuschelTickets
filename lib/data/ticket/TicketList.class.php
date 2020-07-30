<?php
namespace kt\data\ticket;

use kt\data\DatabaseObjectList;

class TicketList extends DatabaseObjectList {
    public $databaseObject = Ticket::class;
}