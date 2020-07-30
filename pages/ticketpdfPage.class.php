<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\KuschelTickets;
use KuschelTickets\lib\dompdfAdapter;
use KuschelTickets\lib\exception\AccessDeniedException;
use KuschelTickets\lib\exception\PageNotFoundException;
use KuschelTickets\lib\Link;
use KuschelTickets\lib\data\ticket\Ticket;

class ticketpdfPage extends Page {

    private $content = null;

    public function readParameters(Array $parameters) {
        global $config;

        if(!$config['pdfexport']) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }

        if(KuschelTickets::getUser() == null) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        if(!KuschelTickets::getUser()->hasPermission("general.view.ticket.own") && !KuschelTickets::getUser()->hasPermission("mod.view.ticket.all")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $ticket = new Ticket($parameters['ticketpdf']);
        if(!$ticket->ticketID) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }
        $creator = $ticket->getCreator();
        if(!KuschelTickets::getUser()->hasPermission("mod.view.ticket.all")) {
            if(KuschelTickets::getUser()->userID !== $creator->userID) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
        }

        if(!KuschelTickets::getUser()->hasPermission("general.ticket.export.pdf")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }

        header("Content-type:application/pdf");
        header("Content-Disposition:inline;");
        $content = "";
        $rating = "";
        if($config['ticketRating']) {
            if($ticket->rating != null) {
                $rating = "<br>Bewertung: ".$ticket->rating."/5";
            } else {
                $rating = "<br><i>noch nicht bewertet</i>";
            }
        }

        $content = $content.'
            <header>
                <center>
                    <small>'.$config['pagetitle'].' &mdash; Ticket #'.$ticket->ticketID.' &mdash; '.$ticket->category.' &mdash; <a href="'.Link::get("ticket-".$ticket->ticketID).'">'.Link::get("ticket-".$ticket->ticketID).'</a></small>
                    <small>'.$rating.'</small>
                </center>
            </header>
        ';

        $content = $content."
        <article>
            <u>".$ticket->getCreator()->getGroup()->getGroupBadge()." ".$ticket->getCreator()->username." schrieb am ".date("d.m.Y", $ticket->time)." um ".date("H:i", $ticket->time)." Uhr:</u>
            <blockquote>
                ".$ticket->content."
            </blockquote>
        </article>";

        foreach($ticket->getAnswers() as $answer) {
            if($answer->creator !== 0) {
                $content = $content."
                <article>
                    <u>".$answer->getCreator()->getGroup()->getGroupBadge()." ".$answer->getCreator()->username." schrieb am ".date("d.m.Y", $answer->time)." um ".date("H:i", $answer->time)." Uhr:</u>
                    <blockquote>
                        ".$answer->content."
                    </blockquote>
                </article>";
            } else {
                $content = $content."
                <article>
                <hr>
                <center>
                ".$answer->content." &mdash; ".date("d.m.Y", $answer->time)." um ".date("H:i", $answer->time)." Uhr
                </center>
                <hr>
                </article>
                ";
            }
        }

        $content = '<html><head><title>'.$ticket->title.'</title><meta charset="utf-8"><style>'.self::getCSS().'</style></head><body>'.$content.'</body></html>';
        $this->content = dompdfAdapter::create($content);
    }

    public function assign() {
        return array(
            "content" => $this->content
        );
    }

    public static function getCSS() {
        return file_get_contents("./assets/pdf.css");
    }


}
?>