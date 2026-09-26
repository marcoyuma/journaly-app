# Arsitektur Journaly API

Dokumen ini menjelaskan **bagaimana** backend dibangun. **Apa** yang dibangun ada di [PRD](./PRD.md). Arsitektur tumbuh bertahap: versi pemula ditulis dulu, lalu dirapikan di modul pelunas (lihat tabel utang belajar di [progress](./progress.md)). Semua versi diverifikasi 24 September 2026.

## 1. Alur satu request

```text
Browser (localhost:3000)
   |  fetch("/api/journals")
   v
Next.js dev server  -- rewrite /api/:path* --> http://localhost:8000/:path*
   |
   v
php -S localhost:8000 -t backend/public      (server bawaan PHP, Modul 04)
   |  setiap request menjalankan satu file, dari awal sampai akhir
   v
backend/public/index.php                     (front controller, Modul 07)
   |  1. cek Content-Type untuk POST/PUT      (415)
   |  2. cocokkan method + path              (router, Modul 07 dan 08)
   v
handler, misalnya listJournals()             (fungsi PHP)
   |  requireAuth()  -> $_SESSION             (Modul 20 dan 21)
   |  query ke database lewat PDO            (Modul 16 sampai 18)
   v
MySQL 8.4 (journaly_dev)
   |
   v
sendJson(200, ['data' => ...])               (Modul 06)
   |  header, status, echo JSON, selesai
   v
kembali ke Next.js, lalu ke browser
```

PHP memakai model **share-nothing** (setiap request mulai dari nol; variabel dari request sebelumnya tidak ada lagi). Karena itu data harus disimpan di luar proses: file (versi pemula, Modul 11), lalu MySQL (Modul 18), dan session untuk "siapa yang sedang login" (Modul 20).

## 2. Struktur folder

Versi pemula (Modul 07 sampai 28), semua fungsi dimuat dengan `require`:

```text
backend/
├── composer.json, composer.lock   alat pengembangan (Modul 00)
├── public/index.php               front controller: satu-satunya file yang bisa dibuka dari luar
├── src/
│   ├── bootstrap.php              memuat file lain, error handler, session
│   ├── http.php                   sendJson, sendError, readJsonBody
│   ├── db.php                     koneksi PDO
│   ├── auth.php                   login, logout, me, requireAuth
│   └── journals.php               CRUD dan validasi jurnal
├── config/
│   ├── config.example.php         contoh konfigurasi (di-commit)
│   └── config.php                 konfigurasi asli berisi password (tidak di-commit)
├── database/migrations/           file SQL bernomor
├── bin/create-user.php            skrip terminal pembuat akun
├── storage/                       file data versi pemula dan log (tidak di-commit)
├── tests/                         test yang Anda tulis sendiri (Modul 25 ke atas)
├── playground/                    file latihan per modul
└── grader/                        test pemeriksa latihan (ditulis mentor)
```

Versi akhir (Modul 29 ke atas): kode di `src/` memakai `namespace` dan autoload Composer (PSR-4), dengan class repository yang menerima koneksi PDO lewat constructor (dependency injection). Handler tetap berupa fungsi sampai jembatan Laravel, supaya perubahannya kecil dan terlihat.

## 3. Aturan lapisan

| Bagian | Tanggung jawab | Tidak boleh |
|---|---|---|
| `public/index.php` | Routing: memilih handler dari method dan path | Menulis query SQL |
| Handler (`auth.php`, `journals.php`) | Membaca input, validasi, memanggil database, membentuk response | Mencetak HTML atau stack trace |
| `http.php` | Bentuk response dan pembacaan body | Tahu tentang jurnal atau user |
| `db.php` | Membuat satu koneksi PDO | Tahu tentang HTTP |

Setiap query data jurnal selalu menyertakan `user_id` pengguna yang sedang login. Jurnal milik orang lain dijawab `404`, sama dengan jurnal yang tidak ada, supaya keberadaannya tidak bocor (celah IDOR: Insecure Direct Object Reference, mengakses data orang lain hanya dengan mengganti id di URL).

## 4. Skema database

