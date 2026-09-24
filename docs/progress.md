# Progress

File ini diperbarui di akhir setiap sesi supaya sesi berikutnya bisa melanjutkan dari konteks bersih. Urutan baca untuk sesi baru: file ini, lalu [roadmap](./modules/00-roadmap.md), [panduan modul](./modules/00-panduan-modul.md), [PRD](./PRD.md), dan [ARCHITECTURE](./ARCHITECTURE.md).

## Status modul

| No | Modul | Status |
|---|---|---|
| 00 | Persiapan | Dokumen siap (2026-09-24). **Berikutnya**: Anda mengerjakannya |
| 01 | Program PHP pertama | Dokumen siap (2026-09-24), menunggu 00 |
| 02 sampai 38b | Lihat roadmap | Belum ditulis. Ditulis satu per sesi, saat gilirannya |

Modul selesai bila `composer grade NN` hijau, tiga pertanyaan pengecekan terjawab, dan pekerjaannya sudah di-merge ke `main`.

## Keputusan awal (grilling, 2026-09-24)

| # | Keputusan | Alasan singkat |
|---|---|---|
| Q1 | PHP tanpa framework; Composer hanya untuk alat dev (PHPUnit, lalu PHPStan dan PHP-CS-Fixer) dan autoload di Modul 29; awalnya `require` manual | Test butuh PHPUnit; library runtime menyembunyikan hal yang dipelajari |
| Q2 | Konsep bahasa PHP diajarkan tepat saat dibutuhkan: 3 modul bahasa di awal, sisanya menjelang dipakai | Tidak ada teori yang belum dipakai, tidak ada konsep sebelum diajarkan |
| Q3 | Jembatan ke Laravel, satu bagian pendek per modul | Paling banyak dipakai di pekerjaan PHP |
| Q4 | Playground dulu, lalu Journaly, ditutup milestone | Konsep terisolasi dulu, proyek tidak terlalu lama absen |
| Q5 | Mentor hanya membuat file dan folder kosong; PHP dan SQL diketik Anda; konfigurasi SALIN-TEMPEL | Permintaan pengguna |
| Q6 | Sesi pertama: fondasi dan Modul 00 serta 01; modul berikutnya satu per sesi | Modul ditulis saat gilirannya supaya tidak basi |
| Q7 | `backend/` dan `docs/` lama dihapus langsung, tanpa arsip | Keputusan pengguna |
| Q8 | 8 endpoint frontend adalah milestone wajib, ditambah fitur backend yang tidak mengubah kontrak; `?q=` dan pagination sebagai latihan mandiri | Frontend tidak disentuh, backend tetap dipelajari lengkap |
| Q9 | MySQL 8.4 | Sudah terpasang, cocok dengan frontend dan Laravel |
| Q10 | Maksimal 5 hal baru per modul, dihitung ketat dan didaftar di awal | Batas "3 konsep" di growth-log dilanggar (nyatanya 5 sampai 8) |
| Q11 | Modul sekitar 250 baris berisi, solution sekitar 120; sampingan di `<details>` | Rasio dokumen dan kode 30:1 di growth-log |
| Q12 | Setiap latihan diperiksa grader PHPUnit | Kode yang menyimpang tidak pernah tertangkap di growth-log |
| Q13 | Tool kualitas yang relevan: PHPUnit, PHPStan, PHP-CS-Fixer, bertahap | Pilihan diserahkan ke mentor |
| Q14 | Berhenti di lokal; Docker opsional dengan format modul yang sama | Keputusan pengguna |
| Q15 | `docs/` baru di-commit (baris `docs/` dihapus dari `.gitignore`) | Keputusan pengguna |
| Q16 | Anda meng-commit sendiri lewat branch fitur per modul, dengan peringatan lengkap; mentor hanya meng-commit yang mentor tulis | Keputusan pengguna |
| Q17 | Mentor menulis grader di `backend/grader/`; satu-satunya pengecualian dari "mentor tidak menulis kode" | Grader adalah alat, bukan kode belajar |
| Q18 | Database lama `journal_app` dan user MySQL-nya dihapus di Modul 13 lewat perintah yang Anda ketik | Keputusan pengguna |

