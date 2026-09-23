<?php

declare(strict_types=1);

// Usage: php backend/bin/create-user.php <username>
// Creates a user, or changes the password if the username already exists.

require __DIR__ . '/../src/db.php';

if (PHP_SAPI !== 'cli') {
    exit("Script ini hanya bisa dijalankan dari terminal.\n");
}

$username = $argv[1] ?? '';

if ($username === '' || mb_strlen($username) > 50) {
    fwrite(STDERR, "Pemakaian: php backend/bin/create-user.php <username> (maksimal 50 karakter)\n");
    exit(1);
}

// Hide typed characters when running in a real terminal (not when input is piped).
$isTerminal = stream_isatty(STDIN);

fwrite(STDOUT, 'Password: ');

if ($isTerminal) {
    shell_exec('stty -echo');
}

$password = rtrim((string) fgets(STDIN), "\r\n");

if ($isTerminal) {
    shell_exec('stty echo');
    fwrite(STDOUT, "\n");
}

if ($password === '') {
    fwrite(STDERR, "Password tidak boleh kosong.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = db()->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);
$existingId = $stmt->fetchColumn();

if ($existingId === false) {
    $stmt = db()->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$username, $hash]);
    fwrite(STDOUT, "User '{$username}' berhasil dibuat.\n");
} else {
    $stmt = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt->execute([$hash, $existingId]);
    fwrite(STDOUT, "Password user '{$username}' berhasil diperbarui.\n");
}