```text
users                                   journals
- id            INT UNSIGNED PK AI      - id          INT UNSIGNED PK AI
- username      VARCHAR(50) UNIQUE      - user_id     INT UNSIGNED FK -> users.id, ON DELETE CASCADE
- password_hash VARCHAR(255)            - title       VARCHAR(150)
- created_at    TIMESTAMP               - content     TEXT (diperbaiki di Modul 34)
                                        - created_at  TIMESTAMP
                                        - updated_at  TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

Database: `journaly_dev` untuk development, `journaly_test` untuk test. Character set `utf8mb4` (mendukung emoji). Koneksi mengatur `time_zone = '+00:00'` sehingga waktu dibaca dan ditulis dalam UTC.

## 5. Grader

Grader adalah test PHPUnit yang ditulis mentor di `backend/grader/` untuk memeriksa latihan Anda.

- `composer grade NN` menjalankan testsuite modul `NN`.
- Grader memeriksa dari luar saja: output skrip terminal, status HTTP, header, dan JSON. Ia tidak memeriksa cara Anda menulis kode, kecuali yang diminta kontrak latihan (misalnya "harus memakai variabel `$title`").
- Skrip dijalankan dengan `display_errors=stderr`, sehingga warning PHP dilaporkan terpisah dan membuat test merah.
- Mulai Modul 04, grader menyalakan `php -S` sendiri di port acak lalu mengirim request dengan ext-curl. Server grader memakai `display_errors=0` dan mencatat error ke file sementara, sehingga warning tidak tercampur ke body dan tetap membuat test merah. Mulai Modul 13, grader menyiapkan `journaly_test` dari file SQL milik Anda.

## 6. Matriks versi

| Komponen | Versi | Sumber |
|---|---|---|
| PHP | 8.5.10 (Homebrew) | [php.net](https://www.php.net/releases/) |
| Composer | 2.10.3 | [getcomposer.org](https://getcomposer.org/) |
| MySQL | 8.4.11 (Homebrew `mysql@8.4`, rilis LTS) | [dev.mysql.com](https://dev.mysql.com/doc/refman/8.4/en/) |
| PHPUnit | 13.3.4 (`require-dev`) | [phpunit.de](https://phpunit.de/) |
| Next.js (frontend, tidak diubah) | 16.3.6 | `frontend/package.json` |

Paket yang direncanakan dan baru dipasang di modulnya, setelah disetujui: PHPStan dan PHP-CS-Fixer (Modul 28). Tidak ada paket runtime.

## 7. Catatan keputusan (ADR)

ADR (Architecture Decision Record: catatan singkat berisi konteks, keputusan, alternatif yang ditolak, dan konsekuensi).

**ADR-001: PHP native, Composer hanya untuk alat.** Konteks: tujuan belajar adalah memahami apa yang biasanya dikerjakan framework. Keputusan: tanpa framework dan tanpa paket runtime; routing, response, session, dan PDO ditulis sendiri. Composer hanya untuk PHPUnit, lalu PHPStan dan PHP-CS-Fixer, dan autoload di Modul 29. Ditolak: tanpa Composer sama sekali (tidak ada PHPUnit, sehingga tidak ada grader dan test); library kecil untuk router atau validasi (menyembunyikan hal yang sedang dipelajari). Konsekuensi: awalnya file dimuat dengan `require` manual, dicatat sebagai utang belajar.

**ADR-002: MySQL 8.4.** Konteks: sudah terpasang dan berjalan, skemanya cocok dengan frontend. Ditolak: PostgreSQL (tidak lebih baik untuk tujuan ini); SQLite dulu (menambah satu perpindahan tanpa manfaat belajar yang sebanding). Konsekuensi: database lama `journal_app` dihapus Anda sendiri di Modul 13.

**ADR-003: Session cookie, bukan token.** Konteks: frontend sudah memakai cookie `PHPSESSID` dan tidak boleh diubah. Keputusan: session bawaan PHP dengan `HttpOnly` dan `SameSite=Lax`, id session diganti setelah login. Konsekuensi: CSRF dibahas di Modul 32.

**ADR-004: Grader black box yang ditulis mentor.** Konteks: di proyek sebelumnya (growth-log), kode latihan yang menyimpang dari kontrak tidak pernah tertangkap. Keputusan: setiap latihan punya test PHPUnit yang memeriksa kontraknya. Folder `grader/` adalah satu-satunya kode yang ditulis mentor. Ditolak: pengecekan manual dengan membandingkan output. Konsekuensi: setiap modul diverifikasi mentor di salinan scratchpad sebelum diterbitkan.

**ADR-005: Berhenti di laptop, Docker opsional.** Konteks: fokusnya backend PHP, Docker belum terpasang. Keputusan: tidak ada modul deploy; Modul 37 (Docker) opsional dengan format modul yang sama.

**ADR-006: `backend/` dan `docs/` lama dihapus.** Konteks: backend lama sudah jadi, dan dokumen lama menjelaskan kode yang sudah ada, bukan membangun dari nol. Keputusan: keduanya dihapus (24 September 2026). Kode lama tetap bisa dilihat di riwayat git sebelum commit kurikulum ini, tapi tidak dipakai sebagai acuan belajar.
