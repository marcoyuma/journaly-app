<?php

declare(strict_types=1);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

echo "Method: {$method}\n";
echo "Path: {$uri}\n";
