<?php

declare(strict_types=1);

// POST /auth/login: check username + password, then store user_id in the session.
function handleLogin(): void
{
    $body = readJsonBody();
    $username = is_string($body['username'] ?? null) ? $body['username'] : '';
    $password = is_string($body['password'] ?? null) ? $body['password'] : '';

    $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user === false || !password_verify($password, $user['password_hash'])) {
        sendError(401, 'Username atau password salah.');
    }

    // New session ID after login prevents session fixation.
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];

    sendJson(200, ['data' => ['id' => $user['id'], 'username' => $user['username']]]);
}

// POST /auth/logout: clear the session data, delete the cookie, destroy the session.
function handleLogout(): void
{
    requireAuth();

    $_SESSION = [];

    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite'],
    ]);

    session_destroy();

    sendNoContent();
}

// GET /auth/me: return the currently logged-in user.
function handleMe(): void
{
    $userId = requireAuth();

    $stmt = db()->prepare('SELECT id, username FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user === false) {
        sendError(401, 'Silakan login terlebih dahulu.');
    }

    sendJson(200, ['data' => $user]);
}

// Return the logged-in user's id, or stop with 401 if nobody is logged in.
function requireAuth(): int
{
    if (!isset($_SESSION['user_id'])) {
        sendError(401, 'Silakan login terlebih dahulu.');
    }

    return (int) $_SESSION['user_id'];
}
