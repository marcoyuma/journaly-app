# 03. Array

**Tujuan**
1. Membuat, membaca, dan menambah isi array list dan array asosiatif.
2. Mengolah setiap isi array dengan `foreach` dan menghitung jumlahnya dengan `count`.
3. Mengubah array menjadi JSON dengan `json_encode` dalam bentuk `{"data": ...}`.

**Prasyarat**: [Modul 02](./02-tipe-kondisi-fungsi.md) | **File yang Anda isi**: `backend/playground/03-array.php`, `03-latihan-1.php`, `03-latihan-2.php`, `03-latihan-3.php` (sudah dibuat kosong oleh mentor) | **Perkiraan waktu**: 60 sampai 75 menit

**Hal baru (5/5)**
1. Array list: `['a', 'b']`, indeks mulai dari 0 (`$titles[0]`), menambah dengan `$titles[] = ...`, dan tipe `array`
2. Array asosiatif: `['title' => '...']`, membaca dan mengisi `$journal['title']`
3. `foreach ($list as $item) { ... }`
4. `count($array)`
5. JSON dan `json_encode($array)`

## Pemanasan

1. Apa beda output `var_dump(3)` dan `var_dump('3')`?
2. Apa yang terjadi pada baris-baris di bawah `return` di dalam fungsi?

<details><summary>Jawaban</summary>

