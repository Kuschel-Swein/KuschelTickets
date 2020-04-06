<?php
define("DATA", array(
    "topnavigation" => [
        array(
            "href" => "",
            "text" => "Startseite",
            "right" => false,
            "identifier" => "Index",
            "permission" => "general.view.dashboard"
        ),
        array(
            "href" => "mytickets",
            "text" => "Meine Tickets",
            "right" => false,
            "identifier" => "mytickets",
            "permission" => "general.view.tickets.self"
        ),
        array(
            "href" => "tickets",
            "text" => "Tickets",
            "right" => false,
            "identifier" => "tickets",
            "permission" => "mod.view.tickets.list"
        ),
        array(
            "href" => "faq",
            "text" => "FAQ",
            "right" => false,
            "identifier" => "faq",
            "permission" => "general.view.faq"
        ),
        array(
            "href" => "accountmanagement",
            "text" => "Account verwalten",
            "right" => false,
            "identifier" => "accountmanagement",
            "permission" => "general.account.manage"
        ),
        array(
            "href" => "editortemplates",
            "text" => "Editorvorlagen",
            "right" => false,
            "identifier" => "editortemplates",
            "permission" => "general.editor.templates"
        ),
        array(
            "href" => "admin",
            "text" => "Administration",
            "right" => false,
            "identifier" => "admin",
            "permission" => "admin.acp.use"
        )
    ]
));