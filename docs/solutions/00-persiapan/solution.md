# Solution 00. Persiapan

Soal ada di [Modul 00](../../modules/00-persiapan.md). Coba kerjakan sendiri dulu; buka file ini setelah grader hijau atau setelah Anda benar-benar buntu.

## Latihan 1

Dari folder `backend`:

1. Tempel blok SALIN-TEMPEL Konsep 1 ke `backend/composer.json`.
2. `composer install`
3. `composer grade 00`

Output yang benar untuk bagian Latihan 1:

```text
Latihan1 (Grader\Modul00\Latihan1)
 ✔ Latihan 1: PHP yang dipakai versi 8.5 atau lebih baru
 ✔ Latihan 1: ekstensi pdo_mysql, mbstring, dan curl aktif
 ✔ Latihan 1: composer install sudah dijalankan (composer.lock ada)
 ✔ Latihan 1: folder vendor/ diabaikan git sehingga tidak ikut ter-commit
```

Bila ada yang `✘`, baca baris pertama di bawahnya:
- "composer.lock belum ada" berarti `composer install` belum dijalankan atau dijalankan di folder yang salah.
- "Versi PHP Anda ..." berarti PHP perlu diperbarui (`brew upgrade php`).

## Latihan 2

```bash
git switch -c feat/00-persiapan
git status
git add composer.json composer.lock
git status
git commit -m "chore(backend): add composer and phpunit for the grader"
git switch main
git merge feat/00-persiapan
git branch -d feat/00-persiapan
composer grade 00
```

Output akhir grader (hasil eksekusi):

```text
Latihan1 (Grader\Modul00\Latihan1)
 ✔ Latihan 1: PHP yang dipakai versi 8.5 atau lebih baru
 ✔ Latihan 1: ekstensi pdo_mysql, mbstring, dan curl aktif
 ✔ Latihan 1: composer install sudah dijalankan (composer.lock ada)
 ✔ Latihan 1: folder vendor/ diabaikan git sehingga tidak ikut ter-commit

Latihan2 (Grader\Modul00\Latihan2)
 ✔ Latihan 2: composer.json dan composer.lock sudah ter-commit tanpa perubahan tertunda
 ✔ Latihan 2: Anda kembali di main dan branch feat/00-persiapan sudah di-merge lalu dihapus

OK (6 tests, 12 assertions)
```

Kesalahan yang paling mungkin: menjalankan `git merge` saat masih di branch fitur. Grader lalu menulis "Anda belum kembali ke main". Pindah dulu dengan `git switch main`, baru merge.

## Pertanyaan 1

Dasar: Konsep 2.

`vendor/` bisa dibuat ulang kapan saja dengan `composer install`, jadi tidak perlu disimpan di git. `composer.lock` mencatat versi persis semua paket, dan `composer install` memakai catatan itu untuk memasang versi yang sama persis. Tanpa `composer.lock` di git, instalasi di tempat lain bisa mendapat versi yang berbeda.

## Pertanyaan 2

Dasar: Konsep 3.

Baris pertama di bawah `✘` adalah pesan untuk Anda: file latihan itu belum diisi. Grader belum menemukan kesalahan di kode Anda, karena memang belum ada kode. Langkahnya: kerjakan Latihan 1 Modul 01 di file itu, lalu jalankan `composer grade 01` lagi.

## Pertanyaan 3

Dasar: Konsep 4 (peringatan pertama).

Tidak boleh. Perubahan yang belum di-commit ikut terbawa ke `main` (atau git menolak pindah), sehingga pekerjaan branch fitur tercampur dengan `main`. Selesaikan dulu: `git status`, `git add <file>`, `git commit -m "..."`, baru `git switch main`.
