# Solution 03. Array

Soal ada di [Modul 03](../../modules/03-array.md). Kerjakan sendiri dulu. Buka file ini setelah `composer grade 03` hijau, atau setelah ketiga petunjuk tidak cukup.

## Latihan 1

`backend/playground/03-latihan-1.php`:

```php
<?php

declare(strict_types=1);

// 1. Each value is written once, under its key
$journal = ['id' => 7, 'title' => 'Belajar array', 'content' => 'Array menyimpan banyak nilai.'];

// 2. Add one more key after the array is created
$journal['created_at'] = '2026-09-25';

// 3. Read the values by key
echo "#{$journal['id']} {$journal['title']}\n";
echo "{$journal['content']}\n";
echo "Dibuat: {$journal['created_at']}\n";
```

`php playground/03-latihan-1.php`:

```text
#7 Belajar array
Array menyimpan banyak nilai.
Dibuat: 2026-09-25
```

## Latihan 2

`backend/playground/03-latihan-2.php`:

```php
<?php

declare(strict_types=1);

// 1. A list of journals; each journal is an associative array
$journals = [
    ['id' => 1, 'title' => 'Hari pertama'],
    ['id' => 2, 'title' => 'Belajar array'],
    ['id' => 3, 'title' => 'Libur panjang'],
];

// 2. One line per journal
foreach ($journals as $journal) {
    echo "{$journal['id']}. {$journal['title']}\n";
}

// 3. The total comes from the list itself
$total = count($journals);
echo "Total: {$total} jurnal\n";
```

`php playground/03-latihan-2.php`:

```text
1. Hari pertama
2. Belajar array
3. Libur panjang
Total: 3 jurnal
```

Bila total ditulis `$total = 3;`, output tetap benar, tapi grader menolak:

```text
Kontrak meminta pemanggilan count(). Jumlah jurnal dihitung dari $journals, bukan ditulis manual.
```

Alasannya: begitu jurnal keempat ditambahkan, angka `3` yang ditulis manual menjadi salah.

## Latihan 3

`backend/playground/03-latihan-3.php`:

```php
<?php

declare(strict_types=1);

// 1. Build a new list that keeps only the public keys (no user_id)
function publicJournals(array $journals): array
{
    $result = [];
    foreach ($journals as $journal) {
        $result[] = ['id' => $journal['id'], 'title' => $journal['title'], 'content' => $journal['content']];
    }

    return $result;
}

// 2. The data as it might come from storage
$journals = [
    ['id' => 1, 'user_id' => 3, 'title' => 'Hari pertama', 'content' => 'Mulai menulis.'],
    ['id' => 2, 'user_id' => 3, 'title' => 'Belajar array', 'content' => 'Array menyimpan banyak nilai.'],
];

// 3. Wrap the list in "data", like every Journaly API response
echo json_encode(['data' => publicJournals($journals)]);
echo "\n";
```

Output `composer grade 03` (hasil eksekusi, baris kepala PHPUnit dipotong):

```text
Latihan1 (Grader\Modul03\Latihan1)
 ✔ Latihan 1: memakai array $journal dan setiap nilai ditulis sekali
 ✔ Latihan 1: mencetak jurnal persis sesuai kontrak

Latihan2 (Grader\Modul03\Latihan2)
 ✔ Latihan 2: memakai $journals, foreach, dan count()
 ✔ Latihan 2: mencetak daftar jurnal dan totalnya persis sesuai kontrak

Latihan3 (Grader\Modul03\Latihan3)
 ✔ Latihan 3: publicJournals membuang user_id dan menjaga urutan id, title, content
 ✔ Latihan 3: publicJournals dengan list kosong mengembalikan list kosong
 ✔ Latihan 3: mencetak JSON {"data": [...]} persis sesuai kontrak

OK (7 tests, 14 assertions)
```

Urutan key ikut menentukan JSON. Bila array baru ditulis `['title' => ..., 'id' => ..., ...]`, grader menunjukkan perbedaannya (dipotong):

```text
-'{"data":[{"id":1,"title":"Hari pertama","content":"Mulai menulis."},...
+'{"data":[{"title":"Hari pertama","id":1,"content":"Mulai menulis."},...
```

## Pertanyaan 1

Dasar: Konsep 2.

```text
#4 Mudik
```

Mengisi key yang sudah ada (`title`) mengganti nilainya, jadi `Libur` tidak ada lagi.

## Pertanyaan 2

Dasar: Konsep 3.

```text
#php #sql (2)
```

`foreach` menjalankan `echo` sekali untuk setiap isi, berurutan, tanpa baris baru di antaranya. `count($tags)` mengembalikan 2.

## Pertanyaan 3

Dasar: Konsep 4.

```text
{"data":["Hari pertama","Libur"]}
{"data":{"title":"Libur"}}
```

Isi `data` yang pertama adalah list, sehingga menjadi array JSON (`[...]`). Yang kedua adalah array asosiatif, sehingga menjadi objek JSON (`{...}`).
