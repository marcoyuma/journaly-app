<?php

declare(strict_types=1);

require __DIR__ . '/http.php';
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/journals.php';

// Never print PHP errors into the response; write them to the error log instead.
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Turn PHP warnings and notices into exceptions so they are handled in one place.
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Any uncaught exception: log the technical detail, send a generic 500 to the client.
set_exception_handler(function (Throwable $e): void {
    error_log((string) $e);
    sendError(500, 'Terjadi kesalahan pada server.');
});

// Session cookie (PHPSESSID) settings, then start or resume the session.
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();
