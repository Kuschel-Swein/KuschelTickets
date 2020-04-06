<?php
  /*
      Automatisch erstellte Config-Datei    
      Erstellt am 06.04.2020  15:20:18    
  */
$database = array(
    "database" => "kuscheltickets",
    "host" => "localhost",
    "port" => 3306,
    "user" => "root",
    "password" => ""
);

$config = array(
    "version" => "v2.1",
    "pagetitle" => "KuschelTickets",
    "databaseaccess" => $database,
    "db" => new PDO("mysql:host=".$database["host"].":".$database["port"].";dbname=".$database["database"], $database["user"], $database["password"]),
    "debugging" => array(
        "templates" => false,
        "php" => false, // stellst du dies auf true, kann es zu Anzeigefehlern kommen
        "database" => false
    ),
    "cookie" => "KuschelTickets",
    "seourls" => true,
    "faviconextension" => "png",
    "externalURLTitle" => true,
    "faviconmime" => "image/png",
    "proxyAllImages" => true,
    "externalURLFavicons" => true,
    "externalURLWarning" => true,
    "useDesktopNotification" => true,
    "emailnotifications" => false,
    "adminmail" => "admin@kuscheltickets.de",
    "mail" => array(
        "host" => "web.kuschelcontrol.de",
        "auth" => true,
        "port" => 25,
        "username" => "noreply@youtubeproxy.tk",
        "password" => "7jE9zrs7_Gi4",
        "from" => "noreply@youtubeproxy.tk"
    ),
    "state_colors" => array(
        "closed" => "red",
        "open" => "blue",
        "done" => "green"
    ),
    "recaptcha" => array(
        "public" => "6LcVEuYUAAAAAJR38WYKgYOXlyl8KPK6Q_vyOKPF",
        "secret" => "6LcVEuYUAAAAACHKmCQki87gMx9ohGAJR1EfE1dd",
        "use" => true,
        "version" => 2,
        "cases" => ['login','registration','passwordreset','addticket','ticketanswer','accountmanagement','notificationsettings','editortemplates']
    )
);

define("KT_N", "1");
