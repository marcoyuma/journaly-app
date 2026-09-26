# 02. Tipe, kondisi, dan fungsi

**Tujuan**
1. Membaca tipe sebuah nilai dengan `var_dump` dan memprediksi hasil `===`.
2. Menulis fungsi bertipe yang memilih nilai kembaliannya dengan `if`.
3. Memicu dan membaca `TypeError` di file yang diawali `declare(strict_types=1)`.

**Prasyarat**: [Modul 01](./01-program-php-pertama.md) | **File yang Anda isi**: `backend/playground/02-tipe.php`, `02-latihan-1.php`, `02-latihan-2.php`, `02-latihan-3.php` (sudah dibuat kosong oleh mentor) | **Perkiraan waktu**: 60 sampai 75 menit

**Hal baru (5/5)**
1. Tipe `int`, `string`, `bool` (`true` dan `false`), dan `null`
2. `var_dump(nilai)`: mencetak nilai beserta tipenya
3. `===` dan `if`/`else`
4. Fungsi bertipe: `function nama(string $x): bool { return ...; }`, termasuk `?` yang berarti "atau null"
5. `declare(strict_types=1)` dan `TypeError`

## Pemanasan

1. Apa output persis dari `echo 'Total: {$count}\n';`?
2. Kenapa grader Modul 01 Latihan 3 menolak judul yang ditulis dua kali di file?

<details><summary>Jawaban</summary>

