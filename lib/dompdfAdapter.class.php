<?php
namespace KuschelTickets\lib;

class dompdfAdapter {

    public static function create(String $content) {
        require_once(dompdfAdapter::getPath());
        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsFontSubsettingEnabled(true);
        $dompdf->setOptions($options);
        $dompdf->loadHTML($content);
        $dompdf->render();
        return $dompdf->output();
    }

    public static function getPath() {
        return "lib/dompdf/autoload.inc.php";
    }
}