# 00. Persiapan: Composer, grader, dan branch pertama

**Tujuan**
1. Menjalankan `composer install` dan grader, lalu membedakan hasil hijau dan merah.
2. Menyimpan pekerjaan lewat branch fitur sampai ter-merge ke `main`.

**Prasyarat**: tidak ada | **File yang Anda isi**: `backend/composer.json` (sudah dibuat kosong oleh mentor) | **Perkiraan waktu**: 30 sampai 45 menit

**Hal baru (5/5)**
1. Composer dan `composer.json`
2. `composer install`, yang menghasilkan `vendor/` dan `composer.lock`
3. Grader: `composer grade NN`
4. Commit: `git status`, `git add`, `git commit`
5. Branch fitur: `git switch -c`, `git merge`, `git branch -d`

## Pemanasan

Tidak ada, ini modul pertama. Semua perintah di modul ini diketik di **terminal** (aplikasi berbasis teks untuk menjalankan perintah; di Mac: Terminal atau terminal di VS Code).

## Kenapa modul ini ada

Di proyek belajar sebelumnya, ada dua masalah yang terus terjadi:
- Tidak ada cara cepat untuk tahu apakah kode Anda sudah benar.
- Menyimpan pekerjaan terasa berisiko.

Modul ini memasang dua alat yang dipakai di **setiap** modul berikutnya. **Grader** memberi tahu dalam hitungan detik apakah latihan Anda sesuai kontrak. **Branch** membuat setiap modul punya "ruang kerja" sendiri yang baru digabung ke `main` setelah selesai.

Semua kode di modul ini adalah **SALIN-TEMPEL**. Materinya bukan kode PHP, tapi cara bekerja.

## Konsep 1: Composer dan `composer.json`

**Masalah.** Grader memakai PHPUnit (alat untuk menjalankan test PHP). PHPUnit bukan bawaan PHP, jadi harus diunduh, dan versinya harus sama di setiap laptop.

**Definisi.** Composer (pengelola paket PHP: alat yang mengunduh kode orang lain yang dibutuhkan proyek) membaca daftar kebutuhan dari `composer.json` (file yang mencatat paket apa dan versi berapa yang dibutuhkan proyek).

**SALIN-TEMPEL** ke `backend/composer.json`. Isinya adalah daftar alat untuk grader, dan Composer baru dibahas lebih dalam di Modul 29.

```json
{
    "name": "journaly/backend",
    "description": "Journaly API: a native PHP learning project",
    "type": "project",
    "license": "proprietary",
    "require": {
        "php": ">=8.5"
    },
    "require-dev": {
        "phpunit/phpunit": "13.3.4"
    },
    "config": {
        "sort-packages": true
    },
    "scripts": {
        "grade": "phpunit -c grader/phpunit.xml --testsuite"
    }
}
```

| Bagian | Artinya |
|---|---|
| `require` | Yang dibutuhkan saat aplikasi berjalan. Di proyek ini hanya PHP 8.5 ke atas, tanpa paket lain |
| `require-dev` | Alat yang hanya dipakai saat belajar dan mengetes. `13.3.4` ditulis persis (tanpa `^`) supaya semua orang memakai versi yang sama |
| `scripts.grade` | Membuat perintah pendek `composer grade`, yang menjalankan PHPUnit dengan konfigurasi grader |

