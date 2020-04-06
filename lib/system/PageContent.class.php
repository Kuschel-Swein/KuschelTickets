<?php
namespace KuschelTickets\lib\system;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\Group;

class PageContent {

    public static function get(String $identifier) {
        global $config;
        global $templateengine;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE identifier = ?");
        $stmt->execute([$identifier]);
        $row = $stmt->fetch();
        $content = $row['content'];
        
        $username = "Gast";
        $userid = "0";
        $group = "Gast";
        $email = "gast@gast.gast";
        $tickets = "0";

        if(UserUtils::isLoggedIn()) {
            $user = new User(UserUtils::getUserID());
            $username = $user->getUserName();
            $userid = $user->userID;
            $group = $user->getGroup()->getGroupName();
            $email = $user->getEmail();
            $tickets = $user->getTicketCount();
        }
        $content = str_replace("{@USERNAME}", $username, $content);
        $content = str_replace("{@USERID}", $userid, $content);
        $content = str_replace("{@USERGROUP}", $group, $content);
        $content = str_replace("{@EMAIL}", $email, $content);
        $content = str_replace("{@TICKETS}", $tickets, $content);


        return $content;
    }

    public static function getTitle(String $identifier) {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE identifier = ?");
        $stmt->execute([$identifier]);
        $row = $stmt->fetch();
        return $row['title'];
    }

    public static function getType(String $identifier) {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE identifier = ?");
        $stmt->execute([$identifier]);
        $row = $stmt->fetch();
        return $row['type'];
    }

    public static function getAll() {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages");
        $stmt->execute();
        $pages = [];
        while($row = $stmt->fetch()) {
            $data = array(
                "identifier" => $row['identifier'],
                "url" => $row['url'],
                "groups" => json_decode($row['groups'], true)
            );
            array_push($pages, $data);
        }
        return $pages;
    }
}