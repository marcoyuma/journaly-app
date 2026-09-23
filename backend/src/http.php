<?php

declare(strict_types=1);

// Send a JSON response with the given status code and stop the script.
function sendJson(int $status, array $body): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

// Send "204 No Content" (success without a body) and stop the script.
function sendNoContent(): never
{
    http_response_code(204);
    exit;
}

// Send an error in the format { "error": "...", "errors": { ... } }.
function sendError(int $status, string $message, array $errors = []): never
{
    $body = ['error' => $message];

    if ($errors !== []) {
        $body['errors'] = $errors;
    }

    sendJson($status, $body);
}

// Reject requests whose body is not declared as JSON (415).
function requireJsonContentType(): void
{
    $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

    if (!str_starts_with($contentType, 'application/json')) {
        sendError(415, 'Content-Type harus application/json.');
    }
}

// Read the request body and decode it as a JSON object (associative array).
function readJsonBody(): array
{
    $raw = file_get_contents('php://input') ?: '';

    try {
        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        sendError(400, 'Body JSON tidak valid.');
    }

    if (!is_array($data)) {
        sendError(400, 'Body JSON tidak valid.');
    }

    return $data;
}
