<?php

declare(strict_types=1);

// Front controller: every request to the PHP server enters here.
require __DIR__ . '/../src/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

// POST and PUT must send JSON (simple CSRF mitigation together with SameSite=Lax).
if ($method === 'POST' || $method === 'PUT') {
    requireJsonContentType();
}

if ($path === '/auth/login') {
    match ($method) {
        'POST' => handleLogin(),
        default => sendError(405, 'Method tidak didukung.'),
    };
}

if ($path === '/auth/logout') {
    match ($method) {
        'POST' => handleLogout(),
        default => sendError(405, 'Method tidak didukung.'),
    };
}

if ($path === '/auth/me') {
    match ($method) {
        'GET' => handleMe(),
        default => sendError(405, 'Method tidak didukung.'),
    };
}

if ($path === '/journals') {
    match ($method) {
        'GET' => listJournals(),
        'POST' => createJournal(),
        default => sendError(405, 'Method tidak didukung.'),
    };
}

if (preg_match('#^/journals/(\d+)$#', $path, $matches) === 1) {
    $id = (int) $matches[1];

    match ($method) {
        'GET' => showJournal($id),
        'PUT' => updateJournal($id),
        'DELETE' => deleteJournal($id),
        default => sendError(405, 'Method tidak didukung.'),
    };
}

sendError(404, 'Endpoint tidak ditemukan.');