Sumber: [Composer basic usage](https://getcomposer.org/doc/01-basic-usage.md), [schema composer.json](https://getcomposer.org/doc/04-schema.md)

## Konsep 2: `composer install`

**Cara kerja.** `composer install` membaca `composer.json`, mengunduh paketnya ke folder `vendor/`, lalu menulis `composer.lock` (catatan versi persis **semua** paket yang terpasang, termasuk paket yang dibutuhkan PHPUnit). Bila `composer.lock` sudah ada, Composer memasang persis versi di catatan itu.

| | Di-commit ke git? | Kenapa |
|---|---|---|
| `composer.json` | Ya | Daftar kebutuhan yang Anda tulis |
| `composer.lock` | Ya | Supaya instalasi berikutnya memasang versi yang persis sama |
| `vendor/` | **Tidak** | Bisa dibuat ulang kapan saja dari `composer.lock`. Sudah diabaikan lewat `.gitignore` |

**Jalankan** dari folder `backend`:

```bash
cd ~/Projects/journaly/backend
composer install
```

Output (baris daftar paket dipotong):

```text
No composer.lock file present. Updating dependencies to latest instead of installing from lock file. See https://getcomposer.org/install for more information.
Loading composer repositories with package information
Updating dependencies
Lock file operations: 27 installs, 0 updates, 0 removals
...
Generating autoload files
25 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
```

**Kesalahan umum: menjalankan dari folder yang salah.** Bila Anda masih di folder `journaly` (bukan `journaly/backend`), outputnya:

```text
Composer could not find a composer.json file in /Users/marcoyumarafiinursaid/Projects/journaly
To initialize a project, please create a composer.json file. See https://getcomposer.org/basic-usage
```

Perbaikannya: `cd backend`, lalu ulangi. **Semua perintah `composer` dan `php` di kurikulum ini dijalankan dari folder `backend`**, kecuali disebut lain.

Sumber: [composer install](https://getcomposer.org/doc/03-cli.md#install-i)

## Konsep 3: Grader

**Definisi.** Grader (pemeriksa) adalah kumpulan test di `backend/grader/` yang ditulis mentor. Setiap latihan punya test sendiri. Test itu hanya memeriksa **kontrak** (output atau response yang diminta soal), bukan cara Anda menulis kode.

**Jalankan**: `composer grade 00`, yaitu grader untuk modul 00.

**Prediksi dulu**: Anda belum meng-commit apa pun. Test mana yang akan gagal?

Output:

```text
PHPUnit 13.3.4 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.5.10
Configuration: /Users/marcoyumarafiinursaid/Projects/journaly/backend/grader/phpunit.xml

....FF                                                              6 / 6 (100%)

Latihan1 (Grader\Modul00\Latihan1)
 ✔ Latihan 1: PHP yang dipakai versi 8.5 atau lebih baru
 ✔ Latihan 1: ekstensi pdo_mysql, mbstring, dan curl aktif
 ✔ Latihan 1: composer install sudah dijalankan (composer.lock ada)
 ✔ Latihan 1: folder vendor/ diabaikan git sehingga tidak ikut ter-commit

Latihan2 (Grader\Modul00\Latihan2)
 ✘ Latihan 2: composer.json dan composer.lock sudah ter-commit tanpa perubahan tertunda
   │
   │ backend/composer.json punya perubahan yang belum di-commit. Cek dengan: git status
   │ Failed asserting that true is false.
   ...
FAILURES!
Tests: 6, Assertions: 9, Failures: 2.
```

**Cara membaca:**
- `✔` berarti lolos, `✘` berarti gagal.
- Di bawah setiap `✘`, **baris pertama setelah `│` adalah pesan untuk Anda**. Baris "Failed asserting..." adalah bahasa PHPUnit dan boleh diabaikan.
- Baris path yang berakhiran nomor (misalnya `Latihan2Test.php:21`) menunjuk ke file grader, bukan ke kode Anda.
- Bila outputnya berupa perbandingan teks, `-` adalah yang diharapkan (Expected) dan `+` adalah yang Anda hasilkan (Actual).

Latihan 2 memang belum dikerjakan. Merah di sini artinya "belum", bukan "rusak". Contoh pesan lain yang akan sering Anda lihat di modul berikutnya: `File playground/01-latihan-1.php masih kosong. Kerjakan Latihan 1 dulu, lalu jalankan grader lagi.`

**Kesalahan umum: lupa nomor modul.** `composer grade` tanpa angka menghasilkan:

```text
Required argument for option "--testsuite" is missing
```

Sumber: [PHPUnit: the command-line test runner](https://docs.phpunit.de/en/13.3/textui.html)

## Konsep 4: Commit dan branch fitur

**Definisi.**
- **Commit** (satu titik simpan di git beserta pesannya) dibuat dengan dua langkah. `git add <file>` memilih file yang akan disimpan. `git commit -m "pesan"` menyimpannya.
- **Branch** (cabang: jalur kerja terpisah dengan riwayat commit sendiri) membuat pekerjaan modul tidak mengganggu `main` (branch utama yang selalu berisi kode yang sudah selesai).
- **Merge** (menggabungkan commit dari satu branch ke branch lain) membawa hasil kerja branch fitur ke `main`.

Alur di setiap modul (branch fitur selalu bernama `feat/<nomor>-<nama>`):

```text
main ──●─────────────●── (setelah merge)
        \           /
         ●─────────●   feat/00-persiapan
      switch -c   commit
```

**Langkah Modul 00.** Karena Anda sudah mengisi `composer.json` sebelum membuat branch, perubahan itu ikut dibawa ke branch baru. Sejak Modul 01, branch selalu dibuat **sebelum** mengubah file.

1. Buat branch: `git switch -c feat/00-persiapan`. Output: `Switched to a new branch 'feat/00-persiapan'`.
2. Lihat apa yang berubah: `git status` (baris petunjuk git yang diawali `(use ...` dipotong di sini dan di langkah 3)

   ```text
   On branch feat/00-persiapan
   Changes not staged for commit:
   	modified:   composer.json

   Untracked files:
   	composer.lock
   ```

3. Pilih file satu per satu, lalu cek lagi:

   ```bash
   git add composer.json composer.lock
   git status
   ```

   ```text
   Changes to be committed:
   	modified:   composer.json
   	new file:   composer.lock
   ```

4. Simpan: `git commit -m "chore(backend): add composer and phpunit for the grader"`. Output diawali `[feat/00-persiapan <kode acak>]` dan diakhiri `create mode 100644 backend/composer.lock`.
5. Gabungkan ke `main`, lalu hapus branch yang sudah selesai:

   ```bash
   git switch main
   git merge feat/00-persiapan
   git branch -d feat/00-persiapan
   ```

   `git merge` menampilkan `Fast-forward` (main cukup "maju" ke commit Anda karena tidak ada perubahan lain di main). `git branch -d` menampilkan `Deleted branch feat/00-persiapan (was <kode acak>)`.

**Peringatan (berlaku di setiap modul)**
- **Jangan pindah branch saat masih ada perubahan yang belum di-commit.** Perubahan itu ikut terbawa ke branch tujuan (atau git menolak pindah), sehingga pekerjaan dua branch tercampur.
- **Baca `git status` sebelum `git add`.** `backend/config/config.php` (berisi password, mulai Modul 16) tidak boleh pernah muncul di daftar itu.
- **Jangan `git add -A` atau `git add .`** tanpa membaca `git status` dulu. Sebut file satu per satu.
- **Hapus branch dengan `-d`, bukan `-D`.** `-d` menolak menghapus branch yang belum di-merge, jadi pekerjaan Anda tidak hilang.
- **Bila `git merge` menulis `CONFLICT`:** jalankan `git merge --abort` (membatalkan merge dan mengembalikan keadaan), lalu tanya mentor.
- **Jangan pernah `git reset --hard` atau `git push --force`.** Keduanya bisa menghapus pekerjaan secara permanen.
- **Branch fitur hanya mengubah `backend/`.** `docs/` diubah mentor di `main`.
- `git status` di `main` mungkin menulis `Your branch is ahead of 'origin/main' by N commits`. Artinya ada commit yang belum dikirim ke GitHub. `git push` opsional dan keputusan Anda; mentor tidak pernah push.

Sumber: [git switch](https://git-scm.com/docs/git-switch), [git merge](https://git-scm.com/docs/git-merge), [git branch](https://git-scm.com/docs/git-branch)

## Latihan

### Latihan 1: Membuat grader Latihan 1 hijau (Tujuan: #1)

- **Soal**: Pasang alat grader lalu pastikan laptop Anda memenuhi syarat kurikulum.
- **Kontrak**: keempat baris `Latihan 1: ...` bertanda `✔`.
- **File**: `backend/composer.json` (isi dari Konsep 1).
- **Jalankan**: `composer install`, lalu `composer grade 00`.
- **Harapan**: seperti output di Konsep 3. Empat `✔` untuk Latihan 1; Latihan 2 boleh masih `✘`.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 1 dan 2. Semua perintah dijalankan dari folder <code>backend</code>.</details>
  <details><summary>Petunjuk 2</summary>Bila ekstensi tidak aktif, cek daftar ekstensi dengan <code>php -m</code>.</details>
  <details><summary>Petunjuk 3</summary>Isi <code>composer.json</code> harus persis blok SALIN-TEMPEL, termasuk tanda kurung kurawal pembuka dan penutup.</details>
- **Periksa**: `composer grade 00`
- **Jawaban**: [solution.md#latihan-1](../solutions/00-persiapan/solution.md#latihan-1)

### Latihan 2: Menyimpan pekerjaan lewat branch (Tujuan: #2)

- **Soal**: Simpan `composer.json` dan `composer.lock` ke `main` lewat branch `feat/00-persiapan`, lalu bersihkan branch-nya.
- **Kontrak**: kedua file ter-commit tanpa perubahan tertunda; Anda berada di `main`; branch `feat/00-persiapan` sudah tidak ada.
- **File**: tidak ada file baru.
- **Jalankan**: langkah 1 sampai 5 di Konsep 4, lalu `composer grade 00`.
- **Harapan**: `OK (6 tests, 12 assertions)`.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 4, langkah berurutan.</details>
  <details><summary>Petunjuk 2</summary>Setelah commit, Anda masih di branch fitur. Pindah dulu ke <code>main</code> sebelum merge.</details>
  <details><summary>Petunjuk 3</summary><code>git switch main</code>, <code>git merge feat/00-persiapan</code>, <code>git branch -d feat/00-persiapan</code>.</details>
- **Periksa**: `composer grade 00`
- **Jawaban**: [solution.md#latihan-2](../solutions/00-persiapan/solution.md#latihan-2)

## Ringkasan

1. `composer.json` mencatat kebutuhan proyek; di Journaly hanya alat pengembangan (`require-dev`).
2. `composer install` mengisi `vendor/` (tidak di-commit) dan menulis `composer.lock` (di-commit).
3. `composer grade NN` menjalankan grader modul `NN`; baca baris pertama di bawah setiap `✘`.
4. Commit adalah `git add <file>` lalu `git commit -m`, selalu setelah membaca `git status`.
5. Setiap modul dikerjakan di `feat/NN-nama`, lalu di-merge ke `main` dan dihapus dengan `-d`.

## Utang belajar

Tidak ada.

## Di Laravel

Proyek Laravel juga punya `composer.json`, `composer.lock`, dan `vendor/`. Bedanya, Laravel sendiri tercantum di `require` karena dibutuhkan saat aplikasi berjalan, sedangkan Journaly sengaja hanya memakai `require-dev`. Test di Laravel juga dijalankan oleh PHPUnit.

## Pertanyaan pengecekan

1. Kenapa `composer.lock` di-commit tapi `vendor/` tidak? (Tujuan #1) [Jawaban](../solutions/00-persiapan/solution.md#pertanyaan-1)
2. Grader menampilkan `✘` dengan pesan `File playground/01-latihan-1.php masih kosong.` Apa artinya, dan apa langkah Anda berikutnya? (Tujuan #1) [Jawaban](../solutions/00-persiapan/solution.md#pertanyaan-2)
3. Anda sedang di `feat/01-program-php-pertama` dan `git status` menunjukkan file yang berubah tapi belum di-commit. Bolehkah Anda `git switch main` sekarang? Kenapa? (Tujuan #2) [Jawaban](../solutions/00-persiapan/solution.md#pertanyaan-3)
