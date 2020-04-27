<?php
/**
 * 
 * KuschelTickets - Anforderungstest
 * 
 */
error_reporting(0);

if(file_exists("config.php")) {
    header("Location: index.php");
    die("Weiterleitung auf die <a href='index.php'>index.php</a> Seite.");
}

$error = 0;
function resultVersion() {
    global $error;

    $result = (int) phpversion();
    $class = "error";
    $error++;
    if($result == 7) {
        $class = "success";
        $error--;
    }
    return '<div class="result '.$class.'">PHP 7 (PHP v'.phpversion().')</div>';
}
function resultMemory() {
    global $error;

    $result = (int) ini_get('memory_limit');
    $class = "error";
    $error++;
    if($result > 128) {
        $class = "success";
        $error--;
    }
    return '<div class="result '.$class.'">Arbeitsspeicherlimit > 128MB ('.$result.' MB)</div>';
}
function resultDisk() {
    global $error;

    $result = disk_free_space("./");
    $class = "error";
    $error++;
    if($result > 2e+7) {
        $class = "success";
        $error--;
    }
    if($result == false) {
        return '<div class="result warning">freier Speicher > 20MB (konnte nicht überprüft werden)</div>';
    }
    return '<div class="result '.$class.'">freier Speicher > 20MB ('.formatBytes($result).')</div>';
}
function resultExtension(String $extension) {
    global $error;

    $result = extension_loaded($extension);
    $class = "error";
    $error++;
    if($result) {
        $class = "success";
        $error--;
    }
    return '<div class="result '.$class.'">Erweiterung '.$extension.'</div>';
}
function formatBytes(float $bytes) {
    $base = log($bytes, 1024);
    $suffixes = ['', 'KB', 'MB', 'GB', 'TB'];   

    return round(pow(1024, $base - floor($base)), 0).' '.$suffixes[floor($base)];
}
function resultEnd() {
    global $error;
    
    $error = 14 + $error;
    if($error == 14) {
        return '<div class="final result success">KuschelTickets wird vollständig unterstützt</div>';
    } else if($error > 10) {
        return '<div class="final result warning">KuschelTickets könnte unterstützt werden, jedoch konnten einige Überprüfungen nicht ausgeführt werden</div>';
    } else {
        return '<div class="final result error">KuschelTickets wird NICHT unterstützt</div>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Anforderungen - KuschelTickets</title>
        <meta charset="utf-8">
        <style>
            html {
                font-family: 'Arial', sans-serif;
            }
            .success {
                color: #4cd137;
            }
            .error {
                color: #e84118;
            }
            .warning {
                color: #e1b12c;
            }
            .success::before {
                content: '✔';
                margin-right: 7px;
                padding-right: 7px;
                border-right: 2px dotted #44bd32;
            }
            .errror::before {
                content: '✘';
                margin-right: 7px;
                padding-right: 7px;
                border-right: 2px dotted #c23616;
            }
            .warning::before {
                content: 'ⓛ';
                margin-right: 7px;
                padding-right: 7px;
                border-right: 2px dotted #fbc531;
            }
            footer {
                color: #7f8fa6;
                bottom: 0;
                position: absolute;
                left: 0;
                right: 0;
                width: fit-content;
                margin: auto;
                margin-bottom: 20px;
            }
            footer a {
                color: #7f8fa6;
            }
            footer a:hover, footer a:focus {
                color: #718093;
            }
            footer span:not(:last-child)::after {
                content: ' | ';
            }
            header {
                color: #2185d0;
                text-decoration: underline;
                top: 0;
                position: absolute;
                left: 0;
                right: 0;
                width: fit-content;
                margin: auto;
                margin-bottom: 20px;
                margin-top: 20px;
                line-break: normal;
            }
            article {
                margin-top: 130px;
            }
            article .result {
                width: fit-content;
                margin: auto;
                left: 0;
                right: 0;
                font-size: larger;
                font-weight: bold;
                margin-bottom: 7px;
            }
            article .result.final {
                margin-top: 25px;
                padding: 10px;
                margin-bottom: 0;
                font-size: x-large;
                border: 5px dotted #2185d0;
            }
            @media only screen and (max-width: 600px) {
                header {
                    padding-left: 25%;
                }
            }
        </style>
    </head>
    <body>
        <header>
            <h1>KuschelTickets - Anforderungstest</h1>
        </header>
        <article>
            <?php echo resultVersion(); ?>
            <?php echo resultMemory(); ?>
            <?php echo resultDisk(); ?>
            <?php echo resultExtension("Core"); ?>
            <?php echo resultExtension("date"); ?>
            <?php echo resultExtension("curl"); ?>
            <?php echo resultExtension("filter"); ?>
            <?php echo resultExtension("json"); ?>
            <?php echo resultExtension("session"); ?>
            <?php echo resultExtension("standard"); ?>
            <?php echo resultExtension("PDO"); ?>
            <?php echo resultExtension("pdo_mysql"); ?>
            <?php echo resultExtension("mbstring"); ?>
            <?php echo resultExtension("bcmath"); ?>
            <?php echo resultEnd(); ?>
        </article>
    </body>
    <footer>
        <span><a href="https://github.com/Kuschel-Swein/KuschelTickets/">KuschelTickets</a></span>
        <span>ein Projekt von <a href="https://github.com/Kuschel-Swein">Kuschel_Swein</a></span>
        <span><a href="https://github.com/Kuschel-Swein/KuschelTickets/wiki">Wiki</a></span>
        <span><a href="https://demo.kuschel-swein.eu/KuschelTickets">Demo</a></span>
    </footer>
</html>