<?php
use kt\system\CRSF;
use kt\system\KuschelTickets;

$errors = array(
    "token" => false,
    "sure" => false
);
$success = false;

if(isset($parameters['submit'])) {
    if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
        if(CRSF::validate($parameters['CRSF'])) {
            if(isset($parameters['sure'])) {
                if(isset($parameters['notifications'])) {
                    $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_notifications WHERE done = 1");
                    $stmt->execute();
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['tickets'])) {
                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                        $statement = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
                        $statement->execute([$row['ticketID']]);
                    }
                    $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
                    $stmt->execute();
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['banned'])) {
                    $stmt = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_accounts WHERE banned = 1");
                    $stmt->execute();
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['supportchat'])) {
                    $stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat");
                    $stmt->execute();
                    while($row = $stmt->fetch()) {
                        if($row['state'] == 2 || $row['time'] > strtotime("-24hours")) {
                            $statement = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_supportchat_messages WHERE chatID = ?");
                            $statement->execute([$row['chatID']]);
                            $statement = KuschelTickets::getDB()->prepare("DELETE FROM kuscheltickets".KT_N."_supportchat WHERE chatID = ?");
                            $statement->execute([$row['chatID']]);
                        }
                    }
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['errorlogs'])) {
                    $errorfiles = glob("./data/logs/*.txt");
                    foreach($errorfiles as $file) {
                        unlink($file);
                    }
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
                if(isset($parameters['templatescompiled'])) {
                    $errorfiles = glob("./data/templates_compiled/*.php");
                    foreach($errorfiles as $file) {
                        unlink($file);
                    }
                    $success = "Die gewählten Einträge wurden erfolgreich gelöscht.";
                }
            } else {
                $errors['sure'] = "Bitte bestätige die Löschung der Daten.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    } else {
        $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
    }
}



$stmt = KuschelTickets::getDB()->query('SHOW TABLE STATUS');
$dbsize = $stmt->fetch(PDO::FETCH_ASSOC)["Data_length"];
$dbsize = round($dbsize/(1024 * 1024), 2);

$stmt = KuschelTickets::getDB()->prepare("SELECT COUNT(*) AS readnotifications FROM kuscheltickets".KT_N."_notifications WHERE done = 1");
$stmt->execute();
$row = $stmt->fetch();
$readnotifications = $row['readnotifications'];

$stmt = KuschelTickets::getDB()->prepare("SELECT COUNT(*) AS closetickets FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
$stmt->execute();
$row = $stmt->fetch();
$closetickets = $row['closetickets'];

$stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_tickets WHERE NOT state = 1");
$stmt->execute();
$closeanswers = 0;
while($row = $stmt->fetch()) {
    $stmt = KuschelTickets::getDB()->prepare("SELECT COUNT(*) AS closeanswers FROM kuscheltickets".KT_N."_ticket_answers WHERE ticketID = ?");
    $stmt->execute([$row['ticketID']]);
    $r = $stmt->fetch();
    $closeanswers = $closetickets + $r['closeanswers'];
}

$stmt = KuschelTickets::getDB()->prepare("SELECT COUNT(*) AS bannedusers FROM kuscheltickets".KT_N."_accounts WHERE banned = 1");
$stmt->execute();
$row = $stmt->fetch();
$bannedusers = $row['bannedusers'];

$supportchats = 0;
$stmt = KuschelTickets::getDB()->prepare("SELECT * FROM kuscheltickets".KT_N."_supportchat");
$stmt->execute();
while($row = $stmt->fetch()) {
    if($row['state'] == 2 || $row['time'] > strtotime("-24hours")) {
        $supportchats++;
        $statement = KuschelTickets::getDB()->prepare("SELECT COUNT(*) AS trashAnswers FROM kuscheltickets".KT_N."_supportchat_messages WHERE chatID = ?");
        $statement->execute([$row['chatID']]);
        $messages = 0;
        if(isset($row['trashAnswers'])) {
            $messages = (int) $row['trashAnswers'];
        }
        $supportchats = $supportchats + $messages;
    }
}

$errorlogs = glob("./data/logs/*.txt");
$errorlogs = count($errorlogs);

$templatescompiled = glob("./data/templates_compiled/*.php");
$templatescompiled = count($templatescompiled);

$site = array(
    "dbsize" => $dbsize,
    "errors" => $errors,
    "success" => $success,
    "readnotifications" => $readnotifications,
    "closetickets" => $closetickets,
    "closeanswers" => $closeanswers,
    "bannedusers" => $bannedusers,
    "errorlogs" => $errorlogs,
    "supportchats" => $supportchats,
    "templatescompiled" => $templatescompiled
);
