<?php

declare(strict_types=1);

// define status code
http_response_code(503);

// define header
header("Content-Type: text/plain; charset=UTF-8");

echo "Journaly sedang perawatan. Coba lagi nanti";
