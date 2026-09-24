# 01. Program PHP pertama

**Tujuan**
1. Menjalankan skrip PHP dari terminal dan menulis `echo` di dalam tag `<?php`.
2. Menyimpan nilai di variabel lalu mencetaknya lewat string petik dua.
3. Memprediksi output string petik satu dibanding petik dua.

**Prasyarat**: [Modul 00](./00-persiapan.md) | **File yang Anda isi**: `backend/playground/01-halo.php`, `01-latihan-1.php`, `01-latihan-2.php`, `01-latihan-3.php` (sudah dibuat kosong oleh mentor) | **Perkiraan waktu**: 45 sampai 60 menit

**Hal baru (5/5)**
1. `php nama-file.php`: menjalankan skrip dari terminal
2. Tag pembuka `<?php`
3. `echo` dan titik koma `;`
4. Variabel: `$nama = nilai;`
5. String petik dua (`{$nama}` dan `\n`) dibanding petik satu

## Pemanasan

1. Dari folder mana semua perintah `php` dan `composer` dijalankan?
2. Apa langkah pertama sebelum mengubah file di modul baru?

<details><summary>Jawaban</summary>

1. Dari folder `backend` ([Modul 00 Konsep 2](./00-persiapan.md#konsep-2-composer-install)).
2. Buat branch fitur: `git switch main`, lalu `git switch -c feat/01-program-php-pertama` ([Modul 00 Konsep 4](./00-persiapan.md#konsep-4-commit-dan-branch-fitur)).

</details>

**Mulai sekarang:** `git switch main`, lalu `git switch -c feat/01-program-php-pertama`.

## Kenapa modul ini ada

Backend yang akan Anda bangun pada dasarnya adalah program yang menerima permintaan lalu **mencetak jawaban**. Sebelum jawaban itu dikirim lewat internet (Modul 04), kita belajar mencetak ke terminal dulu. Caranya sama dan hasilnya langsung terlihat. Setiap latihan di modul ini adalah potongan kecil dari tampilan jurnal Journaly.

## Konsep 1: Skrip PHP pertama

**Definisi.** Skrip PHP adalah file teks berakhiran `.php`. Program `php` membacanya dari atas ke bawah lalu menjalankan isinya. Ada tiga hal baru di sini:
- `<?php` (tag pembuka): tanda "mulai dari sini, ini kode PHP". Teks sebelum tag ini dicetak apa adanya.
- `echo` (perintah mencetak teks): `echo 'teks';` mencetak isi di antara tanda petik. Teks di antara tanda petik disebut string (data berupa teks).
- `;` (titik koma): menandai akhir setiap perintah.

**KETIK SENDIRI** di `backend/playground/01-halo.php`:

```php
<?php

// 1. Print text
echo 'Halo dari PHP';
```

Baris yang diawali `//` adalah komentar (catatan untuk manusia yang dilewati PHP).

**Prediksi**: apa yang muncul di terminal?

**Jalankan** dari folder `backend`: `php playground/01-halo.php`

```text
Halo dari PHP%
```

Tanda `%` yang disorot bukan bagian dari output. Itu tanda dari zsh (shell di terminal Mac) bahwa baris terakhir tidak diakhiri "baris baru". Konsep 3 memperbaikinya.

**Kesalahan umum**

1. **Lupa `;`.** Hapus `;` lalu jalankan lagi:

   ```text
   PHP Parse error:  syntax error, unexpected end of file, expecting "," or ";" in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/01-halo.php on line 5

   Parse error: syntax error, unexpected end of file, expecting "," or ";" in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/01-halo.php on line 5
   ```

   Parse error (PHP tidak bisa membaca kode karena tata tulisnya salah) berarti tidak ada satu baris pun yang dijalankan. Perhatikan nomor barisnya: 5, padahal `;` hilang di baris 4. Nomor baris menunjuk ke **tempat PHP sadar ada yang salah**, jadi periksa baris itu dan baris di atasnya.

2. **Lupa `<?php`.** Tanpa tag pembuka, isi file dicetak apa adanya:

   ```text
   // 1. Print text
   echo 'Halo dari PHP';
   ```

<details><summary>Kenapa pesan error muncul dua kali?</summary>

PHP bawaan Homebrew di laptop ini diatur untuk mencatat error (baris `PHP Parse error:`) **dan** menampilkannya (baris `Parse error:`). Isinya sama, jadi cukup baca salah satu. Grader menjalankan skrip Anda dengan pengaturan yang menampilkan error sekali saja.

</details>

Sumber: [PHP tags](https://www.php.net/manual/en/language.basic-syntax.phptags.php), [echo](https://www.php.net/manual/en/function.echo.php), [Instruction separation](https://www.php.net/manual/en/language.basic-syntax.instruction-separation.php)

## Konsep 2: Variabel

**Masalah.** Nama aplikasi "Journaly" akan muncul di banyak tempat. Bila ditulis berulang, mengganti namanya berarti mencari dan mengubah setiap salinan.

**Definisi.** Variabel (nama yang menyimpan sebuah nilai) di PHP selalu diawali `$`. `$appName = 'Journaly';` berarti "simpan teks Journaly di dalam `$appName`". Setelah itu, menulis `$appName` sama dengan menulis nilainya. Nama variabel membedakan huruf besar dan kecil: `$appName` dan `$appname` adalah dua variabel berbeda.

**KETIK SENDIRI** di file yang sama, di bawah baris `echo 'Halo dari PHP';`:

```php

// 2. Store a value in a variable, then print it
$appName = 'Journaly';
echo $appName;
```

**Prediksi**: apakah "Journaly" muncul di baris baru?

**Jalankan**: `php playground/01-halo.php`

```text
Halo dari PHPJournaly%
```

Tidak. `echo` hanya mencetak persis yang diminta, tanpa spasi dan tanpa baris baru.

**Kesalahan umum**

1. **Salah huruf besar kecil.** Bila baris terakhir ditulis `echo $appname;`:

   ```text
   Halo dari PHPPHP Warning:  Undefined variable $appname in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/01-halo.php on line 8

   Warning: Undefined variable $appname in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/01-halo.php on line 8
   ```

   Warning (peringatan: PHP tetap berjalan, tapi ada yang salah) "Undefined variable" berarti variabel dengan nama persis itu belum pernah diisi. Grader menganggap setiap warning sebagai kegagalan.

2. **Lupa `$`.** `appName = 'Journaly';` menghasilkan `Parse error: syntax error, unexpected token "="` di baris 7.

Sumber: [Variables basics](https://www.php.net/manual/en/language.variables.basics.php)

## Konsep 3: String petik dua dan petik satu

**Definisi.** PHP punya dua cara menulis string:

| Ditulis | `{$appName}` di dalamnya | `\n` di dalamnya |
|---|---|---|
| Petik satu `'...'` | dicetak apa adanya | dicetak apa adanya (dua karakter `\` dan `n`) |
| Petik dua `"..."` | diganti nilai variabel (interpolasi: menyisipkan nilai variabel ke dalam string) | menjadi baris baru |

`\n` adalah escape sequence (kombinasi karakter yang berarti karakter khusus), di sini karakter "baris baru". Kurikulum ini selalu menulis variabel di dalam string dengan kurung kurawal `{$nama}` supaya batas nama variabelnya jelas.

**KETIK SENDIRI** di akhir file:

```php

// 3. Double quotes: {$appName} becomes its value, \n becomes a new line
echo "\n";
echo "Aplikasi: {$appName}\n";
echo 'Aplikasi: {$appName}\n';
```

**Prediksi** ketiga baris outputnya sebelum menjalankan.

**Jalankan**: `php playground/01-halo.php`

```text
Halo dari PHPJournaly
Aplikasi: Journaly
Aplikasi: {$appName}\n%
```

- `echo "\n";` mengakhiri baris pertama.
- Baris petik dua mengganti `{$appName}` dengan `Journaly` dan diakhiri baris baru.
- Baris petik satu mencetak semuanya apa adanya, termasuk `\n`, sehingga `%` kembali muncul.

<details><summary>Bandingkan file Anda (lengkap)</summary>

```php
<?php

// 1. Print text
echo 'Halo dari PHP';

// 2. Store a value in a variable, then print it
$appName = 'Journaly';
echo $appName;

// 3. Double quotes: {$appName} becomes its value, \n becomes a new line
echo "\n";
echo "Aplikasi: {$appName}\n";
echo 'Aplikasi: {$appName}\n';
```

</details>

**Kesalahan umum: memakai petik satu untuk teks yang berisi variabel.** Hasilnya `{$appName}` tercetak mentah, seperti baris ketiga di atas. Bila teks berisi variabel atau `\n`, pakai petik dua.

Sumber: [Strings: single quoted, double quoted, interpolation](https://www.php.net/manual/en/language.types.string.php)

## Latihan

### Latihan 1: Mencetak nama dan tagline aplikasi (Tujuan: #1)

- **Soal**: Halaman depan Journaly butuh dua baris teks: nama aplikasi, lalu taglinenya.
- **Kontrak**: output persis dua baris, masing-masing diakhiri baris baru:

  ```text
  Journaly
  Catatan harian pribadi
  ```

- **File**: `backend/playground/01-latihan-1.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  // TODO 1: print "Journaly" followed by a new line
  // TODO 2: print "Catatan harian pribadi" followed by a new line
  ```

- **Jalankan**: `php playground/01-latihan-1.php`
- **Harapan**: persis seperti Kontrak, tanpa `%` di akhir.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 1 (echo) dan Konsep 3 (baris baru).</details>
  <details><summary>Petunjuk 2</summary>Baris baru hanya bekerja di dalam petik dua.</details>
  <details><summary>Petunjuk 3</summary><code>echo "Journaly\n";</code> lalu baris kedua dengan pola yang sama.</details>
- **Periksa**: `composer grade 01` (bagian Latihan 1 harus `✔`)
- **Jawaban**: [solution.md#latihan-1](../solutions/01-program-php-pertama/solution.md#latihan-1)

### Latihan 2: Mencetak judul dan penulis dari variabel (Tujuan: #2)

- **Soal**: Satu entri jurnal punya judul dan penulis. Simpan keduanya di variabel, lalu cetak.
- **Kontrak**: wajib memakai variabel `$title` berisi `Hari pertama belajar PHP` dan `$author` berisi `Sari`. Output persis:

  ```text
  Judul: Hari pertama belajar PHP
  Penulis: Sari
  ```

- **File**: `backend/playground/01-latihan-2.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  // TODO 1: store 'Hari pertama belajar PHP' in $title
  // TODO 2: store 'Sari' in $author
  // TODO 3: print "Judul: " followed by $title and a new line
  // TODO 4: print "Penulis: " followed by $author and a new line
  ```

- **Jalankan**: `php playground/01-latihan-2.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 2 (variabel) dan Konsep 3 (interpolasi).</details>
  <details><summary>Petunjuk 2</summary>Variabel ditulis di dalam petik dua dengan kurung kurawal: <code>{$title}</code>.</details>
  <details><summary>Petunjuk 3</summary><code>echo "Judul: {$title}\n";</code></details>
- **Periksa**: `composer grade 01` (bagian Latihan 2 harus `✔`)
- **Jawaban**: [solution.md#latihan-2](../solutions/01-program-php-pertama/solution.md#latihan-2)

### Latihan 3: Pratinjau jurnal (mandiri) (Tujuan: #2)

- **Soal**: Daftar jurnal menampilkan pratinjau setiap entri. Judul muncul dua kali: di baris pertama dan di baris penutup.
- **Kontrak**: wajib memakai `$title` (`Belajar variabel`), `$content` (`Hari ini saya belajar echo dan variabel.`), dan `$createdAt` (`2026-09-24`). **Setiap nilai hanya boleh ditulis satu kali di file**, yaitu di variabelnya. Output persis:

  ```text
  Judul: Belajar variabel
  Isi: Hari ini saya belajar echo dan variabel.
  (Belajar variabel, 2026-09-24)
  ```

- **File**: `backend/playground/01-latihan-3.php` (tanpa kerangka).
- **Jalankan**: `php playground/01-latihan-3.php`
- **Harapan**: persis seperti Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Sama seperti Latihan 2, dengan tiga variabel.</details>
  <details><summary>Petunjuk 2</summary>Satu variabel boleh dipakai berkali-kali. Justru itu gunanya.</details>
  <details><summary>Petunjuk 3</summary>Baris ketiga: petik dua berisi <code>({$title}, {$createdAt})</code> diakhiri <code>\n</code>.</details>
- **Periksa**: `composer grade 01` (semua `✔`, diakhiri `OK (6 tests, ...)`)
- **Jawaban**: [solution.md#latihan-3](../solutions/01-program-php-pertama/solution.md#latihan-3)

## Ringkasan

1. `php playground/nama.php` (dari folder `backend`) menjalankan skrip dari atas ke bawah.
2. Kode PHP dimulai setelah `<?php`; setiap perintah diakhiri `;`.
3. `echo` mencetak persis yang diminta, tanpa baris baru otomatis.
4. Variabel diawali `$`, diisi dengan `=`, dan membedakan huruf besar kecil.
5. Petik dua mengganti `{$nama}` dan `\n`; petik satu mencetak semuanya apa adanya.

## Utang belajar

Tidak ada.

## Simpan pekerjaan

Setelah `composer grade 01` hijau:

```bash
git status
git add playground/01-halo.php playground/01-latihan-1.php playground/01-latihan-2.php playground/01-latihan-3.php
git status
git commit -m "feat(playground): add module 01 exercises"
git switch main
git merge feat/01-program-php-pertama
git branch -d feat/01-program-php-pertama
```

Peringatan dari [Modul 00 Konsep 4](./00-persiapan.md#konsep-4-commit-dan-branch-fitur) tetap berlaku:
- Baca `git status` sebelum `git add`, dan sebut file satu per satu.
- Jangan pindah branch sebelum commit.
- Pakai `-d`, bukan `-D`.
- Bila ada `CONFLICT`, jalankan `git merge --abort` lalu tanya.
- Jangan pernah `reset --hard` atau `push --force`.

## Di Laravel

Semua file Laravel juga diawali `<?php`. Perintah `php artisan serve` artinya menjalankan skrip PHP bernama `artisan` dari terminal, sama seperti `php playground/01-halo.php`. Di tampilan Laravel, `{{ $title }}` mencetak isi variabel, mirip `{$title}` di string petik dua.

## Pertanyaan pengecekan

1. Apa output persis dari kode berikut? (Tujuan #3) [Jawaban](../solutions/01-program-php-pertama/solution.md#pertanyaan-1)

   ```php
   <?php

   $judul = 'Catatan';
   echo 'Judul: {$judul}\n';
   ```

2. File `playground/coba.php` berisi kode di bawah. Apa yang muncul saat dijalankan, dan kenapa? (Tujuan #2) [Jawaban](../solutions/01-program-php-pertama/solution.md#pertanyaan-2)

   ```php
   <?php

   $appName = 'Journaly';
   echo "{$appname}\n";
   ```

3. Seseorang lupa menulis `<?php` di baris pertama file. Apa yang tercetak saat file itu dijalankan? (Tujuan #1) [Jawaban](../solutions/01-program-php-pertama/solution.md#pertanyaan-3)
