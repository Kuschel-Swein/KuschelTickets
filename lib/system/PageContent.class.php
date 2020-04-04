<?php
namespace KuschelTickets\lib\system;

class PageContent {

    public static function get(String $identifier) {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE identifier = ?");
        $stmt->execute([$identifier]);
        $row = $stmt->fetch();
        return $row['content'];
    }

    public static function getTitle(String $identifier) {
        global $config;

        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_pages WHERE identifier = ?");
        $stmt->execute([$identifier]);
        $row = $stmt->fetch();
        return $row['title'];
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
                "login" => $row['login'] == 1
            );
            array_push($pages, $data);
        }
        return $pages;
    }
}