1. `Total: {$count}\n` apa adanya, karena petik satu tidak mengganti variabel maupun `\n` ([Modul 01 Konsep 3](./01-program-php-pertama.md#konsep-3-string-petik-dua-dan-petik-satu)).
2. Nilai ditulis sekali di variabel, supaya bila berubah cukup diganti di satu tempat ([Modul 01 Konsep 2](./01-program-php-pertama.md#konsep-2-variabel)).

</details>

**Mulai sekarang:** pastikan Modul 01 sudah di-merge ke `main`, lalu `git switch main` dan `git switch -c feat/02-tipe-kondisi-fungsi`.

## Kenapa modul ini ada

Frontend Journaly menampilkan "Belum ada jurnal" bila daftar kosong, dan backend harus menolak orang yang belum login. Keduanya adalah **keputusan**: bila begini, lakukan itu. Keputusan butuh perbandingan, dan perbandingan butuh tahu **tipe** nilai, karena angka `3` dan teks `'3'` tidak sama. Keputusan yang dipakai berulang dibungkus dalam **fungsi**. Semua fungsi Journaly nanti, misalnya validasi judul di Modul 10, memakai pola dari modul ini.

## Konsep 1: Tipe data dan `var_dump`

**Masalah.** `echo` hanya menampilkan teks. Dari output `3` Anda tidak bisa tahu apakah nilainya angka atau string.

**Definisi.** Tipe (jenis sebuah nilai) menentukan apa yang bisa dilakukan dengan nilai itu. Empat tipe pertama:

| Tipe | Artinya | Contoh |
|---|---|---|
| `int` | bilangan bulat (integer) | `3`, `0`, `2026`, ditulis tanpa petik |
| `string` | teks | `'3'`, `'Sari'` |
| `bool` | benar atau salah (boolean) | `true`, `false` |
| `null` | tidak ada nilai | `null` |

`var_dump(nilai)` mencetak tipe dan isi sebuah nilai, lalu baris baru. Inilah alat utama untuk memeriksa nilai.

**KETIK SENDIRI** di `backend/playground/02-tipe.php`:

```php
<?php

// 1. Four types of values
var_dump(3);
var_dump('3');
var_dump(true);
var_dump(null);
```

**Prediksi**: baris mana yang membedakan `3` dan `'3'`?

**Jalankan**: `php playground/02-tipe.php`

```text
int(3)
string(1) "3"
bool(true)
NULL
```

`string(1) "3"` artinya string sepanjang 1 karakter. `null` tidak punya isi, jadi hanya `NULL` yang tercetak.

**Kesalahan umum: memeriksa bool dengan `echo`.** `echo true;` mencetak `1`, dan `echo false;` tidak mencetak apa pun. Untuk melihat bool, selalu pakai `var_dump`.

Sumber: [Types](https://www.php.net/manual/en/language.types.php), [var_dump](https://www.php.net/manual/en/function.var-dump.php)

## Konsep 2: `===` dan `if`/`else`

**Definisi.**
- `===` (identik) membandingkan dua nilai. Hasilnya `true` bila nilai **dan** tipenya sama, selain itu `false`. Jadi hasil `===` adalah sebuah bool.
- `if (kondisi) { ... } else { ... }` menjalankan blok pertama bila kondisinya `true`, dan blok `else` bila `false`. Blok (baris-baris di antara `{` dan `}`) boleh berisi banyak perintah. Bagian `else` boleh tidak ditulis.

**KETIK SENDIRI** di bawah `var_dump(null);`:

```php

// 2. === compares value and type, the result is a bool
var_dump(3 === 3);
var_dump(3 === '3');

// 3. if/else runs one of two blocks
$title = '';
if ($title === '') {
    echo "Tanpa judul\n";
} else {
    echo "{$title}\n";
}
```

**Prediksi** tiga baris terakhir output.

**Jalankan**: `php playground/02-tipe.php` (empat baris pertama sama seperti tadi)

```text
bool(true)
bool(false)
Tanpa judul
```

`3 === '3'` bernilai `false` karena tipenya berbeda: `int` dan `string`. Coba ganti `$title = '';` menjadi `$title = 'Hari pertama';`, jalankan, lalu kembalikan.

**Kesalahan umum: satu `=` di dalam `if`.** Satu `=` berarti mengisi variabel, bukan membandingkan. Dengan `if ($title = '')`, PHP tidak menampilkan error apa pun, tapi yang berjalan justru blok `else`, sehingga yang tercetak baris kosong, bukan `Tanpa judul`. Di dalam `if`, selalu `===`.

Sumber: [Comparison operators](https://www.php.net/manual/en/language.operators.comparison.php), [if](https://www.php.net/manual/en/control-structures.if.php), [else](https://www.php.net/manual/en/control-structures.else.php)

## Konsep 3: Fungsi bertipe

**Masalah.** Pengecekan "judul kosong?" akan dipakai di banyak tempat. Menyalin `if` yang sama berkali-kali punya masalah yang sama dengan menyalin nilai di Modul 01.

**Definisi.** Fungsi (kumpulan perintah bernama yang bisa dipanggil berulang):

```text
function isEmptyTitle(string $title): bool
         nama         tipe   parameter  tipe hasil
```

- Parameter (variabel yang nilainya diberikan oleh pemanggil) ditulis dengan tipenya di depan.
- `: bool` setelah tanda kurung adalah tipe nilai kembalian (hasil yang dikirim fungsi ke pemanggil).
- `return nilai;` mengirim hasil itu dan **langsung menghentikan fungsi**. Baris di bawahnya tidak dijalankan.
- Pemanggilan `isEmptyTitle('')` menjalankan fungsi dengan `$title` berisi `''`. Hasilnya bisa dicetak, disimpan, atau dibandingkan.

**KETIK SENDIRI** di akhir file:

```php

// 4. A typed function: a string goes in, a bool comes out
function isEmptyTitle(string $title): bool
{
    return $title === '';
}

var_dump(isEmptyTitle(''));
var_dump(isEmptyTitle('Hari pertama'));
```

**Jalankan**: dua baris terakhir output adalah `bool(true)` lalu `bool(false)`.

**Dua parameter dan `?`.** Parameter dipisah koma. Tanda `?` di depan tipe berarti "tipe itu atau `null`": `?string` menerima string atau `null`, `?int` menerima int atau `null`.

**KETIK SENDIRI** di akhir file:

```php

// 5. ?string accepts a string or null; parameters are separated by commas
function authorLabel(?string $author, string $fallback): string
{
    if ($author === null) {
        return $fallback;
    }

    return $author;
}

$label = authorLabel(null, 'Anonim');
echo "{$label}\n";
$label = authorLabel('Sari', 'Anonim');
echo "{$label}\n";
```

**Prediksi**, lalu **Jalankan**: dua baris terakhir output adalah `Anonim` lalu `Sari`. Bila `$author` berisi `null`, `return $fallback;` menghentikan fungsi, sehingga `return $author;` tidak pernah dicapai. Hasil fungsi disimpan dulu di `$label` karena interpolasi `{$...}` hanya bisa berisi variabel, bukan pemanggilan fungsi.

**Kesalahan umum: lupa `return`.** Bila isi `isEmptyTitle` ditulis `$title === '';` tanpa `return`, fungsi selesai tanpa hasil padahal berjanji mengembalikan `bool` (baris yang sama juga dicetak dengan awalan `PHP`):

```text
Fatal error: Uncaught TypeError: isEmptyTitle(): Return value must be of type bool, none returned in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/02-tipe.php:25
```

Sumber: [User-defined functions](https://www.php.net/manual/en/functions.user-defined.php), [Returning values](https://www.php.net/manual/en/functions.returning-values.php), [Type declarations](https://www.php.net/manual/en/language.types.declarations.php)

## Konsep 4: `declare(strict_types=1)` dan `TypeError`

**Masalah.** **KETIK SENDIRI** di akhir file, sebuah pemanggilan yang sengaja salah tipe:

```php

// 6. Wrong type on purpose: an int where ?string is expected
$label = authorLabel(2026, 'Anonim');
echo "{$label}\n";
```

**Jalankan**: baris terakhir output adalah `2026`. PHP diam-diam mengubah int `2026` menjadi string `'2026'`. Nanti data dari request (Modul 09) bisa berisi angka di tempat yang seharusnya teks, dan perubahan diam-diam seperti ini menyembunyikan kesalahan.

**Definisi.** `declare(strict_types=1);` (mode tipe ketat) membuat PHP menolak nilai yang tipenya tidak cocok dengan parameter, bukan mengubahnya diam-diam. Penolakannya berupa `TypeError` (error karena tipe tidak cocok) yang menghentikan skrip. Baris ini harus menjadi perintah pertama di file, tepat di bawah `<?php`. **Mulai modul ini, setiap file PHP yang Anda tulis diawali baris ini.**

**KETIK SENDIRI** tepat di bawah `<?php` (baris 1), lalu satu baris kosong:

```php

declare(strict_types=1);
```

**Prediksi**: apakah `2026` masih tercetak?

**Jalankan**: `php playground/02-tipe.php`. Output lama tetap muncul, lalu (dipotong, dan dicetak dua kali seperti di Modul 01):

```text
PHP Fatal error:  Uncaught TypeError: authorLabel(): Argument #1 ($author) must be of type ?string, int given, called in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/02-tipe.php on line 48 and defined in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/02-tipe.php:33
Stack trace:
#0 /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/02-tipe.php(48): authorLabel(2026, 'Anonim')
#1 {main}
```

Cara membacanya:
- `authorLabel(): Argument #1 ($author)`: parameter pertama fungsi `authorLabel`.
- `must be of type ?string, int given`: yang diminta `?string`, yang diberikan `int`.
- `called in ... on line 48`: baris pemanggilnya. Di situlah biasanya yang perlu diperbaiki.
- `Stack trace` (daftar pemanggilan yang berujung ke error) boleh diabaikan untuk sekarang.

Setelah membaca error itu, **hapus** komentar `// 6.` beserta dua baris di bawahnya, lalu jalankan lagi sampai tidak ada error.

**Kesalahan umum: `declare` bukan perintah pertama.** Bila `declare(strict_types=1);` ditulis di bawah `echo`, PHP menolak seluruh file: `Fatal error: strict_types declaration must be the very first statement in the script`.

<details><summary>File mana yang terkena aturan ketat?</summary>

Aturan ketat berlaku untuk pemanggilan fungsi yang ditulis di file yang memuat `declare` itu. File lain tanpa `declare` tetap memakai perubahan diam-diam. Karena itu setiap file butuh barisnya sendiri.

</details>

Sumber: [Strict typing](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict), [declare](https://www.php.net/manual/en/control-structures.declare.php), [TypeError](https://www.php.net/manual/en/class.typeerror.php)

## Latihan

### Latihan 1: Label jumlah jurnal (Tujuan: #2)

- **Soal**: Halaman daftar menampilkan jumlah jurnal. Bila jumlahnya 0, tampilkan "Belum ada jurnal".
- **Kontrak**: fungsi `countLabel(int $count): string`. `countLabel(0)` mengembalikan `Belum ada jurnal`; angka lain mengembalikan angka itu diikuti ` jurnal` (`countLabel(12)` mengembalikan `12 jurnal`). Grader juga memanggil fungsi Anda dengan angka lain. Output skrip persis:

  ```text
  Belum ada jurnal
  3 jurnal
  ```

- **File**: `backend/playground/02-latihan-1.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: write countLabel(int $count): string
  //         0 returns 'Belum ada jurnal', any other number returns "<count> jurnal"
  // TODO 2: print countLabel(0) followed by a new line
  // TODO 3: print countLabel(3) followed by a new line
  ```

- **Jalankan**: `php playground/02-latihan-1.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3, fungsi <code>authorLabel</code>: <code>if</code> dengan <code>return</code> di dalamnya, lalu <code>return</code> lain di bawahnya.</details>
  <details><summary>Petunjuk 2</summary>Kondisinya <code>$count === 0</code>. Untuk angka lain, pakai interpolasi <code>"{$count} jurnal"</code>.</details>
  <details><summary>Petunjuk 3</summary><code>$label = countLabel(0);</code> lalu <code>echo "{$label}\n";</code>, dan ulangi untuk 3.</details>
- **Periksa**: `composer grade 02` (bagian Latihan 1 harus `✔`)
- **Jawaban**: [solution.md#latihan-1](../solutions/02-tipe-kondisi-fungsi/solution.md#latihan-1)

### Latihan 2: Sapaan untuk tamu dan pengguna (Tujuan: #2)

- **Soal**: Journaly menyapa pengguna yang login dengan namanya. Bila belum ada yang login, username-nya `null`.
- **Kontrak**: fungsi `greeting(?string $username): string`. `greeting(null)` mengembalikan `Silakan login terlebih dahulu.`; selain `null` (termasuk string kosong) mengembalikan `Halo, <username>!`. Output skrip persis:

  ```text
  Silakan login terlebih dahulu.
  Halo, sari!
  ```

- **File**: `backend/playground/02-latihan-2.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: write greeting(?string $username): string
  // TODO 2: print greeting(null) followed by a new line
  // TODO 3: print greeting('sari') followed by a new line
  ```

- **Jalankan**: `php playground/02-latihan-2.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3, bagian <code>?</code>. Polanya sama dengan <code>authorLabel</code>.</details>
  <details><summary>Petunjuk 2</summary>Bandingkan dengan <code>=== null</code>, bukan dengan <code>''</code>. String kosong tetap dianggap username.</details>
  <details><summary>Petunjuk 3</summary><code>return "Halo, {$username}!";</code></details>
- **Periksa**: `composer grade 02` (bagian Latihan 2 harus `✔`)
- **Jawaban**: [solution.md#latihan-2](../solutions/02-tipe-kondisi-fungsi/solution.md#latihan-2)

### Latihan 3: Siapa yang boleh mengubah jurnal (mandiri) (Tujuan: #3)

- **Soal**: Jurnal hanya boleh diubah oleh pemiliknya. Tamu (belum login, `null`) tidak pernah boleh. Id pengguna selalu int, dan mengirim string harus ditolak.
- **Kontrak**: file diawali `declare(strict_types=1);`. Fungsi `canEdit(int $journalUserId, ?int $currentUserId): bool` mengembalikan `true` hanya bila `$currentUserId` sama dengan `$journalUserId`. `canEdit('7', 7)` harus berhenti dengan `TypeError`. Skrip mencetak `canEdit(1, null)`, `canEdit(1, 1)`, dan `canEdit(1, 2)` dengan `var_dump`, sehingga outputnya persis:

  ```text
  bool(false)
  bool(true)
  bool(false)
  ```

- **File**: `backend/playground/02-latihan-3.php` (tanpa kerangka).
- **Jalankan**: `php playground/02-latihan-3.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3 (fungsi, <code>?int</code>, <code>return</code> sebuah perbandingan) dan Konsep 4 (<code>declare</code>).</details>
  <details><summary>Petunjuk 2</summary>Tangani <code>null</code> dulu dengan <code>if</code> dan <code>return false;</code>. Sesudahnya, hasil <code>===</code> sudah berupa bool.</details>
  <details><summary>Petunjuk 3</summary>Baris terakhir fungsi: <code>return $journalUserId === $currentUserId;</code></details>
- **Periksa**: `composer grade 02` (semua `✔`, diakhiri `OK (10 tests, 15 assertions)`)
- **Jawaban**: [solution.md#latihan-3](../solutions/02-tipe-kondisi-fungsi/solution.md#latihan-3)

## Ringkasan

1. Empat tipe pertama: `int`, `string`, `bool`, `null`. `var_dump` menampilkan tipe dan isinya.
2. `===` bernilai `true` hanya bila nilai dan tipe sama; `if`/`else` memilih blok berdasarkan hasil itu.
3. Fungsi bertipe menulis tipe setiap parameter dan tipe hasilnya; `return` mengirim hasil dan menghentikan fungsi.
4. `?string` dan `?int` menerima tipe itu atau `null`.
5. `declare(strict_types=1);` di baris pertama membuat tipe yang salah menjadi `TypeError`, bukan perubahan diam-diam.

## Utang belajar

Tidak ada.

## Simpan pekerjaan

Setelah `composer grade 02` hijau:

```bash
git status
git add playground/02-tipe.php playground/02-latihan-1.php playground/02-latihan-2.php playground/02-latihan-3.php
git status
git commit -m "feat(playground): add module 02 exercises"
git switch main
git merge feat/02-tipe-kondisi-fungsi
git branch -d feat/02-tipe-kondisi-fungsi
```

Peringatan dari [Modul 00 Konsep 4](./00-persiapan.md#konsep-4-commit-dan-branch-fitur) tetap berlaku:
- Jangan pindah branch saat masih ada perubahan yang belum di-commit.
- Baca `git status` sebelum `git add`, dan sebut file satu per satu. Jangan `git add -A` atau `git add .`.
- Hapus branch dengan `-d`, bukan `-D`.
- Bila `git merge` menulis `CONFLICT`, jalankan `git merge --abort` lalu tanya mentor.
- Jangan pernah `git reset --hard` atau `git push --force`.
- Branch fitur hanya mengubah `backend/`. `git push` opsional dan keputusan Anda.

## Di Laravel

Kode Laravel menulis tipe di hampir setiap parameter dan nilai kembalian, persis seperti `countLabel(int $count): string`. Untuk memeriksa nilai, Laravel menyediakan `dd($nilai)` (dump and die): mirip `var_dump`, lalu langsung menghentikan skrip. File bawaan Laravel tidak menulis `declare(strict_types=1)`, tapi banyak tim menambahkannya sendiri di kode mereka.

## Pertanyaan pengecekan

1. Apa output persis dari kode berikut? (Tujuan #1) [Jawaban](../solutions/02-tipe-kondisi-fungsi/solution.md#pertanyaan-1)

   ```php
   <?php

   declare(strict_types=1);

   var_dump('0' === 0);
   var_dump('Sari');
   ```

2. Fungsi di bawah dipanggil dengan `titleOrDefault('')`. Apa yang dikembalikan, dan kenapa baris `return $title;` tidak dijalankan? (Tujuan #2) [Jawaban](../solutions/02-tipe-kondisi-fungsi/solution.md#pertanyaan-2)

   ```php
   function titleOrDefault(string $title): string
   {
       if ($title === '') {
           return 'Tanpa judul';
       }

       return $title;
   }
   ```

3. File yang diawali `declare(strict_types=1);` memanggil `countLabel('5')`, dengan `countLabel(int $count): string` dari Latihan 1. Apa yang terjadi, dan bagian mana dari pesan error yang menunjukkan baris yang harus diperbaiki? (Tujuan #3) [Jawaban](../solutions/02-tipe-kondisi-fungsi/solution.md#pertanyaan-3)
