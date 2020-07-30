<?php
namespace kt\system\mailer;

use kt\system\exception\MailerExeption;

class Mailer {

    private $to;
    private $subject;
    private $name;
    private $message;

    public function __construct(String $to, String $subject, String $name = null) {
        $this->to = $to;
        $this->subject = $subject;
        if($name == null) {
            $this->name = $subject;
        } else {
            $this->name = $name;
        }
        
    }

    public function setMessage(String $message) {
        $this->message = $message;
    }

    public function send() {
        global $config;
        $mail = new PHPMailer();
        try {                    
            $mail->isSMTP(); 
            $mail->CharSet = "UTF-8";                                          
            $mail->Host = $config['mail']['host'];                    
            $mail->SMTPAuth = $config['mail']['auth'];    
            if($config['mail']['auth']) {                               
                $mail->Username = $config['mail']['username'];                    
                $mail->Password = $config['mail']['password'];   
            }                            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port = $config['mail']['port'];                                 
            $mail->setFrom($config['mail']['from'], $config['pagetitle']);
            $mail->addAddress($this->to, $this->name);  
            $mail->isHTML(true);                               
            $mail->Subject = $this->subject;
            $mail->Body = "<div style='font-family: Arial, sans-serif'>".$this->message."</div>";
            $mail->send();
        } catch (MailerExeption $e) {
            echo "Mailing Exception: ".$e;
        }
    }
}

?>