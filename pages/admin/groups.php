<?php
use KuschelTickets\lib\Utils;
use KuschelTickets\lib\system\Group;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\PageNotFoundException;

/**
 * 
 * Group Admin Page Handler
 * 
 */

$permissions = [
    array(
        "name" => "mod_view_tickets",
        "display" => "alle Tickets sehen"
    ),
    array(
        "name" => "general_login",
        "display" => "einloggen"
    ),
    array(
        "name" => "general_view_tickets_self",
        "display" => "eigene Tickets sehen"
    ),
    array(
        "name" => "general_tickets_add",
        "display" => "Tickets erstellen"
    ),
    array(
        "name" => "general_view_ticket_own",
        "display" => "eigenes Ticket sehen"
    ),
    array(
        "name" => "general_view_ticket_all",
        "display" => "alle Tickets sehen"
    ),
    array(
        "name" => "general_tickets_answer",
        "display" => "auf Ticket antworten"
    ),
    array(
        "name" => "general_tickets_deletemessage_own",
        "display" => "eigene Nachrichten löschen"
    ),
    array(
        "name" => "general_tickets_deletemessage_other",
        "display" => "alle Nachrichten löschen"
    ),
    array(
        "name" => "mod_tickets_close",
        "display" => "alle Tickets schließen"
    ),
    array(
        "name" => "general_tickets_close_own",
        "display" => "eigene Tickets schließen"
    ),
    array(
        "name" => "mod_tickets_done",
        "display" => "alle Tickets als abgeschlossen markieren"
    ),
    array(
        "name" => "general_tickets_done_own",
        "display" => "eigene Tickets als abgeschlossen markieren"
    ),
    array(
        "name" => "mod_tickets_reopen",
        "display" => "alle Tickets erneut öffnen"
    ),
    array(
        "name" => "general_tickets_reopen_own",
        "display" => "eigene Tickets erneut öffnen"
    ),
    array(
        "name" => "mod_tickets_delete",
        "display" => "alle Tickets löschen"
    ),
    array(
        "name" => "general_tickets_delete_own",
        "display" => "eigene Tickets löschen"
    ),
    array(
        "name" => "mod_tickets_answers_delete",
        "display" => "alle Antworten löschen"
    ),
    array(
        "name" => "general_tickets_answers_delete_own",
        "display" => "eigene Antworten löschen"
    ),
    array(
        "name" => "mod_view_tickets_list",
        "display" => "Liste aller Tickets sehen"
    ),
    array(
        "name" => "general_view_dashboard",
        "display" => "Dashboad sehen"
    ),
    array(
        "name" => "general_view_faq",
        "display" => "FAQ sehen"
    ),
    array(
        "name" => "general_view_pages",
        "display" => "eigene Seiten mit Zugangsbeschränkung sehen"
    ),
    array(
        "name" => "general_account_manage",
        "display" => "Account verwalten"
    ),
    array(
        "name" => "admin_acp_use",
        "display" => "ACP verwenden"
    ),
    array(
        "name" => "admin_acp_page_dashboard",
        "display" => "ACP Dashboard verwenden"
    ),
    array(
        "name" => "admin_acp_page_faq",
        "display" => "ACP FAQ verwalten"
    ),
    array(
        "name" => "admin_acp_page_faqcategories",
        "display" => "ACP FAQ Kategorien verwalten"
    ),
    array(
        "name" => "admin_acp_page_pages",
        "display" => "ACP Seiten verwalten"
    ),
    array(
        "name" => "admin_acp_page_settings",
        "display" => "ACP Einstellungen verwalten"
    ),
    array(
        "name" => "admin_acp_page_accounts",
        "display" => "ACP Accounts verwalten"
    ),
    array(
        "name" => "admin_bypass_bannable",
        "display" => "nicht sperrbar sein"
    ),
    array(
        "name" => "admin_bypass_delete",
        "display" => "nicht löschbar sein"
    ),
    array(
        "name" => "admin_acp_page_groups",
        "display" => "ACP Gruppen verwalten"
    ),
    array(
        "name" => "admin_acp_page_ticketcategories",
        "display" => "ACP Ticketkategorien verwalten"
    )
];

$colors = ['red','orange','yellow','olive','green','teal','blue','violet','purple','pink','brown','grey','black'];

