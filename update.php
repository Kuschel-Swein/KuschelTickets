<?php
if(!file_exists("config.php")) {
    die("Du kannst KuschelTickets nicht aktualisieren, wenn noch keine Konfiguarationsdatei existiert. Nutze hierfür bitte den <a href='install.php'>Installer</a>.");
}
if(file_exists("data/INSTALLED")) {
    header("Location: index.php");
}
$success = "";
require("config.php");
define("UPDATERTOVERSION", "v2.1");
define("UPDATERFROMVERSION", "v2.0");
if($config['version'] !== UPDATERFROMVERSION) {
    die("Dieser Updater kann nur von der Version <b>".UPDATERFROMVERSION."</b> auf die Version <b>".UPDATERTOVERSION."</b> aktualisieren.");
}
if(isset($_POST['submit'])) {
    $pdo = $config['db'];

    $permissions = ['general.tickets.quote', 'admin.login.other', 'admin.bypass.login.other', 'general.notifications.view', 'general.notifications.settings', 'admin.acp.page.cleanup', 'admin.acp.page.errors', 'general.editor.templates'];
    foreach($pdo->query("SELECT * FROM kuscheltickets".KT_N."_groups WHERE groupID NOT 1") as $row) {
        foreach($permissions as $perm) {
            $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (?, ? , 0)");
            $stmt->execute([$row['groupID'], $perm]);
        }
    }
    foreach($permissions as $perm) {
        $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (1, ? , 1)");
        $stmt->execute([$perm]);
    }
    $pdo->query("UPDATE kuscheltickets".KT_N."_group_permissions SET `name`='mod.view.ticket.all' WHERE name = 'general.view.ticket.all'");  
    $pdo->query("DELETE FROM kuscheltickets".KT_N."_group_permissions WHERE name = 'general.view.pages'");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_ticket_categorys ADD `color` VARCHAR(535) NOT NULL AFTER `categoryName`;");
    $pdo->query("UPDATE kuscheltickets".KT_N."_ticket_categorys SET `color`='blue' WHERE 1;");
    $pdo->query("CREATE TABLE kuscheltickets".KT_N."_notifications (
        notificationID int NOT NULL AUTO_INCREMENT,
        linkIdentifier TEXT NOT NULL,
        content TEXT NOT NULL,
        userID int NOT NULL,
        PRIMARY KEY (notificationID),
        FOREIGN KEY (userID) REFERENCES kuscheltickets".KT_N."_accounts(userID) ON DELETE CASCADE
    );");
    $pdo->query("CREATE TABLE kuscheltickets".KT_N."_editortemplates (
        templateID int NOT NULL AUTO_INCREMENT,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        userID int,
        PRIMARY KEY (templateID),
        FOREIGN KEY (userID) REFERENCES kuscheltickets".KT_N."_accounts(userID) ON DELETE CASCADE
    );");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_editortemplates ADD `description` TEXT NOT NULL AFTER `content`;");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_notifications ADD `time` INT NOT NULL AFTER `userID`;");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_notifications ADD `done` INT(1) NOT NULL AFTER `time`;");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_tickets ADD `color` VARCHAR(255) NOT NULL AFTER `time`;");
    $pdo->query("UPDATE kuscheltickets".KT_N."_tickets SET `color`='blue' WHERE 1");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_accounts ADD `notificationsettings` TEXT NOT NULL AFTER `userGroup`;");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_pages ADD `type` INT(1) NOT NULL AFTER `login`;");
    $pdo->query("ALTER TABLE kuscheltickets".KT_N."_pages CHANGE `login` `groups` TEXT NOT NULL;");
    $pdo->query("UPDATE kuscheltickets".KT_N."_pages SET `groups`='[]' WHERE 1");
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
    '    "cookie" => "'.$config['cookie'].'",'.PHP_EOL.
    '    "seourls" => false,'.PHP_EOL.
    '    "faviconextension" => "png",'.PHP_EOL.
    '    "externalURLTitle" => true,'.PHP_EOL.
    '    "faviconmime" => "image/png",'.PHP_EOL.
    '    "proxyAllImages" => true,'.PHP_EOL.
    '    "externalURLFavicons" => true,'.PHP_EOL.
    '    "externalURLWarning" => true,'.PHP_EOL.
    '    "useDesktopNotification" => true,'.PHP_EOL.
    '    "emailnotifications" => true,'.PHP_EOL.
    '    "adminmail" => "'.$config['adminmail'].'",'.PHP_EOL.
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
    'define("KT_N", "'.KT_N.'");'.PHP_EOL.
    '');
    fclose($file);
    $success = "KuschelTickets wurde erfolgreich aktualisiert.";
    $f = fopen("data/INSTALLED", "w");
    fclose($f);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Schritt <?php echo STEP; ?> - Installation - KuschelTickets</title>
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
                <strong><?php echo $success; ?></strong>
                <p>Hier kannst du KuschelTickets aktualisieren, dieser Updater wird KuschelTickets auf die Version <b><?php echo UPDATERTOVERSION; ?></b> bringen.</p>
                <p>Nach dem start der Aktualisierung, kann es etwas dauern bis diese fertig ist.</p>
                <form action="update.php" method="post">
                    <button class="ui blue button" type="submit">Jetzt aktualisieren!</a> 
                </form>
            </div>
        </div>
    </body>
</html>