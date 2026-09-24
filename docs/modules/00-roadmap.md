# 00. Roadmap modul belajar

Peta perjalanan belajar backend PHP Journaly: daftar modul, urutan, hal baru di setiap modul, dan milestone. Apa yang dibangun ada di [PRD](../PRD.md), bagaimana dibangun di [ARCHITECTURE](../ARCHITECTURE.md), aturan menulis modul di [panduan modul](./00-panduan-modul.md).

## Aturan main

- **Satu modul, satu sesi** (45 sampai 90 menit). Maksimal 5 hal baru per modul.
- **Isi modul 02 ke atas di tabel ini adalah rencana.** Setiap modul diriset ulang saat gilirannya tiba. Hal barunya boleh bergeser asal tetap ≤5; bila bergeser, catat di [progress](../progress.md) dan perbarui [register konsep](../concept-register.md).
- **Modul selesai** bila semua syarat ini terpenuhi:
  1. `composer grade NN` hijau.
  2. Tiga pertanyaan pengecekan sudah Anda jawab, lalu dicocokkan dengan solution.
  3. Pekerjaan sudah di-merge ke `main` lewat branch fitur.
  4. Mentor sudah memperbarui progress, register, dan glosarium.
- **Modul G (mandiri)** tidak memberi kode. Anda merancang dan menulis sendiri, grader dan solution sudah tersedia, lalu mentor melakukan code review (memeriksa kode dan memberi masukan beserta alasannya).

## Peta prasyarat

```text
A. Persiapan & bahasa   00 -> 01 -> 02 -> 03
B. HTTP tanpa database  04 -> 05 -> 06 -> 07 -> 08 -> 09 -> 10 -> 11 -> 12
C. Database             13 -> 14 -> 15 -> 16 -> 17 -> 18
D. Identitas            19 -> 20 -> 21 -> 22   (milestone: frontend jalan penuh)
E. Kualitas & refactor  23 -> 24 -> 25 -> 26 -> 27 -> 28 -> 29 -> 30 -> 31
F. Keamanan             32 -> 33 -> 34
G. Mandiri              35 -> 36
H. Opsional             37 (Docker)
I. Jembatan Laravel     38a -> 38b
```

Urutannya linear: setiap modul memakai hasil modul sebelumnya.

## Daftar modul

### A. Persiapan dan bahasa PHP

| No | Modul | Hal baru (≤5) | Milestone dan utang |
|---|---|---|---|
| 00 | [Persiapan](./00-persiapan.md) | Composer dan `composer.json`, `composer install` (`vendor/`, `composer.lock`), grader `composer grade NN`, branch fitur (`git switch -c`, `git merge`, `git branch -d`) | Grader 00 hijau, commit pertama Anda |
| 01 | [Program PHP pertama](./01-program-php-pertama.md) | `php nama-file.php`, tag `<?php`, `echo` dan `;`, variabel `$nama = nilai;`, string petik dua (`{$var}`, `\n`) dibanding petik satu | |
| 02 | Tipe, kondisi, fungsi | `declare(strict_types=1)`, tipe `int`/`string`/`bool`/`null`, `if`/`else` dengan `===`, fungsi bertipe (`function f(int $x): string`), `TypeError` | |
| 03 | Array | array list `[a, b]`, array asosiatif `['k' => v]`, `foreach`, `count`, `json_encode` | |

### B. HTTP tanpa database

| No | Modul | Hal baru (≤5) | Milestone dan utang |
|---|---|---|---|
| 04 | HTTP dan server bawaan PHP | request dan response, `php -S`, `curl -i`, status code, header | Grader mulai menguji lewat HTTP |
| 05 | Membaca request | `$_SERVER['REQUEST_METHOD']`, `$_SERVER['REQUEST_URI']`, `parse_url`, `$_GET`, `http_response_code` | |
| 06 | Response JSON | `header()`, `json_encode` dengan flag, pembungkus `{data}`/`{error}`, `201`, `204` | |
| 07 | Front controller dan routing | `public/index.php` sebagai front controller, `match`, `require`, `exit`, `405` | Branch fitur pertama di aplikasi. Utang: `require` manual (dilunasi di 29) |
| 08 | Route parameter | `preg_match` untuk satu pola `#^/journals/(\d+)$#`, `$matches`, cast `(int)`, `404` | |
| 09 | Body JSON | `php://input` dengan `file_get_contents`, `json_decode` dengan `JSON_THROW_ON_ERROR`, `try`/`catch`, `400`, `415` | |
| 10 | Validasi | `trim`, `mb_strlen`, array `errors`, `422`, `is_string` | |
| 11 | PHP lupa setiap request | share-nothing, `file_put_contents`, `LOCK_EX`, `file_exists`, menyimpan jurnal ke file JSON | Utang: data di file JSON (dilunasi di 18) |
| 12 | CRUD di file | `array_filter`, `array_values`, update dan hapus berdasarkan id | 5 endpoint jurnal (tanpa login) lolos lewat curl dan grader |

### C. Database

