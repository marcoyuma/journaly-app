<?php

declare(strict_types=1);

// Columns returned for a journal. Timestamps are formatted as ISO 8601 UTC strings.
const JOURNAL_COLUMNS = "id, title, content, "
    . "DATE_FORMAT(created_at, '%Y-%m-%dT%H:%i:%sZ') AS created_at, "
    . "DATE_FORMAT(updated_at, '%Y-%m-%dT%H:%i:%sZ') AS updated_at";

const TITLE_MAX_LENGTH = 150;
const CONTENT_MAX_LENGTH = 20000;

// GET /journals: all journals of the logged-in user, most recently updated first.
function listJournals(): void
{
    $userId = requireAuth();

    $stmt = db()->prepare(
        'SELECT ' . JOURNAL_COLUMNS . ' FROM journals WHERE user_id = ? ORDER BY updated_at DESC, id DESC'
    );
    $stmt->execute([$userId]);

    sendJson(200, ['data' => $stmt->fetchAll()]);
}

// GET /journals/{id}
function showJournal(int $id): void
{
    $userId = requireAuth();

    sendJson(200, ['data' => findJournalOrFail($id, $userId)]);
}

// POST /journals
function createJournal(): void
{
    $userId = requireAuth();
    [$title, $content] = validateJournal(readJsonBody());

    $stmt = db()->prepare('INSERT INTO journals (user_id, title, content) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $title, $content]);
    $id = (int) db()->lastInsertId();

    sendJson(201, ['data' => findJournalOrFail($id, $userId)]);
}

// PUT /journals/{id}
function updateJournal(int $id): void
{
    $userId = requireAuth();
    findJournalOrFail($id, $userId);
    [$title, $content] = validateJournal(readJsonBody());

    $stmt = db()->prepare('UPDATE journals SET title = ?, content = ? WHERE id = ? AND user_id = ?');
    $stmt->execute([$title, $content, $id, $userId]);

    sendJson(200, ['data' => findJournalOrFail($id, $userId)]);
}

// DELETE /journals/{id}
function deleteJournal(int $id): void
{
    $userId = requireAuth();

    $stmt = db()->prepare('DELETE FROM journals WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);

    if ($stmt->rowCount() === 0) {
        sendError(404, 'Jurnal tidak ditemukan.');
    }

    sendNoContent();
}

// Find one journal owned by the user, or stop with 404.
// Someone else's journal also gives 404, so its existence is not revealed.
function findJournalOrFail(int $id, int $userId): array
{
    $stmt = db()->prepare('SELECT ' . JOURNAL_COLUMNS . ' FROM journals WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $userId]);
    $journal = $stmt->fetch();

    if ($journal === false) {
        sendError(404, 'Jurnal tidak ditemukan.');
    }

    return $journal;
}

// Validate title and content. Returns [title, content] trimmed, or stops with 422.
function validateJournal(array $body): array
{
    $title = is_string($body['title'] ?? null) ? trim($body['title']) : '';
    $content = is_string($body['content'] ?? null) ? trim($body['content']) : '';
    $errors = [];

    if ($title === '') {
        $errors['title'] = 'Judul wajib diisi.';
    } elseif (mb_strlen($title) > TITLE_MAX_LENGTH) {
        $errors['title'] = 'Judul maksimal 150 karakter.';
    }

    if ($content === '') {
        $errors['content'] = 'Isi jurnal wajib diisi.';
    } elseif (mb_strlen($content) > CONTENT_MAX_LENGTH) {
        $errors['content'] = 'Isi jurnal maksimal 20.000 karakter.';
    }

    if ($errors !== []) {
        sendError(422, 'Data tidak valid.', $errors);
    }

    return [$title, $content];
}
