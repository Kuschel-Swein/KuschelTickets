<?php
/**
 * in this file are all SYSTEM Pages, that are needed for the navigation
 */
define("DATA", array(
    "topnavigation" => [
        array(
            "text" => "Startseite",
            "identifier" => "Index",
            "permission" => "general.view.dashboard"
        ),
        array(
            "text" => "Meine Tickets",
            "identifier" => "mytickets",
            "permission" => "general.view.tickets.self"
        ),
        array(
            "text" => "Tickets",
            "identifier" => "tickets",
            "permission" => "mod.view.tickets.list"
        ),
        array(
            "text" => "FAQ",
            "identifier" => "faq",
            "permission" => "general.view.faq"
        ),
        array(
            "text" => "Account verwalten",
            "identifier" => "accountmanagement",
            "permission" => "general.account.manage"
        ),
        array(
            "text" => "Editorvorlagen",
            "identifier" => "editortemplates",
            "permission" => "general.editor.templates"
        ),
        array(
            "text" => "SupportChat",
            "identifier" => "supportchat",
            "permission" => "general.supportchat.view"
        ),
        array(
            "text" => "Administration",
            "identifier" => "admin",
            "permission" => "admin.acp.use"
        )
    ]
));