# Solution 01. Program PHP pertama

Soal ada di [Modul 01](../../modules/01-program-php-pertama.md). Kerjakan sendiri dulu. Buka file ini setelah `composer grade 01` hijau, atau setelah ketiga petunjuk tidak cukup.

## Latihan 1

`backend/playground/01-latihan-1.php`:

```php
<?php

// 1. Print the app name followed by a new line
echo "Journaly\n";

// 2. Print the tagline followed by a new line
echo "Catatan harian pribadi\n";
```

`php playground/01-latihan-1.php`:

```text
Journaly
Catatan harian pribadi
```

Kesalahan yang paling sering: memakai petik satu (`'Journaly\n'`). Outputnya menjadi `Journaly\nCatatan harian pribadi\n` dalam satu baris, dan grader menampilkan perbedaan Expected dan Actual.

## Latihan 2

`backend/playground/01-latihan-2.php`:

```php
<?php

// 1. Store the title and the author
$title = 'Hari pertama belajar PHP';
$author = 'Sari';

// 2. Print them with double quotes (interpolation)
echo "Judul: {$title}\n";
echo "Penulis: {$author}\n";
```

`php playground/01-latihan-2.php`:

```text
Judul: Hari pertama belajar PHP
Penulis: Sari
```

Bila Anda menulis `{$Author}` (huruf A besar), grader menulis:

```text
PHP menampilkan warning atau error saat menjalankan playground/01-latihan-2.php:
Warning: Undefined variable $Author in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/01-latihan-2.php on line 9
```

## Latihan 3

`backend/playground/01-latihan-3.php`:

```php
<?php

// 1. Each value is written once, in its variable
$title = 'Belajar variabel';
$content = 'Hari ini saya belajar echo dan variabel.';
$createdAt = '2026-09-24';

// 2. The same variable can be printed as many times as needed
echo "Judul: {$title}\n";
echo "Isi: {$content}\n";
echo "({$title}, {$createdAt})\n";
```

Output `composer grade 01` (hasil eksekusi, baris kepala PHPUnit dipotong):

```text
Latihan1 (Grader\Modul01\Latihan1)
 ✔ Latihan 1: mencetak nama aplikasi dan tagline, masing-masing satu baris

Latihan2 (Grader\Modul01\Latihan2)
 ✔ Latihan 2: memakai variabel $title dan $author
 ✔ Latihan 2: mencetak judul dan penulis persis sesuai kontrak

Latihan3 (Grader\Modul01\Latihan3)
 ✔ Latihan 3: memakai variabel $title, $content, dan $createdAt
 ✔ Latihan 3: setiap nilai ditulis sekali saja, di variabelnya
 ✔ Latihan 3: mencetak pratinjau jurnal persis sesuai kontrak

OK (6 tests, 11 assertions)
```

Bila baris ketiga ditulis `echo "(Belajar variabel, {$createdAt})\n";`, outputnya tetap benar, tapi grader menolak:

```text
Teks "Belajar variabel" harus muncul tepat satu kali di file. Nilai ditulis sekali di variabel, lalu dicetak lewat interpolasi.
```

Alasannya: bila judul diganti, judul yang ditulis ulang di baris ketiga akan tertinggal. Inilah masalah di awal Konsep 2.

## Pertanyaan 1

Dasar: Konsep 3.

```text
Judul: {$judul}\n
```

Petik satu mencetak semuanya apa adanya: `{$judul}` tidak diganti dan `\n` tercetak sebagai dua karakter, bukan baris baru. Karena tidak ada baris baru, zsh menampilkan `%` setelahnya.

## Pertanyaan 2

Dasar: Konsep 2.

Nama variabel membedakan huruf besar dan kecil. Yang diisi adalah `$appName`, sedangkan yang dicetak `$appname`, dan variabel itu belum pernah diisi. Hasilnya warning, lalu satu baris kosong (dari `\n`):

```text
PHP Warning:  Undefined variable $appname in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/coba.php on line 4

Warning: Undefined variable $appname in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/coba.php on line 4

```

## Pertanyaan 3

Dasar: Konsep 1.

Seluruh isi file dicetak apa adanya, termasuk kata `echo` dan tanda petiknya, karena tanpa `<?php` PHP menganggap semuanya teks biasa, bukan kode.
