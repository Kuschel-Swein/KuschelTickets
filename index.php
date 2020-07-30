<?php
if(!file_exists("config.php") || !file_exists("./data/INSTALLED")) {
    header("Location: install.php");
    die("Weiterleitung zum <a href='install.php'>Installer</a>");
}
require "config.php";
require "lib/system/KuschelTickets.class.php";
new kt\system\KuschelTickets();
?>