<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\Ticket;
use KuschelTickets\lib\dompdfAdapter;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\Exceptions\PageNotFoundException;
use KuschelTickets\lib\Link;

class ticketpdfPage extends Page {

    private $content = null;

    public function readParameters(Array $parameters) {
        global $config;

        if(!$config['pdfexport']) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }

        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.view.ticket.own") && !$user->hasPermission("mod.view.ticket.all")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $ticket = new Ticket($parameters['ticketpdf']);
        if(!$ticket->exists()) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }
        $creator = $ticket->getCreator();
        if(!$user->hasPermission("mod.view.ticket.all")) {
            if($user->userID !== $creator->userID) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
        }

        if(!$user->hasPermission("general.ticket.export.pdf")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        header("Content-type:application/pdf");
        header("Content-Disposition:inline;");
        $content = "";

        $content = $content.'
            <header>
                <center>
                    <small>'.$config['pagetitle'].' &mdash; Ticket #'.$ticket->ticketID.' &mdash; '.$ticket->getCategory().' &mdash; <a href="'.Link::get("ticket-".$ticket->ticketID).'">'.Link::get("ticket-".$ticket->ticketID).'</a></small>
                </center>
            </header>
        ';

        $content = $content."
        <article>
            <u>".$ticket->getCreator()->getGroup()->getGroupBadge()." ".$ticket->getCreator()->getUserName()." schrieb am ".date("d.m.Y", $ticket->getTime())." um ".date("H:i", $ticket->getTime()).":</u>
            <blockquote>
                ".$ticket->getContent()."
            </blockquote>
        </article>";

        foreach($ticket->getAnswers() as $answer) {
            if($answer['creator'] !== "system") {
                $content = $content."
                <article>
                    <u>".$answer['creator']->getGroup()->getGroupBadge()." ".$answer['creator']->getUserName()." schrieb am ".date("d.m.Y", $answer['time'])." um ".date("H:i", $answer['time']).":</u>
                    <blockquote>
                        ".$answer['content']."
                    </blockquote>
                </article>";
            } else {
                $content = $content."
                <article>
                <hr>
                <center>
                ".$answer['content']." &mdash; ".date("d.m.Y", $answer['time'])." um ".date("H:i", $answer['time'])."
                </center>
                <hr>
                </article>
                ";
            }
        }

        $content = '<html><head><title>'.$ticket->getTitle().'</title><meta charset="utf-8"><style>'.self::getCSS().'</style></head><body>'.$content.'</body></html>';
        $this->content = dompdfAdapter::create($content);
    }

    public function assign() {
        return array(
            "content" => $this->content
        );
    }

    public static function getCSS() {
        return "
            html {
                font-family: sans-serif;
                padding: 10px;
            }
            header {
                margin-bottom: 15px;
            }
            article {
                margin-bottom: 15px;
            }
            article blockquote {
                border-left: 5px solid #ccc;
                padding-left: 3px;
                padding-right: 3px;
                display: block;
                word-wrap: auto;
            }
            .ui.label {
                display: inline;
                vertical-align: baseline;
                background-color: #e8e8e8;
                background-image: none;
                padding: 1px;
                color: rgba(0,0,0,.6);
                text-transform: none;
                font-weight: 700;
                border: 0 solid transparent;
                border-radius: .28571429rem;
                -webkit-transition: background .1s ease;
                transition: background .1s ease;
            }
            .ui.red.label {
                background-color: #db2828!important;
                border-color: #db2828!important;
                color: #fff!important;
            }
            .ui.orange.label {
                background-color: #f2711c!important;
                border-color: #f2711c!important;
                color: #fff!important;
            }
            .ui.yellow.label {
                background-color: #fbbd08!important;
                border-color: #fbbd08!important;
                color: #fff!important;
            }
            .ui.olive.label {
                background-color: #b5cc18!important;
                border-color: #b5cc18!important;
                color: #fff!important;
            }
            .ui.green.label {
                background-color: #21ba45!important;
                border-color: #21ba45!important;
                color: #fff!important;
            }
            .ui.teal.label {
                background-color: #00b5ad!important;
                border-color: #00b5ad!important;
                color: #fff!important;
            }
            .ui.blue.label {
                background-color: #2185d0!important;
                border-color: #2185d0!important;
                color: #fff!important;
            }
            .ui.violet.label {
                background-color: #6435c9!important;
                border-color: #6435c9!important;
                color: #fff!important;
            }
            .ui.purple.label {
                background-color: #a333c8!important;
                border-color: #a333c8!important;
                color: #fff!important;
            }
            .ui.pink.label {
                background-color: #e03997!important;
                border-color: #e03997!important;
                color: #fff!important;
            }
            .ui.brown.label {
                background-color: #a5673f!important;
                border-color: #a5673f!important;
                color: #fff!important;
            }
            .ui.grey.label {
                background-color: #767676!important;
                border-color: #767676!important;
                color: #fff!important;
            }
            .ui.black.label {
                background-color: #1b1c1d!important;
                border-color: #1b1c1d!important;
                color: #fff!important;
            }
        ";
    }


}
?>