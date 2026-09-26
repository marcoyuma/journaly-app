# Progress

File ini diperbarui di akhir setiap sesi supaya sesi berikutnya bisa melanjutkan dari konteks bersih. Urutan baca untuk sesi baru: file ini, lalu [roadmap](./modules/00-roadmap.md), [panduan modul](./modules/00-panduan-modul.md), [PRD](./PRD.md), dan [ARCHITECTURE](./ARCHITECTURE.md).

## Status modul

| No | Modul | Status |
|---|---|---|
| 00 | Persiapan | Di-merge ke `main` (commit `124603f`) |
| 01 | Program PHP pertama | **Sedang Anda kerjakan** di `feat/01-program-php-pertama` (per 2026-09-27: Latihan 3 masih kosong) |
| 02 | Tipe, kondisi, fungsi | Dokumen siap (2026-09-27), menunggu 01 |
| 03 | Array | Dokumen siap (2026-09-27), menunggu 02 |
| 04 | HTTP dan server bawaan PHP | Dokumen siap (2026-09-27), menunggu 03 |
| 05 | Membaca request | Dokumen siap (2026-09-27), menunggu 04 |
| 06 sampai 38b | Lihat roadmap | Belum ditulis. Ditulis saat gilirannya |

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
| 2026-09-27 | Satu modul ditulis per sesi (Q6) | Sesi 2026-09-27 menulis Modul 02 sampai 05 sekaligus, sebelum Modul 01 selesai Anda kerjakan | Permintaan Anda. Risiko basi kecil: keempatnya modul bahasa dan HTTP dasar yang tidak bergantung pada keputusan yang masih terbuka. Sesi berikutnya kembali ke satu modul per sesi kecuali Anda meminta lain |
| 2026-09-27 | Roadmap 02 sampai 06 (hal baru) | 02 menambah `var_dump`; `http_response_code` (dari 05) dan `header()` (dari 06) pindah ke 04; 05 menambah `curl -X` dan `??`; 06 memakai `Content-Type: application/json` sebagai pengganti `header()` | Tanpa status dan header, Modul 04 tidak punya kode untuk diketik. `??` dibutuhkan untuk membaca `$_GET` tanpa warning dan belum dijadwalkan di mana pun. `curl -X` dibutuhkan untuk mengirim method selain `GET`. Semua modul tetap 5/5 |

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
| Grader HTTP: cookie jar untuk session (port acak dan ext-curl sudah terverifikasi di 04) | 20 |
| Grader database: menyiapkan `journaly_test` dari file SQL Anda | 13 |
| Versi PHPStan dan PHP-CS-Fixer yang mendukung PHP 8.5 (perlu persetujuan) | 28 |

## Catatan untuk sesi berikutnya

- **Anda sedang di Modul 01** (`feat/01-program-php-pertama`). Selesaikan sampai `composer grade 01` hijau. Karena `main` sudah maju dengan commit mentor (dokumen Modul 02 sampai 05), `git merge feat/01-program-php-pertama` di `main` membuat merge commit (bukan `Fast-forward`) dan membuka editor pesan commit. Simpan dan tutup editornya tanpa mengubah pesan. Modul 02 dan seterusnya kembali `Fast-forward` karena branch dibuat dari `main` yang terbaru.
- **Sesi berikutnya menulis Modul 06** (Response JSON). Baca `00-panduan-modul.md`, riset ulang, tambah testsuite `06` di `backend/grader/phpunit.xml`, dan verifikasi di salinan scratchpad. `header()` sudah diajarkan di 04, jadi 06 berisi `Content-Type: application/json`, flag `json_encode`, pembungkus `{data}`/`{error}`, `201`, `204`.
- **Janji yang sudah ditulis di modul dan harus ditepati:**
  - 03 Konsep 4: Modul 06 merapikan `\/` dan `\u2615` dengan flag `json_encode`.
  - 04 Konsep 3: Modul 24 membuat skrip yang gagal dijawab `500` (dengan `display_errors` menyala, PHP menjawab `200`).
  - 05 Di Laravel: Modul 23 menjelaskan objek dan tanda `->`.
  - 02 Konsep 4: Modul 09 menunjukkan data request yang tipenya tidak sesuai.
- **Helper grader** (semua di `backend/grader/Support/`):
  - `Cli::run()` menjalankan skrip dengan `display_errors=stderr` dan `log_errors=0`.
  - `LearnerFile`: file terisi, variabel dipakai, teks muncul sekali, `assertDeclaresStrictTypes`, `assertCallsFunction`.
  - `LearnerFunction::returnValue()` dan `assertThrows()`: memuat file Anda di proses terpisah (`call-function.php`, strict types, output dibuang), lalu memanggil fungsinya dengan input lain supaya jawaban yang ditulis mati ketahuan.
  - `Server::start()` menyalakan `php -S 127.0.0.1:<port acak>` dengan file Anda sebagai router, `request()` memakai ext-curl dan mengembalikan `HttpResponse` (status, header huruf kecil, body, error). Di `php -S`, `display_errors=stderr` tetap mencetak warning ke body sebagai HTML, jadi server grader memakai `display_errors=0` dan `error_log` ke file sementara; warning per request dibaca dari file itu.
  - `Git` (hanya membaca status git), `Paths`.
- Versi yang terverifikasi: PHPUnit 13.3.4 (27 paket), `composer audit` bersih, curl 8.7.1 bawaan macOS. `composer grade NN -- --colors=never` meneruskan flag tambahan ke PHPUnit.
- Kontrak frontend lengkap ada di PRD bagian 3, diambil dari `frontend/lib/api.ts`, `lib/schemas.ts`, `next.config.ts`, dan backend lama (commit `7425a12` dan sebelumnya). Contohnya: `415` dicek sebelum routing, `PUT` mengecek `404` sebelum `422`, `POST /auth/logout` tanpa login menghasilkan `401`.
