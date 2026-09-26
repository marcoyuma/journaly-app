# Solution 02. Tipe, kondisi, dan fungsi

Soal ada di [Modul 02](../../modules/02-tipe-kondisi-fungsi.md). Kerjakan sendiri dulu. Buka file ini setelah `composer grade 02` hijau, atau setelah ketiga petunjuk tidak cukup.

## Latihan 1

`backend/playground/02-latihan-1.php`:

```php
<?php

declare(strict_types=1);

// 1. 0 has its own label; every other number is printed with "jurnal"
function countLabel(int $count): string
{
    if ($count === 0) {
        return 'Belum ada jurnal';
    }

    return "{$count} jurnal";
}

// 2. Store each result in a variable, then print it
$label = countLabel(0);
echo "{$label}\n";
$label = countLabel(3);
echo "{$label}\n";
```

`php playground/02-latihan-1.php`:

```text
Belum ada jurnal
3 jurnal
```

Bila Anda menulis `return "3 jurnal";`, output skrip tetap benar, tapi grader memanggil `countLabel(1)` dan menolak:

```text
countLabel(1) belum mengembalikan "1 jurnal". Pakai parameter $count, jangan menulis angkanya langsung.
```

## Latihan 2

`backend/playground/02-latihan-2.php`:

```php
<?php

declare(strict_types=1);

// 1. Only null means "not logged in"; an empty string is still a username
function greeting(?string $username): string
{
    if ($username === null) {
        return 'Silakan login terlebih dahulu.';
    }

    return "Halo, {$username}!";
}

// 2. Print both cases
$text = greeting(null);
echo "{$text}\n";
$text = greeting('sari');
echo "{$text}\n";
```

`php playground/02-latihan-2.php`:

```text
Silakan login terlebih dahulu.
Halo, sari!
```

Kontraknya sengaja membedakan `null` dan `''`. `=== null` hanya `true` untuk `null`, karena `''` bertipe string.

## Latihan 3

`backend/playground/02-latihan-3.php`:

```php
<?php

declare(strict_types=1);

// 1. A guest (null) can never edit; otherwise the two ids must be equal
function canEdit(int $journalUserId, ?int $currentUserId): bool
{
    if ($currentUserId === null) {
        return false;
    }

    return $journalUserId === $currentUserId;
}

// 2. var_dump shows the bool itself (echo would print 1 or nothing)
var_dump(canEdit(1, null));
var_dump(canEdit(1, 1));
var_dump(canEdit(1, 2));
```

Output `composer grade 02` (hasil eksekusi, baris kepala PHPUnit dipotong):

```text
Latihan1 (Grader\Modul02\Latihan1)
 ✔ Latihan 1: countLabel(0) mengembalikan "Belum ada jurnal"
 ✔ Latihan 1: countLabel dengan angka lain mengembalikan "<angka> jurnal"
 ✔ Latihan 1: mencetak hasil countLabel(0) dan countLabel(3)

Latihan2 (Grader\Modul02\Latihan2)
 ✔ Latihan 2: greeting(null) meminta login
 ✔ Latihan 2: greeting dengan username menyapa username itu, termasuk string kosong
 ✔ Latihan 2: mencetak sapaan untuk null dan untuk sari

Latihan3 (Grader\Modul02\Latihan3)
 ✔ Latihan 3: file diawali declare(strict_types=1)
 ✔ Latihan 3: canEdit mengembalikan bool sesuai kontrak
 ✔ Latihan 3: parameter pertama bertipe int sehingga string memicu TypeError
 ✔ Latihan 3: mencetak tiga hasil canEdit dengan var_dump

OK (10 tests, 15 assertions)
```

Bila parameter ditulis tanpa tipe (`function canEdit($journalUserId, ...)`), `canEdit('7', 7)` tidak lagi ditolak, dan grader menulis:

```text
canEdit('7', 7) seharusnya berhenti dengan TypeError, tapi fungsi itu mengembalikan nilai. Tulis tipe int pada parameter $journalUserId.
```

## Pertanyaan 1

Dasar: Konsep 1 dan Konsep 2.

```text
bool(false)
string(4) "Sari"
```

`'0'` bertipe string dan `0` bertipe int. `===` membandingkan nilai dan tipe, jadi hasilnya `false`. `var_dump('Sari')` menampilkan tipe `string`, panjangnya 4 karakter, lalu isinya.

## Pertanyaan 2

Dasar: Konsep 3.

Fungsi mengembalikan `Tanpa judul`. Kondisi `$title === ''` bernilai `true`, sehingga `return 'Tanpa judul';` dijalankan. `return` mengirim hasil dan langsung menghentikan fungsi, jadi `return $title;` tidak pernah dicapai.

## Pertanyaan 3

Dasar: Konsep 4.

Skrip berhenti dengan `TypeError`, karena `'5'` bertipe string sedangkan parameter `$count` meminta int, dan mode ketat tidak mengubahnya diam-diam. Contoh pesannya dari file `playground/q.php` yang memanggil `countLabel('5')` di baris 20 (dipotong):

```text
Fatal error: Uncaught TypeError: countLabel(): Argument #1 ($count) must be of type int, string given, called in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/q.php on line 20 and defined in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/q.php:6
```

Bagian `called in ... on line 20` menunjuk baris pemanggil, yaitu baris yang harus diperbaiki (misalnya menjadi `countLabel(5)`).