## Keputusan yang berubah di tengah jalan

| Tanggal | Semula | Menjadi | Alasan |
|---|---|---|---|
| 2026-09-24 | Batas panjang modul "sekitar 250 baris" (Q11) | Dihitung sebagai baris berisi (`grep -c .`), baris kosong tidak dihitung | Modul 01 berisi 223 baris berisi dari 328 baris total. Baris kosong hanya pemisah Markdown dan tidak menambah bacaan |

## Utang belajar

Penyederhanaan cara pemula yang sengaja dibuat, dan modul yang melunasinya. Modul pelunas kembali ke file yang sama dan menunjukkan kode sebelum dan sesudah.

| Penyederhanaan | Di modul | Dilunasi di | Status |
|---|---|---|---|
| File dimuat dengan `require` manual | 07 (rencana) | 29 | Belum terjadi |
| Data jurnal di file JSON | 11 (rencana) | 18 | Belum terjadi |
| Migrasi SQL dijalankan manual | 15 (rencana) | 31 | Belum terjadi |
| `new PDO` dipakai sebelum class diajarkan | 16 (rencana) | 23 | Belum terjadi |
| Error ditangani dengan `exit` di banyak tempat | 07 sampai 22 (rencana) | 24 | Belum terjadi |
| Endpoint dicek manual dengan curl dan grader | 04 sampai 24 (rencana) | 25, 26 | Belum terjadi |
| Kolom `content` bertipe `TEXT` meluap untuk isi penuh emoji | 15 (rencana) | 34 | Belum terjadi |

## Hal yang masih terbuka

| Hal | Diverifikasi di modul |
|---|---|
| Grader HTTP: `php -S` di port acak lewat `proc_open`, ext-curl dengan cookie jar | 04 |
| Grader database: menyiapkan `journaly_test` dari file SQL Anda | 13 |
| Versi PHPStan dan PHP-CS-Fixer yang mendukung PHP 8.5 (perlu persetujuan) | 28 |

## Catatan untuk sesi berikutnya

- **Mulai dari Modul 00** (`docs/modules/00-persiapan.md`). Anda mengisi `backend/composer.json`, menjalankan `composer install` dan `composer grade 00`, lalu mengerjakan Modul 01 di branch-nya sendiri. Tugas mentor: menjawab pertanyaan dan me-review, tanpa menulis kode Anda.
- Sebelum menulis Modul 02: baca `00-panduan-modul.md`, riset ulang hal barunya, perbarui roadmap bila bergeser, tambah testsuite `02` di `backend/grader/phpunit.xml`, dan verifikasi semuanya di salinan scratchpad.
- Grader CLI sudah ada: `Cli::run()` menjalankan skrip dengan `display_errors=stderr` dan `log_errors=0`, sehingga warning dilaporkan sekali dan membuat test merah. Helper lain: `LearnerFile` (file terisi, variabel dipakai, teks muncul sekali), `Git` (hanya membaca status git), `Paths`.
- Versi yang terverifikasi di scratchpad: PHPUnit 13.3.4 memasang 27 paket, dan `composer audit` bersih. `composer grade 01 -- --colors=never` meneruskan flag tambahan ke PHPUnit.
- Kontrak frontend lengkap ada di PRD bagian 3, diambil dari `frontend/lib/api.ts`, `lib/schemas.ts`, `next.config.ts`, dan backend lama (commit `7425a12` dan sebelumnya). Contohnya: `415` dicek sebelum routing, `PUT` mengecek `404` sebelum `422`, `POST /auth/logout` tanpa login menghasilkan `401`.
