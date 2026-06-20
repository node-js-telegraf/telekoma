<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");

$url = 'http://50.7.24.50/nginx_status';

$response = file_get_contents($url);
if ($response === FALSE) {
    http_response_code(500);
    echo "Error fetching data.";
    exit;
}

echo $response;
?>