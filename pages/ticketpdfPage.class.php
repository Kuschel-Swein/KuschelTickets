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
        $rating = "";
        if($config['ticketRating']) {
            if($ticket->isRated()) {
                $rating = "<br>Bewertung: ".$ticket->getRating()."/5";
            } else {
                $rating = "<br><i>noch nicht bewertet</i>";
            }
        }

        $content = $content.'
            <header>
                <center>
                    <small>'.$config['pagetitle'].' &mdash; Ticket #'.$ticket->ticketID.' &mdash; '.$ticket->getCategory().' &mdash; <a href="'.Link::get("ticket-".$ticket->ticketID).'">'.Link::get("ticket-".$ticket->ticketID).'</a></small>
                    <small>'.$rating.'</small>
                </center>
            </header>
        ';

        $content = $content."
        <article>
            <u>".$ticket->getCreator()->getGroup()->getGroupBadge()." ".$ticket->getCreator()->getUserName()." schrieb am ".date("d.m.Y", $ticket->getTime())." um ".date("H:i", $ticket->getTime())." Uhr:</u>
            <blockquote>
                ".$ticket->getContent()."
            </blockquote>
        </article>";

        foreach($ticket->getAnswers() as $answer) {
            if($answer['creator'] !== "system") {
                $content = $content."
                <article>
                    <u>".$answer['creator']->getGroup()->getGroupBadge()." ".$answer['creator']->getUserName()." schrieb am ".date("d.m.Y", $answer['time'])." um ".date("H:i", $answer['time'])." Uhr:</u>
                    <blockquote>
                        ".$answer['content']."
                    </blockquote>
                </article>";
            } else {
                $content = $content."
                <article>
                <hr>
                <center>
                ".$answer['content']." &mdash; ".date("d.m.Y", $answer['time'])." um ".date("H:i", $answer['time'])." Uhr
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
        return file_get_contents("./assets/pdf.css");
    }


}
?>