| No | Modul | Hal baru (≤5) | Milestone dan utang |
|---|---|---|---|
| 13 | SQL dasar | CLI `mysql`, `CREATE DATABASE`, `CREATE TABLE`, `INSERT`, `SELECT` | Anda sendiri menghapus database lama `journal_app` (dengan peringatan) |
| 14 | SQL lanjut | `WHERE`, `ORDER BY`, `UPDATE`, `DELETE`, `AUTO_INCREMENT` dan primary key | |
| 15 | Relasi | tabel `users`, `FOREIGN KEY`, `ON DELETE CASCADE`, index, file SQL bernomor | Utang: migrasi dijalankan manual (dilunasi di 31) |
| 16 | PDO | `new PDO` dan DSN, `PDO::ERRMODE_EXCEPTION`, `config.php` yang tidak di-commit, `query` dan `fetchAll` | Utang: `new` dipakai sebelum class diajarkan (dilunasi di 23) |
| 17 | Prepared statement | `prepare` dan `execute`, SQL injection (demo), `fetch`, `lastInsertId`, `rowCount` | |
| 18 | Journaly pindah ke MySQL | `DATE_FORMAT` untuk waktu ISO UTC, `time_zone = '+00:00'`, fungsi `db()` dengan `static` | Melunasi 11. Semua endpoint jurnal lolos dengan MySQL |

### D. Identitas

| No | Modul | Hal baru (≤5) | Milestone dan utang |
|---|---|---|---|
| 19 | Password dan skrip terminal | `password_hash`, `password_verify`, `$argv`, `bin/create-user.php`, hash dibanding enkripsi | |
| 20 | Login dengan session | `session_start`, `$_SESSION`, cookie, `session_regenerate_id`, `session_set_cookie_params` | `POST /auth/login` dan `GET /auth/me` |
| 21 | Logout dan penjaga | `session_destroy`, `setcookie` kedaluwarsa, fungsi `requireAuth`, `401` | |
| 22 | Kepemilikan data (IDOR) | `WHERE user_id = ?` di setiap query, `404` bukan `403` | **Milestone besar: frontend jalan penuh dengan backend Anda** |

### E. Kualitas dan refactor

| No | Modul | Hal baru (≤5) | Milestone dan utang |
|---|---|---|---|
| 23 | Class dan exception | `class`, `new`, `extends Exception`, `throw` | Melunasi `new PDO` dari 16 |
| 24 | Error handler terpusat | `set_exception_handler`, `set_error_handler`, `ErrorException`, `500` generik, `error_log` | Melunasi penanganan error yang tersebar |
| 25 | Unit test sendiri | `TestCase`, `assertSame`, test `validateJournal`, `composer test` | |
| 26 | Test HTTP sendiri | database `journaly_test`, reset data sebelum test, test endpoint dengan helper HTTP | Melunasi pengecekan curl manual |
| 27 | Logging terstruktur | satu baris JSON per kejadian, request id dengan `random_bytes`, header `X-Request-Id` | |
| 28 | Analisis statis dan formatter | PHPStan, PHP-CS-Fixer, `composer check` | Paket baru, perlu persetujuan saat modul ini |
| 29 | Namespace dan autoload | `namespace`, `use`, autoload PSR-4 di `composer.json`, `composer dump-autoload` | Melunasi `require` manual |
| 30 | Class repository dan dependency injection | constructor, `private readonly`, constructor injection | |
| 31 | Migration runner | `bin/migrate.php`, tabel `schema_migrations` | Melunasi migrasi manual |

### F. Keamanan

| No | Modul | Hal baru (≤5) | Milestone dan utang |
|---|---|---|---|
| 32 | CSRF | serangan CSRF, `SameSite=Lax`, kenapa cek `415` melindungi, header `Origin` | |
| 33 | Rate limit login | tabel percobaan login, jendela waktu, `429`, `Retry-After` | |
| 34 | Batas data dan header keamanan | `strlen` dibanding `mb_strlen` (byte dan karakter), batas byte kolom `TEXT`, `header_remove('X-Powered-By')`, `X-Content-Type-Options` | Memperbaiki isi penuh emoji yang menyebabkan `500` |

### G. Mandiri (tanpa kode diberikan)

| No | Modul | Yang dirancang sendiri | Milestone |
|---|---|---|---|
| 35 | Pencarian | `GET /journals?q=kata` dengan `LIKE` dan escape `%`/`_` | Grader dan code review |
| 36 | Pagination opsional | `?page=&limit=` dengan `meta`; tanpa parameter response tetap sama persis dengan kontrak | Grader dan code review |

### H. Opsional

| No | Modul | Hal baru (≤5) | Catatan |
|---|---|---|---|
| 37 | Docker | image dan container, `Dockerfile`, `compose.yaml` dengan MySQL, volume | Tidak wajib. Butuh Docker Desktop |

### I. Jembatan Laravel

| No | Modul | Isi | Milestone |
|---|---|---|---|
| 38a | Laravel: alur request | route, controller, request dan response, validasi, dipetakan ke kode Anda | Tabel pemetaan |
| 38b | Laravel: data dan identitas | Eloquent dibanding PDO, migrasi, session auth dan middleware, testing | Tabel pemetaan lengkap |
