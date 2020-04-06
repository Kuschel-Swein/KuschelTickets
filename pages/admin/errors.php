<?php
$errors = [];

foreach(glob("./data/logs/*.txt") as $file) {
    $data = array(
        "content" => file_get_contents($file),
        "date" => filemtime($file),
        "filename" => str_replace(".txt", "", basename($file))
    );
    array_push($errors, $data);
}


$site = array(
    "errors" => $errors
);