if(isset($parameters['add'])) {
    $subpage = "add";

    $errors = array(
        "text" => false,
        "token" => false,
        "badge" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    if(isset($parameters['badge']) && !empty($parameters['badge'])) {
                        if(in_array($parameters['badge'], $colors)) {
                            $text = strip_tags($parameters['text']);
                            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE name = ?");
                            $stmt->execute([$text]);
                            $row = $stmt->fetch();
                            if($row) {
                                $errors['text'] = "Dieser Name ist bereits vergeben.";
                            } else {
                                $badge = strip_tags($parameters['badge']);
                                $badge = '<div class="ui '.$badge.' label groupBadge">%NAME%</div>';
                                $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_groups(`name`,`badge`,`system`) VALUES (?,?,0)");
                                $stmt->execute([$text, $badge]);
                                $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE name = ?");
                                $stmt->execute([$text]);
                                $row = $stmt->fetch();
                                $groupID = $row['groupID'];
                                foreach($permissions as $permission) {
                                    $name = $permission['name'];
                                    $name_db = str_replace("_", ".", $name);
                                    $value = "0";
                                    if(isset($parameters[$name])) {
                                        $value = "1";
                                    }
                                    $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (?, ?, ?)");
                                    $stmt->execute([$groupID, $name_db, $value]);
                                }
                                $success = "Diese Gruppe wurde erfolgreich hinzugefügt.";
                            }
                        } else {
                            $errors['badge'] = "Bitte wähle einen validen Badge.";
                        }
                    } else {
                        $errors['badge'] = "Bitte wähle einen Badge.";
                    }
                } else {
                    $errors['text'] = "Bitte gib einen Namen an.";
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "permissions" => $permissions,
        "colors" => $colors
    );

} else if(isset($parameters['edit'])) {
    $subpage = "edit";
    if(empty($parameters['edit'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }
    $group = new Group($parameters['edit']);
    if(!$group->exists()) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }


    $errors = array(
        "text" => false,
        "token" => false,
        "badge" => false
    );

    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['text']) && !empty($parameters['text'])) {
                    if(isset($parameters['badge']) && !empty($parameters['badge'])) {
                        if(in_array($parameters['badge'], $colors)) {
                            $text = strip_tags($parameters['text']);
                            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups WHERE name = ?");
                            $stmt->execute([$text]);
                            $row = $stmt->fetch();
                            if($row && $row['name'] !== $group->getGroupName()) {
                                $errors['text'] = "Dieser Name ist bereits vergeben.";
                            } else {
                                $badge = strip_tags($parameters['badge']);
                                $badge = '<div class="ui '.$badge.' label groupBadge">%NAME%</div>';
                                $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_groups SET `name`= ?, `badge`=? WHERE groupID = ?");
                                $stmt->execute([$text, $badge, $group->groupID]);
                                if($group->groupID !== 1) {
                                    $groupID = $group->groupID;
                                    foreach($permissions as $permission) {
                                        $name = $permission['name'];
                                        $name_db = str_replace("_", ".", $name);
                                        $value = "0";
                                        if(isset($parameters[$name])) {
                                            $value = "1";
                                        }
                                        $stmt = $config['db']->prepare("UPDATE kuscheltickets".KT_N."_group_permissions SET `value`= ? WHERE groupID = ? AND name = ?");
                                        $stmt->execute([$value, $groupID, $name_db]);
                                    }
                                }
                                $success = "Diese Gruppe wurde erfolgreich gespeichert.";
                            }
                        } else {
                            $errors['badge'] = "Bitte wähle einen validen Badge.";
                        }
                    } else {
                        $errors['badge'] = "Bitte wähle einen Badge.";
                    }
                } else {
                    $errors['text'] = "Bitte gib einen Namen an.";
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }

    $gpermissions = array();
    foreach($group->getPermissions() as $permission) {
        $name = $permission['name'];
        $name = str_replace(".", "_", $name);
        $gpermissions[$name] = $permission['value'];
    }

    $badge = $group->getUnformattedBadge();
    $badge = str_replace('<div class="ui ', '', $badge);
    $badge = str_replace(' label groupBadge">%NAME%</div>', '', $badge);

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "permissions" => $permissions,
        "colors" => $colors,
        "editgroup" => $group,
        "gpermissions" => $gpermissions,
        "badge" => $badge
    );
} else {
    $subpage = "index";

    $groups = [];
    $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_groups");
    $stmt->execute();
    while($row = $stmt->fetch()) {
        array_push($groups, new Group($row['groupID']));
    }

    $site = array(
        "groups" => $groups,
        "site" => $subpage
    );
}




