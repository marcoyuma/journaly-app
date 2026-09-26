# Solution 04. HTTP dan server bawaan PHP

Soal ada di [Modul 04](../../modules/04-http-server-bawaan.md). Kerjakan sendiri dulu. Buka file ini setelah `composer grade 04` hijau, atau setelah ketiga petunjuk tidak cukup.

## Latihan 1

`backend/playground/04-latihan-1.php`:

```php
<?php

declare(strict_types=1);

// 1. Every request runs this file, so every path gets the same body
echo "Selamat datang di Journaly API\n";
```

`curl -i localhost:8001/journals` (server: `php -S localhost:8001 playground/04-latihan-1.php`):

```text
HTTP/1.1 200 OK
Host: localhost:8001
Date: Sat, 26 Sep 2026 20:34:42 GMT
Connection: close
X-Powered-By: PHP/8.5.10
Content-type: text/html; charset=UTF-8

Selamat datang di Journaly API
```

Grader mengirim `GET /`, `GET /journals`, dan `POST /auth/login`, dan ketiganya harus mendapat jawaban yang sama.

## Latihan 2

`backend/playground/04-latihan-2.php`:

```php
<?php

declare(strict_types=1);

// 1. Status code before the body
http_response_code(401);

// 2. The body
echo "Silakan login terlebih dahulu.\n";
```

`curl -i localhost:8001/auth/me`:

```text
HTTP/1.1 401 Unauthorized
Host: localhost:8001
Date: Sat, 26 Sep 2026 20:34:43 GMT
Connection: close
X-Powered-By: PHP/8.5.10
Content-type: text/html; charset=UTF-8

Silakan login terlebih dahulu.
```

Bila Anda menulis `http_response_code('401');`, grader menampilkan error yang dicatat server (dipotong):

```text
PHP menampilkan warning atau error saat playground/04-latihan-2.php menangani GET /auth/me:
PHP Fatal error:  Uncaught TypeError: http_response_code(): Argument #1 ($response_code) must be of type int, string given in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/04-latihan-2.php:6
```

## Latihan 3

`backend/playground/04-latihan-3.php`:

```php
<?php

declare(strict_types=1);

// 1. Status code and headers first
http_response_code(503);
header('Content-Type: text/plain; charset=UTF-8');
header('Retry-After: 120');

// 2. Then the body
echo "Journaly sedang perawatan. Coba lagi nanti.\n";
```

Output `composer grade 04` (hasil eksekusi, baris kepala PHPUnit dipotong):

```text
Latihan1 (Grader\Modul04\Latihan1)
 ✔ Latihan 1: setiap request dijawab 200 dengan body sambutan

Latihan2 (Grader\Modul04\Latihan2)
 ✔ Latihan 2: menjawab 401 dengan body "Silakan login terlebih dahulu."

Latihan3 (Grader\Modul04\Latihan3)
 ✔ Latihan 3: status 503
 ✔ Latihan 3: header Content-Type dan Retry-After persis sesuai kontrak
 ✔ Latihan 3: body pesan perawatan persis sesuai kontrak

OK (5 tests, 12 assertions)
```

Bila baris `Retry-After` terlupa, grader menulis:

```text
Header Retry-After belum ada atau nilainya bukan 120.
```

## Pertanyaan 1

Dasar: Konsep 2.

- Status code `404` (Not Found), kelompok `4xx`: kesalahan dari klien, yaitu yang diminta tidak ada.
- Jenis isi body: `text/plain; charset=UTF-8`, teks biasa dalam UTF-8.
- Body: `Endpoint tidak ditemukan.`, semua yang tertulis setelah baris kosong.

## Pertanyaan 2

Dasar: Konsep 1.

Tidak ada server yang menunggu di port 8001: server belum dijalankan atau sudah dihentikan dengan Ctrl+C. Periksa terminal pertama. Bila tidak ada server yang berjalan, nyalakan lagi dengan `php -S localhost:8001 playground/<file>.php` dari folder `backend`.

## Pertanyaan 3

Dasar: Konsep 3 dan Konsep 4.

```php
http_response_code(404);
header('Content-Type: text/plain; charset=UTF-8');
echo "Tidak ada.\n";
```

Status line dan header dikirim sebelum body, jadi `http_response_code` dan `header` ditulis sebelum `echo` pertama.
