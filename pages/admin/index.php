<?php
$site = false;
$opts = [
    "http" => [
        "method" => "GET",
        "header" => [
            "User-Agent: PHP"
        ]
    ]
];
$context = stream_context_create($opts);
try {
    $data = file_get_contents("https://api.github.com/repos/Kuschel-Swein/KuschelTickets/releases/latest", false, $context);
    $data = json_decode($data);
    if($config['version'] !== $data->tag_name) {
        $site = true;
    }
} catch(Exception $e) {
    // ignore
}