<?php
if(!file_exists("config.php")) {
    die("Du kannst KuschelTickets nicht aktualisieren, wenn noch keine Konfiguarationsdatei existiert. Nutze hierfür bitte den <a href='install.php'>Installer</a>.");
}
if(file_exists("data/INSTALLED")) {
    header("Location: index.php");
    die();
}
$success = "";
require "config.php";
define("UPDATERTOVERSION", "v2.4");
define("UPDATERFROMVERSION", "v2.3");
if($config['version'] !== UPDATERFROMVERSION) {
    die("Dieser Updater kann nur von der Version <b>".UPDATERFROMVERSION."</b> auf die Version <b>".UPDATERTOVERSION."</b> aktualisieren.");
}
if(isset($_POST['submit'])) {
    $pdo = $config['db'];

    $permissions = ['general.tickets.edit.own', 'mod.tickets.edit.all', 'mod.tickets.edithistory', 'general.account.signature', 'general.account.avatar', 'mod.tickets.edit.removenotice'];
    $stmt = $pdo->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE groupID NOT 1");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        foreach($permissions as $perm) {
            $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (?, ? , 0)");
            $stmt->execute([$row['groupID'], $perm]);
        }
    }
    foreach($permissions as $perm) {
        $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (1, ? , 1)");
        $stmt->execute([$perm]);
    }
    



    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_accounts CHANGE `twofactor` `twofactor` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '{\"use\":false,\"code\":\"\",\"backupcodes\":[]}';");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_accounts ADD `signature` TEXT NOT NULL AFTER `userGroup`;");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_accounts ADD `avatar` TEXT NOT NULL DEFAULT 'default.png' AFTER `signature`;");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_tickets ADD `customInputResponse` TEXT NOT NULL DEFAULT '' AFTER `content`;");
    $pdo->query("CREATE TABLE kuscheltickets".KT_N."_ticket_change (
        changeID int NOT NULL AUTO_INCREMENT,
        userID int NOT NULL,
        ticketID int NOT NULL,
        answerID int DEFAULT NULL,
        time int NOT NULL,
        newContent TEXT NOT NULL,
        oldContent TEXT NOT NULL,
        PRIMARY KEY (changeID),
        FOREIGN KEY (userID) REFERENCES kuscheltickets".KT_N."_accounts(userID) ON DELETE CASCADE,
        FOREIGN KEY (ticketID) REFERENCES kuscheltickets".KT_N."_tickets(ticketID) ON DELETE CASCADE,
        FOREIGN KEY (answerID) REFERENCES kuscheltickets".KT_N."_ticket_answers(answerID) ON DELETE CASCADE
    );");

    $count = count($config['recaptcha']['cases']);
    $recaptchacases = $config['recaptcha']['cases'];
    $recaptchacases_ready = "";
    for($i = 0; $i < $count; $i++) {
        if(isset($recaptchacases[$i + 1])) {
            $recaptchacases_ready = $recaptchacases_ready."'".$recaptchacases[$i]."',";
        } else {
            $recaptchacases_ready = $recaptchacases_ready."'".$recaptchacases[$i]."'";
        }
    }
    $file = fopen("config.php", "w");
    fwrite($file, '<?php' . PHP_EOL . '  /*' . PHP_EOL . '      Automatisch erstellte Config-Datei    ' . PHP_EOL . '      Erstellt am ' . date('d.m.Y  H:i:s') . '    ' . PHP_EOL . '  */'.PHP_EOL.
    '$database = array('.PHP_EOL.
    '    "database" => "'.$database['database'].'",'.PHP_EOL.
    '    "host" => "'.$database['host'].'",'.PHP_EOL.
    '    "port" => '.$database['port'].','.PHP_EOL.
    '    "user" => "'.$database['user'].'",'.PHP_EOL.
    '    "password" => "'.$database['password'].'"'.PHP_EOL.
    ');'.PHP_EOL.
    ''.PHP_EOL.
    '$config = array('.PHP_EOL.
    '    "version" => "'.UPDATERTOVERSION.'",'.PHP_EOL.
    '    "pagetitle" => "'.$config['pagetitle'].'",'.PHP_EOL.
    '    "databaseaccess" => $database,'.PHP_EOL.
    '    "db" => new PDO("mysql:host=".$database["host"].":".$database["port"].";dbname=".$database["database"], $database["user"], $database["password"]),'.PHP_EOL.
    '    "debugging" => array('.PHP_EOL.
    '        "templates" => false,'.PHP_EOL.
    '        "php" => false, // stellst du dies auf true, kann es zu Anzeigefehlern kommen'.PHP_EOL.
    '        "database" => false'.PHP_EOL.
    '    ),'.PHP_EOL.
    '    "equalfaq" => true,'.PHP_EOL.
    '    "avatarextensions" => ["png", "jpg"],'.PHP_EOL.
    '    "gravatar" => true,'.PHP_EOL.
    '    "avatarsize" => 10000000,'.PHP_EOL.
    '    "cookie" => "'.$config['cookie'].'",'.PHP_EOL.
    '    "cookienotice" => '.$config['cookienotice'].','.PHP_EOL.
    '    "seourls" => '.$config['seourls'].','.PHP_EOL.
    '    "ticketRating" => '.$config['ticketRating'].','.PHP_EOL.
    '    "ticketRatingIcon" => "'.$config['ticketRatingIcon'].'",'.PHP_EOL.
    '    "pdfexport" => '.$config['pdfexport'].','.PHP_EOL.
    '    "registrationEnabled" => '.$config['registrationEnabled'].','.PHP_EOL.
    '    "faviconextension" => "'.$config['faviconextension'].'",'.PHP_EOL.
    '    "externalURLTitle" => '.$config['externalURLTitle'].','.PHP_EOL.
    '    "faviconmime" => "'.$config['faviconmime'].'",'.PHP_EOL.
    '    "proxyAllImages" => '.$config['proxyAllImages'].','.PHP_EOL.
    '    "externalURLFavicons" => '.$config['externalURLFavicons'].','.PHP_EOL.
    '    "externalURLWarning" => '.$config['externalURLWarning'].','.PHP_EOL.
    '    "useDesktopNotification" => '.$config['useDesktopNotification'].','.PHP_EOL.
    '    "emailnotifications" => '.$config['emailnotifications'].','.PHP_EOL.
    '    "adminmail" => "'.$config['adminmail'].'",'.PHP_EOL.
    '    "oauth" => array('.PHP_EOL.
    '        "google" => array('.PHP_EOL.
    '            "use" => '.$config['oauth']['google']['use'].','.PHP_EOL.
    '            "clientid" => "'.$config['oauth']['google']['clientid'].'",'.PHP_EOL.
    '            "clientsecret" => "'.$config['oauth']['google']['clientsecret'].'"'.PHP_EOL.
    '        ),'.PHP_EOL.
    '        "github" => array('.PHP_EOL.
    '            "use" => '.$config['oauth']['github']['clientsecret'].','.PHP_EOL.
    '            "clientid" => "'.$config['oauth']['github']['clientsecret'].'",'.PHP_EOL.
    '            "clientsecret" => "'.$config['oauth']['github']['clientsecret'].'"'.PHP_EOL.
    '        ),'.PHP_EOL.
    '    ),'.PHP_EOL.
    '    "mail" => array('.PHP_EOL.
    '        "host" => "'.$config['mail']['host'].'",'.PHP_EOL.
    '        "auth" => '.$config['mail']['auth'].','.PHP_EOL.
    '        "port" => '.$config['mail']['port'].','.PHP_EOL.
    '        "username" => "'.$config['mail']['username'].'",'.PHP_EOL.
    '        "password" => "'.$config['mail']['password'].'",'.PHP_EOL.
    '        "from" => "'.$config['mail']['from'].'"'.PHP_EOL.
    '    ),'.PHP_EOL.
    '    "state_colors" => array('.PHP_EOL.
    '        "closed" => "'.$config['state_colors']['closed'].'",'.PHP_EOL.
    '        "open" => "'.$config['state_colors']['open'].'",'.PHP_EOL.
    '        "done" => "'.$config['state_colors']['done'].'"'.PHP_EOL.
    '    ),'.PHP_EOL.
    '    "recaptcha" => array('.PHP_EOL.
    '        "public" => "'.$config['recaptcha']['public'].'",'.PHP_EOL.
    '        "secret" => "'.$config['recaptcha']['secret'].'",'.PHP_EOL.
    '        "use" => '.$config['recaptcha']['use'].','.PHP_EOL.
    '        "version" => '.$config['recaptcha']['version'].','.PHP_EOL.
    '        "cases" => ['.$recaptchacases_ready.']'.PHP_EOL.
    '    )'.PHP_EOL.
    ');'.PHP_EOL.
    ''.PHP_EOL.
    'if(!defined("KT_N")) define("KT_N", "'.KT_N.'");'.PHP_EOL.
    '');
    fclose($file);
    $success = "KuschelTickets wurde erfolgreich aktualisiert. Sieh doch mal in den Einstellungen des ACPs vorbei, es gab einige Änderungen.";
    $f = fopen("data/INSTALLED", "w");
    fclose($f);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aktualisierung - KuschelTickets</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap">
        <link rel="stylesheet" href="assets/semantic.min.css"> 
        <link rel="stylesheet" href="assets/master.css">    
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="assets/semantic.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="ui grid container">
            <div class="ui sixteen wide column segment">
                <h1>KuschelTickets Aktualisierung</h1>
                <?php if($success !== "") { ?><div class="ui success message"><?php echo $success; ?></div><?php } ?>
                <p>Hier kannst du KuschelTickets aktualisieren, dieser Updater wird KuschelTickets auf die Version <b><?php echo UPDATERTOVERSION; ?></b> bringen.</p>
                <p>Nach dem start der Aktualisierung, kann es etwas dauern bis diese fertig ist.</p>
                <form action="update.php" method="post">
                    <button class="ui blue button" name="submit" type="submit">Jetzt aktualisieren!</a> 
                </form>
            </div>
        </div>
    </body>
</html>