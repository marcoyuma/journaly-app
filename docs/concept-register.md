# Register konsep

Daftar konsep yang sudah dan akan diajarkan, beserta modulnya. Aturannya ada di [panduan modul](./modules/00-panduan-modul.md): sebelum sebuah modul memakai identifier di blok kode, identifier itu harus tercatat di sini sebagai **Diajarkan** di modul yang sama atau lebih awal. Definisi istilah ada di [glosarium](./glossary.md); register ini mencatat **urutan**.

Kolom "Status":
- **Diajarkan**: dijelaskan lengkap saat pertama dipakai.
- **Terjadwal**: belum boleh dipakai sebelum modul yang disebut (rencana roadmap, boleh bergeser).
- **Dipakai dulu, dilunasi di X**: sengaja dipakai sebelum dijelaskan tuntas (tercatat juga di utang belajar), dengan penanda "dijelaskan di Modul X" di tempat.

## Tooling dan git

| Konsep | Diajarkan di | Status |
|---|---|---|
| Terminal, folder kerja `backend` | 00 | Diajarkan |
| Composer, `composer.json` (`require`, `require-dev`, `scripts`) | 00 | Diajarkan (detail autoload di 29) |
| `composer install`, `vendor/`, `composer.lock` | 00 | Diajarkan |
| Grader, `composer grade NN`, membaca `✔`/`✘` dan Expected/Actual | 00 | Diajarkan |
| `git status`, `git add <file>`, `git commit -m` | 00 | Diajarkan |
| `git switch -c`, `git switch`, `git merge`, `git branch -d`, `git merge --abort` | 00 | Diajarkan |
| PHPStan, PHP-CS-Fixer, `composer check` | 28 | Terjadwal |
| Autoload PSR-4, `composer dump-autoload` | 29 | Terjadwal |
| Docker, `Dockerfile`, `compose.yaml` | 37 | Terjadwal (opsional) |

## Bahasa PHP

| Konsep | Diajarkan di | Status |
|---|---|---|
| `php nama-file.php` | 01 | Diajarkan |
| Tag `<?php`, teks di luar tag dicetak apa adanya | 01 | Diajarkan |
| `echo`, `;`, komentar `//` | 01 | Diajarkan |
| String petik satu `'...'` | 01 | Diajarkan |
| Variabel `$nama = nilai;`, peka huruf besar kecil | 01 | Diajarkan |
| String petik dua, interpolasi `{$nama}`, `\n` | 01 | Diajarkan |
| Parse error, Warning "Undefined variable" | 01 | Diajarkan (cara membaca) |
| `declare(strict_types=1)`, tipe `int`/`string`/`bool`/`null` | 02 | Terjadwal |
| `if`/`else`, `===` | 02 | Terjadwal |
| Fungsi bertipe, `return`, `TypeError` | 02 | Terjadwal |
| Array list, array asosiatif, `foreach`, `count` | 03 | Terjadwal |
| `json_encode` | 03 | Terjadwal (flag di 06) |
| `match` | 07 | Terjadwal |
| `require` | 07 | Terjadwal (dilunasi oleh autoload di 29) |
| `exit` | 07 | Terjadwal |
| `preg_match`, `$matches`, cast `(int)` | 08 | Terjadwal (satu pola saja) |
| `try`/`catch`, `JsonException` | 09 | Terjadwal |
| `trim`, `mb_strlen`, `is_string` | 10 | Terjadwal |
| `file_put_contents`, `file_get_contents`, `LOCK_EX`, `file_exists` | 09, 11 | Terjadwal |
| `array_filter`, `array_values` | 12 | Terjadwal |
| `static` di dalam fungsi | 18 | Terjadwal |
| `$argv` | 19 | Terjadwal |
| `class`, `new`, `extends`, `throw` | 23 | Terjadwal (`new PDO` dipakai dulu di 16) |
| `namespace`, `use` | 29 | Terjadwal |
| Constructor, `private readonly` | 30 | Terjadwal |

## HTTP dan PHP untuk web

| Konsep | Diajarkan di | Status |
|---|---|---|
| Request, response, status code, header | 04 | Terjadwal |
| `php -S`, `curl -i` | 04 | Terjadwal |
| `$_SERVER['REQUEST_METHOD']`, `$_SERVER['REQUEST_URI']`, `parse_url`, `$_GET`, `http_response_code` | 05 | Terjadwal |
| `header()`, pembungkus `{data}`/`{error}`, 201, 204 | 06 | Terjadwal |
| Front controller `public/index.php`, 405 | 07 | Terjadwal |
| Route parameter, 404 | 08 | Terjadwal |
| `php://input`, 400, 415 | 09 | Terjadwal |
| Validasi, array `errors`, 422 | 10 | Terjadwal |
| Share-nothing | 11 | Terjadwal |
| `session_start`, `$_SESSION`, cookie, `session_regenerate_id`, `session_set_cookie_params` | 20 | Terjadwal |
| `session_destroy`, `setcookie`, `requireAuth`, 401 | 21 | Terjadwal |
| IDOR, 404 bukan 403 | 22 | Terjadwal |
| `set_exception_handler`, `set_error_handler`, `ErrorException`, `error_log`, 500 | 24 | Terjadwal |
| Request id, `random_bytes`, `X-Request-Id` | 27 | Terjadwal |
| CSRF, `SameSite`, header `Origin` | 32 | Terjadwal |
| Rate limit, 429, `Retry-After` | 33 | Terjadwal |
| `header_remove`, `X-Content-Type-Options`, `strlen` dibanding `mb_strlen` | 34 | Terjadwal |

## SQL dan PDO

| Konsep | Diajarkan di | Status |
|---|---|---|
| CLI `mysql`, `CREATE DATABASE`, `CREATE TABLE`, `INSERT`, `SELECT`, `DROP DATABASE` | 13 | Terjadwal |
| `WHERE`, `ORDER BY`, `UPDATE`, `DELETE`, `AUTO_INCREMENT`, primary key | 14 | Terjadwal |
| `FOREIGN KEY`, `ON DELETE CASCADE`, index, file SQL bernomor | 15 | Terjadwal |
| `new PDO`, DSN, `PDO::ERRMODE_EXCEPTION`, `query`, `fetchAll`, `config.php` | 16 | Terjadwal |
| `prepare`, `execute`, SQL injection, `fetch`, `lastInsertId`, `rowCount` | 17 | Terjadwal |
| `DATE_FORMAT`, `time_zone` | 18 | Terjadwal |
| `password_hash`, `password_verify` | 19 | Terjadwal |
| Tabel `schema_migrations`, migration runner | 31 | Terjadwal |

## Testing

| Konsep | Diajarkan di | Status |
|---|---|---|
| Grader sebagai pemeriksa (dipakai, belum ditulis sendiri) | 00 | Diajarkan |
| `TestCase`, `assertSame`, `composer test` | 25 | Terjadwal |
| Database test `journaly_test`, test HTTP | 26 | Terjadwal |
