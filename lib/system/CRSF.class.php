<?php
namespace KuschelTickets\lib\system;

class CRSF {

    public static function validate(String $CRSF) {
        if(!isset($_SESSION['CRSF'])) {
            CRSF::update();
            return false;
        }
        if($_SESSION['CRSF'] == $CRSF) {
            CRSF::update();
            return true;
        } else {
            CRSF::update();
            return false;
        }
        
    }

    public static function update() {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 200; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $_SESSION['CRSF'] = $randomString;
    }

    public static function get() {
        if(!isset($_SESSION['CRSF'])) {
            CRSF::update();
        }
        return $_SESSION['CRSF'];
    }
}