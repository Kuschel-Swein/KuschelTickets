<?php
use KuschelTickets\lib\Page;
use KuschelTickets\lib\system\Ticket;
use KuschelTickets\lib\system\UserUtils;
use KuschelTickets\lib\system\User;
use KuschelTickets\lib\system\CRSF;
use KuschelTickets\lib\Exceptions\AccessDeniedException;
use KuschelTickets\lib\Exceptions\PageNotFoundException;
use KuschelTickets\lib\recaptcha;

class ticketPage extends Page {

    private $ticket;
    private $errors;
    private $success = false;

    public function readParameters(Array $parameters) {
        global $config;

        if(!UserUtils::isLoggedIn()) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $user = new User(UserUtils::getUserID());
        if(!$user->hasPermission("general.view.ticket.own") && !$user->hasPermission("general.view.ticket.all")) {
            throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
        }
        $ticket = new Ticket($parameters['ticket']);
        if(!$ticket->exists()) {
            throw new PageNotFoundException("Diese Seite wurde nicht gefunden.");
        }
        $creator = $ticket->getCreator();
        if(!$user->hasPermission("general.view.ticket.all")) {
            if($user->userID !== $creator->userID) {
                throw new AccessDeniedException("Du hast nicht die erforderliche Berechtigung diese Seite zu sehen.");
            }
        }
        $this->ticket = $ticket;

        $this->errors = array(
            "text" => false,
            "token" => false
        );

        if(isset($parameters['submit'])) {
            if($user->hasPermission("general.tickets.answer")) {
                if($ticket->getState() == 1) {
                    if(recaptcha::validate("ticketanswer")) {
                        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
                            if(CRSF::validate($parameters['CRSF'])) {
                                if(isset($parameters['text']) && !empty($parameters['text'])) {
                                    require("lib/HTMLPurifier/HTMLPurifier.auto.php");
                                        $secure = HTMLPurifier_Config::createDefault();
                                        $secure->set('HTML.Doctype', 'XHTML 1.0 Transitional');
                                        $secure->set('URI.DisableExternalResources', false);
                                        $secure->set('URI.DisableResources', false);
                                        $secure->set('HTML.Allowed', 'u,a,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
                                        $secure->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
                                        $secure->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
                                        $secure->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
                                        $secure->set('HTML.SafeIframe', true);
                                        $secure->set('Core.EscapeInvalidTags', true);
                                        $secure->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
                                        $purifier = new HTMLPurifier($secure);
                                        $text = $purifier->purify($parameters['text']);
                                        if(!empty($text)) {
                                            $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_ticket_answers(`ticketID`, `creator`, `content`, `time`) VALUES (?, ?, ?, ?)");
                                            $time = time();
                                            $stmt->execute([$parameters['ticket'], $user->userID, $text, $time]);
                                            $this->success = true;
                                        } else {
                                            $this->errors['text'] = "Bitte gib einen Text an.";
                                        }
                                } else {
                                    $this->errors['text'] = "Bitte gib einen Text an.";
                                }
                            } else {
                                $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                            }
                        } else {
                            $this->errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
                        }
                    } else {
                        $this->errors['token'] = "Du wurdest von reCaptcha als Bot erkannt.";
                    }
                    
                }
            }
        }



    }

    public function assign() {
        return array(
            "ticket" => $this->ticket,
            "errors" => $this->errors,
            "success" => $this->success,
            "recaptcha" => recaptcha::build("ticketanswer")
        );
    }


}
?>