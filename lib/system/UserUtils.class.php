<?php
namespace KuschelTickets\lib\system;
use KuschelTickets\lib\Mailer;
use KuschelTickets\lib\Utils;

class UserUtils {
    public static function loginAs(User $user, String $hash) {
        $_SESSION['userID'] = $user->userID;
        $_SESSION['hash'] = $hash;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['userID']);
    }

    public static function getUserID() {
        return $_SESSION['userID'];
    }

    public static function exists(String $content, String $what) {
        global $config;

        if($what == "email") {
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE email = ?");
            $stmt->execute([$content]);
            $row = $stmt->fetch();
            return $row;
        } else if($what == "username") {
            $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE username = ?");
            $stmt->execute([$content]);
            $row = $stmt->fetch();
            return $row;
        } else {
            throw new IllegalArgumentException("you can either enter email or username for the what parameter");
        }
    }

    public static function generateToken() {
        global $config;

        $characters = "0123456789";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < 60; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $stmt = $config['db']->prepare("SELECT * FROM kuscheltickets".KT_N."_accounts WHERE token = ?");
        $stmt->execute([$randomString]);
        $row = $stmt->fetch();
        if($row) {
            return UserUtils::generateToken();
        }
        return $randomString;
    }

    public static function logOut() {
        session_destroy();
        Utils::redirect("index.php?login");
    }

    public static function create(String $username, String $email, String $password) {
        global $config;

        $username = strip_tags($username);
        $email = strip_tags($email);
        $password = password_hash($password, PASSWORD_BCRYPT);
        $token = UserUtils::generateToken();
        $stmt = $config['db']->prepare("INSERT INTO kuscheltickets".KT_N."_accounts(`username`, `password`, `email`, `token`, `userGroup`, `banned`, `password_reset`) VALUES (?, ?, ?, ?, 3, 0, 0)");
        $stmt->execute([$username, $password, $email, $token]);
        $mainurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        $mail = new Mailer($email, $config['pagetitle']." - Registrierung", $username);
        $message = "<p>Hey ".$username.",</p>
        <p>Vielen Dank für die Registrierung bei ".$config['pagetitle'].". Um deinen Account zu aktivieren folge bitte dem untenstehenden Link.</p>
        <p><b>Link:</b> <a href='".$mainurl."?activate/token-".$token."'>".$mainurl."?activate/token-".$token."</a></p>
        <p></p>
        <p><hr></p>
        <p>Mit freundlichen Grüßen,</p>
        <p>dein ".$config['pagetitle']." Team</p>";
        $mail->setMessage($message);
        $mail->send();

    }
}