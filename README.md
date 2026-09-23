# Journal App

A deliberately simple personal journal app: one user logs in and can create, read, update, and delete journal entries (title + plain-text content). The backend is a JSON REST API written in native PHP (no framework, no Composer) on MySQL, and the frontend is a Next.js App Router app.

## Tech stack

- **Backend:** PHP 8.2+ (native, function-based, PSR-12), PDO, PHP built-in web server
- **Database:** MySQL 8.0+ (InnoDB, utf8mb4)
- **Frontend:** Next.js (App Router), React, TypeScript, Tailwind CSS, Zod

## Features

- Login / logout for a single user (user is created from the command line; there is no registration)
- Journal list (most recently updated first), create, view, edit, delete
- Server-side validation with per-field error messages
- UI in Indonesian

## Architecture

```
Browser
   │  http://localhost:3000
   ▼
Next.js (frontend/)
   │  rewrites: /api/:path*  →  http://localhost:8000/:path*
   ▼
PHP built-in server (backend/public/index.php)
   │  PDO (prepared statements)
   ▼
MySQL (database: journal_app)
```

The browser only ever talks to `localhost:3000`. Next.js forwards `/api/*` to PHP, so there is a single origin: no CORS configuration is needed and the PHP session cookie (`PHPSESSID`) works normally.

```
backend/
  public/index.php          front controller + router
  src/bootstrap.php         error handling, session cookie settings, loads other files
  src/db.php                PDO connection
  src/http.php              JSON response and request body helpers
  src/auth.php              login, logout, me, requireAuth
  src/journals.php          journal CRUD handlers + validation
  config/config.example.php example config (copy to config.php)
  database/schema.sql       database and tables
  bin/create-user.php       CLI to create a user or change its password
frontend/
  app/                      pages (login, journals list/new/detail)
  components/JournalForm.tsx
  lib/api.ts                fetch wrapper + ApiError
  lib/schemas.ts            Zod schemas
  lib/format.ts             date formatting
```

## Setup from scratch

Requirements: PHP 8.2+ with `pdo_mysql` and `mbstring`, MySQL 8.0+, Node.js 20+.

1. **Create the database and tables** (adjust the MySQL user if your root has a password: add `-p`):

   ```bash
   mysql -u root < backend/database/schema.sql
   ```

2. **Create a dedicated MySQL user** (replace `your-db-password`):

   ```bash
   mysql -u root -e "CREATE USER 'journal_app'@'localhost' IDENTIFIED BY 'your-db-password';
     GRANT ALL PRIVILEGES ON journal_app.* TO 'journal_app'@'localhost';"
   ```

3. **Backend config:** copy the example and put the same password in it. `config.php` is gitignored.

   ```bash
   cp backend/config/config.example.php backend/config/config.php
   ```

4. **Create the app user** (the password is asked with a hidden prompt, so it never lands in shell history):

   ```bash
   php backend/bin/create-user.php <username>
   ```

   Running it again with the same username changes that user's password.

5. **Frontend env and dependencies:**

   ```bash
   cp frontend/.env.example frontend/.env.local
   cd frontend && npm install
   ```

## Running

Use two terminals from the repository root.

```bash
# Terminal 1: backend (PHP built-in server)
php -S localhost:8000 -t backend/public
```

```bash
# Terminal 2: frontend
cd frontend && npm run dev
```

Open http://localhost:3000 and log in.

## API

All bodies are JSON. Through the frontend, every path below is prefixed with `/api` (for example `/api/journals`).

| Method | Path | Auth | Body | Success |
|---|---|---|---|---|
| POST | `/auth/login` | No | `{ "username", "password" }` | 200 `{ "data": { "id", "username" } }` |
| POST | `/auth/logout` | Yes | - | 204 |
| GET | `/auth/me` | Yes | - | 200 `{ "data": { "id", "username" } }` |
| GET | `/journals` | Yes | - | 200 `{ "data": [ ... ] }` (newest `updated_at` first) |
| GET | `/journals/{id}` | Yes | - | 200 `{ "data": { ... } }` |
| POST | `/journals` | Yes | `{ "title", "content" }` | 201 `{ "data": { ... } }` |
| PUT | `/journals/{id}` | Yes | `{ "title", "content" }` | 200 `{ "data": { ... } }` |
| DELETE | `/journals/{id}` | Yes | - | 204 |

A journal is `{ "id", "title", "content", "created_at", "updated_at" }`, with timestamps in ISO 8601 UTC (for example `2026-09-24T03:15:00Z`).

Errors are `{ "error": "message" }`. Validation errors (422) also include per-field messages: `{ "error": "Data tidak valid.", "errors": { "title": "...", "content": "..." } }`.

Status codes: 200, 201, 204, 400 (invalid JSON), 401 (not logged in / wrong credentials), 404 (unknown route or journal not found), 405 (method not allowed), 415 (POST/PUT without `Content-Type: application/json`), 422 (validation), 500 (server error).

Validation: `title` is required, trimmed, max 150 characters; `content` is required, trimmed, max 20,000 characters.

## Security notes

- **SQL injection:** every query uses PDO prepared statements, with native prepares (`ATTR_EMULATE_PREPARES = false`).
- **Passwords:** stored with `password_hash()` (bcrypt by default) and checked with `password_verify()`. A failed login always returns the same generic message.
- **Session fixation:** `session_regenerate_id(true)` runs right after a successful login.
- **Session cookie:** `HttpOnly` (not readable from JavaScript) and `SameSite=Lax`. `Secure` is off only because the app runs on plain `http://localhost`.
- **Authorization:** every journal query is filtered by the `user_id` stored in the session. Another user's journal answers 404, the same as a missing one.
- **CSRF (simple mitigation):** POST and PUT must send `Content-Type: application/json` (otherwise 415). A plain HTML form cannot send that header cross-site, and `SameSite=Lax` keeps the cookie off cross-site POSTs.
- **XSS:** React escapes rendered text by default. Journal content is rendered as text (`whitespace-pre-wrap`) and `dangerouslySetInnerHTML` is never used.
- **Error leakage:** unexpected errors return a generic 500 message; details only go to the server's `error_log`.
- **Secrets:** `backend/config/config.php` and `frontend/.env.local` are gitignored.
