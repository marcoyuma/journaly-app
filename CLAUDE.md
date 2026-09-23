# CLAUDE.md

## Project

Super simple journal app (login for 1 user + journal CRUD) for learning and interview prep. Backend: native PHP (no framework, no Composer) + MySQL, JSON REST API. Frontend: Next.js App Router that proxies `/api/*` to PHP via `rewrites`.

## Commands

```bash
php -S localhost:8000 -t backend/public      # backend (terminal 1)
cd frontend && npm run dev                   # frontend (terminal 2), http://localhost:3000
cd frontend && npm run lint && npm run build # checks
php backend/bin/create-user.php <username>   # create user / change password
mysql -u root < backend/database/schema.sql  # create database + tables
```

## Conventions

- UI text and API error messages: Indonesian.
- Code, code comments, and commit messages: English. Conventional Commits.
- `docs/` is Indonesian, line-by-line study documentation, and is gitignored.
- Keep it super simple: no new features, libraries, frameworks, or abstractions beyond the current scope.
- PHP: `declare(strict_types=1);`, functions (no classes), PSR-12, prepared statements only.
- Never commit secrets (`backend/config/config.php`, `frontend/.env.local`).

## Rule: docs must follow code

Every code change MUST be followed by updating the related file in `docs/` (full code block identical to the file, line-by-line explanation) and `docs/COVERAGE.md` (line count from `wc -l`, status).
