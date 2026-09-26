# Solution 05. Membaca request

Soal ada di [Modul 05](../../modules/05-membaca-request.md). Kerjakan sendiri dulu. Buka file ini setelah `composer grade 05` hijau, atau setelah ketiga petunjuk tidak cukup.

## Latihan 1

`backend/playground/05-latihan-1.php`:

```php
<?php

declare(strict_types=1);

// 1. Read the method and the path (the URI without its query string)
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. Print them
echo "Method: {$method}\n";
echo "Path: {$path}\n";
```

`curl -X DELETE 'localhost:8001/journals/5?confirm=1'`:

```text
Method: DELETE
Path: /journals/5
```

Bila Anda mencetak `$_SERVER['REQUEST_URI']` langsung, grader menunjukkan query string yang ikut tercetak:

```text
-Path: /journals
+Path: /journals?page=2
```

## Latihan 2

`backend/playground/05-latihan-2.php`:

```php
<?php

declare(strict_types=1);

// 1. ?name= is optional, so give it a default
$name = $_GET['name'] ?? 'tamu';

// 2. Greet
echo "Halo, {$name}!\n";
```

`curl 'localhost:8001/?name=Sari'` lalu `curl localhost:8001`:

```text
Halo, Sari!
Halo, tamu!
```

Tanpa `?? 'tamu'`, grader melaporkan warning yang dicatat server (dipotong):

```text
PHP menampilkan warning atau error saat playground/05-latihan-2.php menangani GET /:
PHP Warning:  Undefined array key "name" in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/05-latihan-2.php on line 6
```

## Latihan 3

`backend/playground/05-latihan-3.php`:

```php
<?php

declare(strict_types=1);

// 1. Only the path decides which answer to give
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/search') {
    // 2. Missing ?q= means "search everything"
    $q = $_GET['q'] ?? '(semua)';
    echo "Hasil pencarian: {$q}\n";
} else {
    // 3. Every other path does not exist
    http_response_code(404);
    echo "Endpoint tidak ditemukan.\n";
}
```

`curl -i 'localhost:8001/search?q=php'` lalu `curl -i localhost:8001/journals`:

```text
HTTP/1.1 200 OK
Host: localhost:8001
Date: Sat, 26 Sep 2026 20:37:43 GMT
Connection: close
X-Powered-By: PHP/8.5.10
Content-type: text/html; charset=UTF-8

Hasil pencarian: php
HTTP/1.1 404 Not Found
Host: localhost:8001
Date: Sat, 26 Sep 2026 20:37:43 GMT
Connection: close
X-Powered-By: PHP/8.5.10
Content-type: text/html; charset=UTF-8

Endpoint tidak ditemukan.
```

Output `composer grade 05` (hasil eksekusi, baris kepala PHPUnit dipotong):

```text
Latihan1 (Grader\Modul05\Latihan1)
 ✔ Latihan 1: mencetak method dan path (tanpa query string) dari setiap request

Latihan2 (Grader\Modul05\Latihan2)
 ✔ Latihan 2: menyapa nama dari query string ?name=
 ✔ Latihan 2: tanpa ?name= menyapa "tamu" tanpa warning

Latihan3 (Grader\Modul05\Latihan3)
 ✔ Latihan 3: /search menjawab 200 dengan kata kuncinya, atau "(semua)" tanpa ?q=
 ✔ Latihan 3: path lain menjawab 404 "Endpoint tidak ditemukan."

OK (5 tests, 24 assertions)
```

Bila `if` membandingkan `$_SERVER['REQUEST_URI']` dengan `'/search'`, request `/search?q=php` tidak cocok, dan grader menulis:

```text
Status code untuk GET /search?q=php seharusnya 200, tapi 404.
```

## Pertanyaan 1

Dasar: Konsep 1, Konsep 2, dan Konsep 3.

```text
Method: PUT
Path: /journals/12
```

`-X PUT` mengirim method `PUT`, dan `parse_url` membuang query string `?draft=1` dari URI.

## Pertanyaan 2

Dasar: Konsep 4.

Untuk `/journals?page=3`, `$page` berisi `'3'`; untuk `/journals`, key `page` tidak ada sehingga `??` memberi `'1'`. Di kasus pertama tipenya string (`var_dump` menampilkan `string(1) "3"`), karena nilai `$_GET` selalu string.

## Pertanyaan 3

Dasar: Konsep 3.

Status `404`. Path hasil `parse_url` adalah `/search/`, dan `'/search/' === '/search'` bernilai `false` karena kedua string berbeda satu karakter. Yang dibandingkan adalah path persis, jadi request itu masuk ke blok `else`.
