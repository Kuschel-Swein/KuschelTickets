<?php
  /*
      Automatisch erstellte Config-Datei    
      Erstellt am 27.04.2020  21:50:03    
  */
$database = array(
    "database" => "kuscheltickets",
    "host" => "localhost",
    "port" => 3306,
    "user" => "root",
    "password" => ""
);

$config = array(
    "version" => "v2.2",
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
    "ticketRating" => true,
    "ticketRatingIcon" => "star",
    "pdfexport" => true,
    "faviconextension" => "png",
    "externalURLTitle" => true,
    "faviconmime" => "image/png",
    "proxyAllImages" => false,
    "externalURLFavicons" => true,
    "externalURLWarning" => true,
    "cookienotice" => true,
    "useDesktopNotification" => true,
    "emailnotifications" => true,
    "adminmail" => "admin@kuscheltickets.de",
    "oauth" => array(
        "google" => array(
            "use" => true,
            "clientid" => "453925826640-105pps9t6vasaau1gmikid5cic31gcsb.apps.googleusercontent.com",
            "clientsecret" => "Q9temhafR1cz4A0UwFmQ00FF"
        ),
        "github" => array(
            "use" => true,
            "clientid" => "cd61a9e93d02c1217cd3",
            "clientsecret" => "345c540ce3bb1445fec00ea3d12edddc5ac28063"
        ),
    ),
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
