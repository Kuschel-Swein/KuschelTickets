<?php
namespace kt\data\page;

use kt\data\DatabaseObject;
use kt\system\KuschelTickets;

class Page extends DatabaseObject {
    public $tableName = "pages";

    public function getContent() {
        $content = $this->content;
        if($this->type !== 2) {
            $username = (KuschelTickets::getUser()->userID) ? KuschelTickets::getUser()->username : "Gast";
            $userid = (KuschelTickets::getUser()->userID) ? KuschelTickets::getUser()->userID : "0";
            $usergroup = (KuschelTickets::getUser()->userID) ? KuschelTickets::getUser()->getGroup()->name : "Gäste";
            $email = (KuschelTickets::getUser()->userID) ? KuschelTickets::getUser()->email : "guest@guest.guest";
            $tickets = (KuschelTickets::getUser()->userID) ? count(KuschelTickets::getUser()->getTickets()) : "0";

            $content = str_replace("{@USERNAME}", $username, $content);
            $content = str_replace("{@USERID}", $userid, $content);
            $content = str_replace("{@USERGROUP}", $usergroup, $content);
            $content = str_replace("{@EMAIL}", $email, $content);
            $content = str_replace("{@TICKETS}", $tickets, $content);
        }
        return $content;
    }
}