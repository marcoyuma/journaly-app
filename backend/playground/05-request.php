<?php

declare(strict_types=1);

// 1. php fills $_SERVER for every request
$method = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];
echo "Method: {$method}\n";
echo "URI: {$uri}\n";

// 2. the path is the URI without the query string
$path = parse_url($uri, PHP_URL_PATH);

// 3. query parameters arrive in $_GET, always as strings
var_dump($_GET);

// 4. ?? gives a default when the key does not exist
$q = $_GET['q'] ?? '';
echo "Cari: {$q}\n";
