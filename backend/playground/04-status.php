<?php

declare(strict_types=1);

// fundamental dalam response get

// 1 status code first, before anything is printed (the default is 200)
http_response_code(404);
// header
header('Content-Type: text/plain; charset=UTF-8');

// 2 then the body
echo "Endpoint tidak ditemukan. \n";