1. `int(3)` dibanding `string(1) "3"`: tipenya berbeda ([Modul 02 Konsep 1](./02-tipe-kondisi-fungsi.md#konsep-1-tipe-data-dan-var_dump)).
2. Tidak dijalankan, karena `return` langsung menghentikan fungsi ([Modul 02 Konsep 3](./02-tipe-kondisi-fungsi.md#konsep-3-fungsi-bertipe)).

</details>

**Mulai sekarang:** `git switch main`, lalu `git switch -c feat/03-array`.

## Kenapa modul ini ada

Di Modul 01 Latihan 3, satu jurnal butuh tiga variabel: `$title`, `$content`, `$createdAt`. Daftar sepuluh jurnal akan butuh tiga puluh variabel. Selain itu, API Journaly mengirim daftar jurnal ke frontend dalam satu teks JSON berbentuk `{"data":[...]}` ([PRD](../PRD.md) bagian 3.2). Keduanya diselesaikan oleh array: satu variabel yang menyimpan banyak nilai.

## Konsep 1: Array list

**Definisi.** Array (satu nilai yang berisi banyak nilai) ditulis di antara `[` dan `]`, dipisah koma. Array list (array yang isinya berurutan) memberi setiap isinya indeks (nomor posisi) mulai dari **0**. `$titles[0]` membaca isi pertama, dan `$titles[] = nilai;` menambah isi baru di akhir. `[]` adalah array kosong. Array yang panjang boleh ditulis beberapa baris, satu isi per baris, dan koma setelah isi terakhir boleh ada. Tipenya `array`, dan tipe ini bisa dipakai di parameter fungsi seperti `int` atau `string` (Konsep 3).

**KETIK SENDIRI** di `backend/playground/03-array.php`:

```php
<?php

declare(strict_types=1);

// 1. A list: values in order, numbered from 0
$titles = ['Hari pertama', 'Belajar array'];
$titles[] = 'Libur panjang';
echo "{$titles[0]}\n";
var_dump($titles);
```

**Prediksi**: berapa isi `$titles` sekarang, dan apa indeks `'Libur panjang'`?

**Jalankan**: `php playground/03-array.php`

```text
Hari pertama
array(3) {
  [0]=>
  string(12) "Hari pertama"
  [1]=>
  string(13) "Belajar array"
  [2]=>
  string(13) "Libur panjang"
}
```

`var_dump` menampilkan setiap isi beserta indeksnya dalam `[ ]`. `'Libur panjang'` mendapat indeks 2, yang berikutnya setelah 1.

**Kesalahan umum: indeks yang tidak ada.** Tiga isi berarti indeks 0 sampai 2. `echo "{$titles[3]}\n";` menghasilkan:

```text
Warning: Undefined array key 3 in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/03-array.php on line 8
```

Sumber: [Arrays](https://www.php.net/manual/en/language.types.array.php)

## Konsep 2: Array asosiatif

**Definisi.** Array asosiatif (array yang setiap isinya punya nama) memakai key (nama isi, biasanya string) alih-alih nomor: `['id' => 1, 'title' => 'Hari pertama']`. Tanda `=>` memasangkan key dengan nilainya. `$journal['title']` membaca nilai, dan `$journal['content'] = '...';` mengisi key baru atau mengganti nilai key yang sudah ada. Di dalam string petik dua, tulis dengan kurung kurawal: `"{$journal['title']}"`.

**KETIK SENDIRI** di akhir file:

```php

// 2. An associative array: each value has a name (key)
$journal = ['id' => 1, 'title' => 'Hari pertama'];
$journal['content'] = 'Mulai menulis.';
echo "#{$journal['id']} {$journal['title']}\n";
var_dump($journal);
```

**Jalankan**: output baru setelah bagian Konsep 1:

```text
#1 Hari pertama
array(3) {
  ["id"]=>
  int(1)
  ["title"]=>
  string(12) "Hari pertama"
  ["content"]=>
  string(14) "Mulai menulis."
}
```

**Kesalahan umum**

1. **Salah menulis key.** `{$journal['judul']}` (key yang tidak ada) menghasilkan `Warning: Undefined array key "judul" in .../03-array.php on line 14`. Key membedakan huruf besar kecil, sama seperti variabel.
2. **Lupa kurung kurawal di string.** `"#$journal['id'] ..."` membuat PHP tidak bisa membaca file:

   ```text
   Parse error: syntax error, unexpected string content "", expecting "-" or identifier or variable or number in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/03-array.php on line 14
   ```

Sumber: [Arrays: syntax](https://www.php.net/manual/en/language.types.array.php#language.types.array.syntax), [String interpolation](https://www.php.net/manual/en/language.types.string.php#language.types.string.parsing)

## Konsep 3: `foreach` dan `count`

**Definisi.**
- `foreach ($titles as $title) { ... }` menjalankan blok sekali untuk setiap isi array, berurutan. Di setiap putaran, `$title` berisi isi yang sedang dikunjungi.
- `count($titles)` mengembalikan jumlah isi array sebagai int.

**KETIK SENDIRI** di akhir file:

```php

// 3. foreach visits every item; count() tells how many there are
foreach ($titles as $title) {
    echo "- {$title}\n";
}
$total = count($titles);
echo "Total: {$total}\n";
```

**Jalankan**: output baru:

```text
- Hari pertama
- Belajar array
- Libur panjang
Total: 3
```

**Fungsi yang mengolah array.** Pola yang akan sering Anda tulis: mulai dari array kosong, kunjungi setiap isi dengan `foreach`, tambahkan hasilnya dengan `[] =`, lalu `return`. Variabel di dalam fungsi terpisah dari variabel di luar: fungsi hanya melihat parameternya dan variabel yang ia buat sendiri.

**KETIK SENDIRI** di akhir file:

```php

// 4. A function that takes a list of journals and returns a new list
function titlesOf(array $journals): array
{
    $result = [];
    foreach ($journals as $journal) {
        $result[] = $journal['title'];
    }

    return $result;
}

$journals = [$journal, ['id' => 2, 'title' => 'Belajar array', 'content' => 'Isi kedua.']];
var_dump(titlesOf($journals));
```

**Prediksi**, lalu **Jalankan**: output baru adalah `array(2)` berisi `"Hari pertama"` dan `"Belajar array"`. `$journals` adalah list yang setiap isinya array asosiatif, persis bentuk daftar jurnal di API.

**Kesalahan umum: memakai variabel dari luar fungsi.** Bila di dalam `titlesOf` Anda menulis `foreach ($titles as $journal)`, `$titles` tidak dikenal di dalam fungsi:

```text
Warning: Undefined variable $titles in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/03-array.php on line 28
Warning: foreach() argument must be of type array|object, null given in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/03-array.php on line 28
```

Sumber: [foreach](https://www.php.net/manual/en/control-structures.foreach.php), [count](https://www.php.net/manual/en/function.count.php), [Variable scope](https://www.php.net/manual/en/language.variables.scope.php)

## Konsep 4: JSON dan `json_encode`

**Definisi.** JSON (JavaScript Object Notation: format teks untuk bertukar data antarprogram) adalah bahasa yang dipakai frontend Journaly untuk membaca jawaban backend. `json_encode($array)` mengubah array PHP menjadi string JSON:

| Array PHP | Menjadi JSON |
|---|---|
| list `['a', 'b']` | array JSON `["a","b"]` |
| asosiatif `['id' => 1]` | objek JSON `{"id":1}` |
| int, string, bool, null | `1`, `"teks"`, `true`, `null` |

**KETIK SENDIRI** di akhir file:

```php

// 5. json_encode turns an array into JSON text
echo json_encode($titles);
echo "\n";
echo json_encode(['data' => $journals]);
echo "\n";
```

**Prediksi**, lalu **Jalankan**: dua baris terakhir output:

```text
["Hari pertama","Belajar array","Libur panjang"]
{"data":[{"id":1,"title":"Hari pertama","content":"Mulai menulis."},{"id":2,"title":"Belajar array","content":"Isi kedua."}]}
```

Baris kedua adalah bentuk response `GET /journals` di [PRD](../PRD.md) bagian 3.2: objek dengan key `data`, berisi array jurnal.

**Kesalahan umum: `echo` sebuah array.** `echo $journals;` tidak menghasilkan JSON, melainkan warning dan kata `Array`:

```text
Warning: Array to string conversion in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/03-array.php on line 43
Array
```

<details><summary>Kenapa <code>/</code> dan emoji terlihat aneh?</summary>

Tanpa pengaturan tambahan, `json_encode` menulis `/` sebagai `\/` dan karakter di luar ASCII sebagai kode: `['url' => 'a/b', 'emoji' => 'kopi ☕']` menjadi `{"url":"a\/b","emoji":"kopi ☕"}`. JSON itu tetap sah dan terbaca benar oleh frontend. Modul 06 merapikannya dengan flag `json_encode`.

</details>

Sumber: [json_encode](https://www.php.net/manual/en/function.json-encode.php), [RFC 8259: JSON](https://www.rfc-editor.org/rfc/rfc8259)

## Latihan

### Latihan 1: Satu jurnal dalam satu array (Tujuan: #1)

- **Soal**: Simpan satu jurnal di satu array, tambahkan tanggal dibuatnya, lalu tampilkan.
- **Kontrak**: variabel `$journal` berisi `id` 7, `title` `Belajar array`, dan `content` `Array menyimpan banyak nilai.`; key `created_at` berisi `2026-09-25` ditambahkan di baris terpisah. Setiap nilai hanya ditulis sekali di file. Output persis:

  ```text
  #7 Belajar array
  Array menyimpan banyak nilai.
  Dibuat: 2026-09-25
  ```

- **File**: `backend/playground/03-latihan-1.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: store id 7, title 'Belajar array', content 'Array menyimpan banyak nilai.' in $journal
  // TODO 2: add the key created_at with '2026-09-25' on its own line
  // TODO 3: print "#<id> <title>", the content, and "Dibuat: <created_at>", one per line
  ```

- **Jalankan**: `php playground/03-latihan-1.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 2 (array asosiatif) dan cara menulisnya di string petik dua.</details>
  <details><summary>Petunjuk 2</summary><code>$journal['created_at'] = '2026-09-25';</code> menambah key baru.</details>
  <details><summary>Petunjuk 3</summary><code>echo "#{$journal['id']} {$journal['title']}\n";</code></details>
- **Periksa**: `composer grade 03` (bagian Latihan 1 harus `✔`)
- **Jawaban**: [solution.md#latihan-1](../solutions/03-array/solution.md#latihan-1)

### Latihan 2: Daftar jurnal dan totalnya (Tujuan: #2)

- **Soal**: Halaman daftar menampilkan setiap jurnal dengan nomornya, lalu jumlah jurnalnya.
- **Kontrak**: variabel `$journals` berisi list tiga jurnal; setiap jurnal adalah array asosiatif dengan `id` dan `title`: (1, `Hari pertama`), (2, `Belajar array`), (3, `Libur panjang`). Setiap judul hanya ditulis sekali di file. Baris jurnal dicetak dengan `foreach`, dan total dihitung dengan `count()`. Output persis:

  ```text
  1. Hari pertama
  2. Belajar array
  3. Libur panjang
  Total: 3 jurnal
  ```

- **File**: `backend/playground/03-latihan-2.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: store the three journals in $journals (a list of associative arrays)
  // TODO 2: print "<id>. <title>" for every journal with foreach
  // TODO 3: print "Total: <count> jurnal" using count()
  ```

- **Jalankan**: `php playground/03-latihan-2.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3. Bentuk <code>$journals</code> sama dengan contoh <code>titlesOf</code>.</details>
  <details><summary>Petunjuk 2</summary>Di dalam <code>foreach ($journals as $journal)</code>, setiap putaran punya <code>$journal['id']</code> dan <code>$journal['title']</code>.</details>
  <details><summary>Petunjuk 3</summary><code>$total = count($journals);</code> lalu <code>echo "Total: {$total} jurnal\n";</code></details>
- **Periksa**: `composer grade 03` (bagian Latihan 2 harus `✔`)
- **Jawaban**: [solution.md#latihan-2](../solutions/03-array/solution.md#latihan-2)

### Latihan 3: Response daftar jurnal tanpa `user_id` (mandiri) (Tujuan: #3)

- **Soal**: Data jurnal yang tersimpan menyertakan `user_id` pemiliknya, tapi API tidak pernah mengirimkannya ([PRD](../PRD.md) bagian 3.2). Buat fungsi yang membuang `user_id`, lalu cetak response-nya.
- **Kontrak**: fungsi `publicJournals(array $journals): array` mengembalikan list baru; setiap jurnal hanya berisi `id`, `title`, dan `content`, dalam urutan itu. List kosong menghasilkan list kosong. Grader juga memanggil fungsi Anda dengan data lain. Skrip memakai dua jurnal berikut (keduanya `user_id` 3): (1, `Hari pertama`, `Mulai menulis.`) dan (2, `Belajar array`, `Array menyimpan banyak nilai.`), lalu mencetak `json_encode(['data' => publicJournals($journals)])` diikuti baris baru. Output persis (satu baris):

  ```text
  {"data":[{"id":1,"title":"Hari pertama","content":"Mulai menulis."},{"id":2,"title":"Belajar array","content":"Array menyimpan banyak nilai."}]}
  ```

- **File**: `backend/playground/03-latihan-3.php` (tanpa kerangka).
- **Jalankan**: `php playground/03-latihan-3.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3 (pola fungsi <code>titlesOf</code>) dan Konsep 4 (<code>json_encode</code>).</details>
  <details><summary>Petunjuk 2</summary>Yang ditambahkan ke <code>$result</code> di setiap putaran adalah array asosiatif baru, bukan satu string.</details>
  <details><summary>Petunjuk 3</summary><code>$result[] = ['id' => $journal['id'], 'title' => $journal['title'], 'content' => $journal['content']];</code></details>
- **Periksa**: `composer grade 03` (semua `✔`, diakhiri `OK (7 tests, 14 assertions)`)
- **Jawaban**: [solution.md#latihan-3](../solutions/03-array/solution.md#latihan-3)

## Ringkasan

1. Array list memberi isinya indeks mulai dari 0; `$list[] = nilai;` menambah di akhir.
2. Array asosiatif memasangkan key dengan nilai (`'title' => '...'`); di string petik dua ditulis `{$journal['title']}`.
3. `foreach` mengunjungi setiap isi berurutan; `count` mengembalikan jumlahnya.
4. Fungsi yang mengolah array: mulai dari `[]`, `foreach`, tambah dengan `[] =`, lalu `return`.
5. `json_encode` mengubah list menjadi array JSON dan array asosiatif menjadi objek JSON.

## Utang belajar

Tidak ada.

## Simpan pekerjaan

Setelah `composer grade 03` hijau:

```bash
git status
git add playground/03-array.php playground/03-latihan-1.php playground/03-latihan-2.php playground/03-latihan-3.php
git status
git commit -m "feat(playground): add module 03 exercises"
git switch main
git merge feat/03-array
git branch -d feat/03-array
```

Peringatan dari [Modul 00 Konsep 4](./00-persiapan.md#konsep-4-commit-dan-branch-fitur) tetap berlaku:
- Jangan pindah branch saat masih ada perubahan yang belum di-commit.
- Baca `git status` sebelum `git add`, dan sebut file satu per satu. Jangan `git add -A` atau `git add .`.
- Hapus branch dengan `-d`, bukan `-D`.
- Bila `git merge` menulis `CONFLICT`, jalankan `git merge --abort` lalu tanya mentor.
- Jangan pernah `git reset --hard` atau `git push --force`.
- Branch fitur hanya mengubah `backend/`. `git push` opsional dan keputusan Anda.

## Di Laravel

Route Laravel yang mengembalikan array otomatis dikirim sebagai JSON, jadi `json_encode` terjadi di balik layar. Laravel juga menyediakan Collection lewat `collect($journals)`: pembungkus array yang punya banyak operasi siap pakai, termasuk `count()` dan cara membuat list baru dari list lama seperti `titlesOf`.

## Pertanyaan pengecekan

1. Apa output persis dari kode berikut? (Tujuan #1) [Jawaban](../solutions/03-array/solution.md#pertanyaan-1)

   ```php
   $journal = ['id' => 4, 'title' => 'Libur'];
   $journal['title'] = 'Mudik';
   echo "#{$journal['id']} {$journal['title']}\n";
   ```

2. Apa output persis dari kode berikut? (Tujuan #2) [Jawaban](../solutions/03-array/solution.md#pertanyaan-2)

   ```php
   $tags = ['php', 'sql'];
   foreach ($tags as $tag) {
       echo "#{$tag} ";
   }
   $total = count($tags);
   echo "({$total})\n";
   ```

3. Apa output `json_encode(['data' => ['Hari pertama', 'Libur']])` dan `json_encode(['data' => ['title' => 'Libur']])`? Kenapa isi `data` di keduanya berbeda bentuk? (Tujuan #3) [Jawaban](../solutions/03-array/solution.md#pertanyaan-3)
