# Journaly

Learning project: the learner builds a native PHP backend (no framework) from zero, module by module, for an existing Next.js frontend. The goal is learning backend development, not shipping a product. Bridge target: Laravel.

Start every session by reading `docs/progress.md`. Scope and the exact frontend contract: `docs/PRD.md`. Design and versions: `docs/ARCHITECTURE.md`. Module plan: `docs/modules/00-roadmap.md`. How to write a module: `docs/modules/00-panduan-modul.md`. Which concepts are already taught: `docs/concept-register.md`.

## Commands

- `cd backend && composer install`: install dev tools from `composer.lock` (PHPUnit for the grader).
- `cd backend && composer grade NN`: run the grader for module `NN` (for example `composer grade 01`). Extra PHPUnit flags go after `--`, e.g. `composer grade 01 -- --colors=never`.
- `cd backend && composer audit`: must report no advisories after every dependency change.
- `php -S localhost:8000 -t backend/public`: the API (from Module 07 on). The frontend proxies `/api/*` to this address.
- `cd frontend && npm run dev`: the frontend on port 3000 (only needed from the Module 22 milestone on).
- Playground scripts: `cd backend && php playground/NN-name.php`. Playground HTTP servers use port 8001.

## Working rules

- Explanations to the learner are in Indonesian; code, identifiers, file names, and commit messages are in English. API error messages stay in Indonesian because the frontend contract (`docs/PRD.md`) fixes them. No em dash anywhere.
- Every module follows `docs/modules/00-panduan-modul.md`: at most 5 new items per module (counted strictly and listed at the top), no forward references (every identifier in a code block is in `docs/concept-register.md` or marked "dijelaskan di Modul X"), every method an exercise needs appears in the module body first, full exercise template, check-question answers cite "Dasar: Konsep N" and add nothing beyond it, module about 250 lines, solution about 120 lines, one short "Di Laravel" section at the end.
- Beginner way first; every simplification goes into the "Utang belajar" table in `docs/progress.md` with the module that pays it off. The paying module returns to the same file and shows before and after.
- The mentor never writes learner code in `backend/` (no `src/`, `public/`, `playground/`, `tests/`, `bin/`, `database/` content). The mentor may only: write `docs/`, write `backend/grader/` (the grader), and create empty files and folders a module names. All learner code lives in the module doc, labeled KETIK SENDIRI (PHP and SQL, the module's subject) or SALIN-TEMPEL (configuration and boilerplate, with one sentence on what it is for).
- Solutions are Markdown only, at `docs/solutions/NN-slug/solution.md`, headings numbered exactly like the module (`## Latihan 2`, `## Pertanyaan 1`), each question linking to its answer. Never copy solution code into `backend/` on the learner's behalf.
- Before publishing a module, run everything in a scratchpad copy of the repo: paste the SALIN-TEMPEL blocks, type the solution, run `composer grade NN` until green, and confirm that an empty or wrong file turns it red with a readable message. Every output pasted in a module comes from that run.
- Grader tests are black box: they check CLI output, HTTP status, headers, and JSON against the exercise contract, never the implementation. One test class per exercise under `backend/grader/ModulNN/`, one testsuite per module in `backend/grader/phpunit.xml`, test names in Indonesian via `#[TestDox]`.
- The learner commits their own work on a `feat/NN-slug` branch and merges it into `main`. The mentor commits only what the mentor wrote (docs, grader, empty files), on `main`, and never pushes. Feature branches touch only `backend/`; docs change on `main`.
- One module per session. At the end of a module: update `docs/progress.md`, `docs/glossary.md`, and `docs/concept-register.md`.
- If a decision in PRD or ARCHITECTURE changes, record it in `docs/progress.md` under "Keputusan yang berubah".

## Hard rules

- `frontend/` is never edited. The backend must satisfy the contract in `docs/PRD.md` exactly.
- Never add, remove, or upgrade a Composer package without explicit learner approval, including the reason and rejected alternatives. Versions are exact (no `^` or `~`). Commit `composer.lock`. No runtime (`require`) packages: only `require-dev` tools.
- Never commit `backend/config/config.php` or any secret.
- Never run destructive database commands. Dropping or resetting a database is written in a module for the learner to run, with a warning.
- Never `git push`, `git push --force`, `git reset --hard`, or rewrite history.

## Environment quirks

- PHP 8.5.10 and Composer 2.10.3 from Homebrew. Extensions include `pdo_mysql`, `mbstring`, `curl`, `intl`. No Xdebug or pcov (no coverage reports).
- MySQL 8.4 comes from the Homebrew formula `mysql@8.4` (binary `/opt/homebrew/opt/mysql@8.4/bin/mysql`), running as a brew service. `root` has no password. The old database `journal_app` and MySQL user `journal_app` still exist until the learner drops them in Module 13.
- PostgreSQL 18 also runs locally but is not used by this project.
- Docker is not installed (Module 37 is optional).
- The Homebrew CLI `php.ini` has `display_errors=STDOUT` and `log_errors=On`, so every warning is printed twice in the terminal ("PHP Warning:" on stderr and "Warning:" on stdout). The grader runs scripts with `display_errors=stderr` and `log_errors=0`.
- The shell is zsh. A line printed without a trailing newline shows a highlighted `%` after it.
- The repo has a GitHub remote (`origin`). Pushing is the learner's choice.
