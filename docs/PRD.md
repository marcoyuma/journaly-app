# PRD: Journaly API (proyek belajar)

PRD (Product Requirements Document: dokumen yang menjelaskan apa yang dibangun dan kapan dianggap benar) ini mengunci **apa** yang dibangun backend. **Bagaimana** membangunnya ada di [ARCHITECTURE](./ARCHITECTURE.md). Keputusan di sini berasal dari sesi grilling 24 September 2026 (tercatat di [progress](./progress.md)).

## 1. Tujuan

Tujuan utama adalah **belajar**: memahami backend PHP dari nol, tanpa framework, dengan membangun ulang API untuk frontend Journaly yang sudah jadi. Produknya kecil (login dan CRUD jurnal) supaya perhatian tertuju pada konsep backend, bukan pada banyaknya fitur. Setelah selesai, setiap pola dipetakan ke Laravel.

## 2. Pengguna

Satu jenis pengguna: pemilik jurnal. Tidak ada pendaftaran lewat aplikasi. Akun dibuat dari terminal dengan skrip `bin/create-user.php` (Modul 19). Setiap pengguna hanya bisa melihat dan mengubah jurnalnya sendiri.

## 3. Kontrak dengan frontend (wajib, tidak boleh berubah)

Frontend tidak diubah, jadi backend harus memenuhi kontrak ini **persis**. Sumbernya `frontend/lib/api.ts`, `frontend/lib/schemas.ts`, dan `frontend/next.config.ts`.

### 3.1 Cara frontend memanggil backend

- Browser memanggil `http://localhost:3000/api/...`. Next.js meneruskannya ke `http://localhost:8000/...` **tanpa** awalan `/api`. Jadi backend melihat path `/auth/login`, bukan `/api/auth/login`.
- Setiap request membawa header `Content-Type: application/json`, termasuk `GET`, `DELETE`, dan `POST /auth/logout` yang tidak punya body.
- Autentikasi (membuktikan siapa pengguna) memakai **session cookie** PHP bernama `PHPSESSID`: `HttpOnly`, `SameSite=Lax`, `Path=/`. Tidak ada token Bearer. Cookie ikut otomatis karena browser hanya bicara ke satu alamat (localhost:3000), jadi CORS tidak diperlukan.

### 3.2 Bentuk response

- Sukses dengan isi: `{ "data": ... }`. Frontend hanya membaca `data`; field tambahan di luar `data` diabaikan.
- Sukses tanpa isi: status `204` tanpa body.
- Error (status bukan 2xx): `{ "error": "pesan", "errors": { "field": "pesan" } }`. `errors` opsional dan **nilainya harus string**, bukan array. Bila bentuknya lain, frontend hanya menampilkan "Terjadi kesalahan. Coba lagi."
- User: `{ "id": 1, "username": "sari" }`.
- Journal: `{ "id": 1, "title": "...", "content": "...", "created_at": "2026-09-24T01:58:00Z", "updated_at": "2026-09-24T01:58:00Z" }`. Waktu dalam UTC dengan format ISO 8601 berakhiran `Z`. `user_id` tidak pernah dikirim.

### 3.3 Endpoint

| # | Method dan path | Body request | Sukses | Error yang dipakai frontend |
|---|---|---|---|---|
| E1 | `POST /auth/login` | `{ "username", "password" }` | `200` `{ data: User }` | `401` "Username atau password salah." ditampilkan di form |
| E2 | `GET /auth/me` | tidak ada | `200` `{ data: User }` | `401` membuat frontend pindah ke halaman login |
| E3 | `POST /auth/logout` | tidak ada | `204` | frontend selalu pindah ke login apa pun hasilnya |
| E4 | `GET /journals` | tidak ada | `200` `{ data: Journal[] }`, urut `updated_at` terbaru lalu `id` terbesar | pesan ditampilkan |
| E5 | `POST /journals` | `{ "title", "content" }` | `201` `{ data: Journal }` | `422` dengan `errors.title` dan `errors.content` di bawah field |
| E6 | `GET /journals/{id}` | tidak ada | `200` `{ data: Journal }` | `404` "Jurnal tidak ditemukan." |
| E7 | `PUT /journals/{id}` | `{ "title", "content" }` | `200` `{ data: Journal }` | `404` dicek sebelum `422` |
| E8 | `DELETE /journals/{id}` | tidak ada | `204` | `404` "Jurnal tidak ditemukan." |

### 3.4 Aturan dan pesan error

| Status | Kapan | `error` |
|---|---|---|
| 400 | Body bukan JSON yang sah, atau JSON-nya bukan objek atau array | "Body JSON tidak valid." |
| 401 | Belum login (E2, E3, E4 sampai E8) | "Silakan login terlebih dahulu." |
| 401 | Login gagal (E1) | "Username atau password salah." |
| 404 | Path tidak dikenal, termasuk `/journals/abc` | "Endpoint tidak ditemukan." |
| 404 | Jurnal tidak ada **atau milik pengguna lain** | "Jurnal tidak ditemukan." |
| 405 | Path dikenal, method tidak | "Method tidak didukung." |
| 415 | `POST` atau `PUT` tanpa `Content-Type: application/json`, dicek sebelum routing | "Content-Type harus application/json." |
| 422 | Validasi jurnal gagal | "Data tidak valid." |
| 500 | Error tak terduga; detail hanya masuk log | "Terjadi kesalahan pada server." |

Validasi jurnal (E5, E7): `title` dan `content` di-trim (spasi di awal dan akhir dibuang) sebelum dicek dan disimpan. Nilai yang bukan string dianggap kosong.

| Field | Aturan | Pesan di `errors` |
|---|---|---|
| `title` | wajib | "Judul wajib diisi." |
| `title` | maksimal 150 karakter | "Judul maksimal 150 karakter." |
| `content` | wajib | "Isi jurnal wajib diisi." |
| `content` | maksimal 20.000 karakter | "Isi jurnal maksimal 20.000 karakter." |

Karakter dihitung per huruf (termasuk emoji sebagai satu karakter), bukan per byte.

## 4. Fitur backend tambahan (tidak mengubah kontrak)

Fitur ini tidak terlihat dari frontend, tapi wajib dipelajari karena bagian dari backend yang sesungguhnya. Semuanya dicek dengan grader, curl, atau test.

- Error handler terpusat: tidak ada stack trace di response.
- Log terstruktur dengan request id.
- Test yang ditulis sendiri: unit dan HTTP.
- Migrasi database bernomor dengan runner.
- Rate limit login (`429` setelah terlalu banyak percobaan gagal).
- Perlindungan CSRF yang dijelaskan dan diuji.
- Header keamanan.
- Perbaikan batas byte kolom `TEXT` untuk isi yang penuh emoji.
- Latihan mandiri: pencarian `?q=` dan pagination opsional `?page=&limit=`. Tanpa parameter, response tetap sama persis dengan kontrak E4.

## 5. Non-goal

- Mengubah frontend dalam bentuk apa pun.
- Pendaftaran akun lewat API, reset password lewat email, peran admin.
- Tag, lampiran, format teks kaya.
- Framework atau library runtime PHP. Composer hanya untuk alat pengembangan.
- Deploy ke internet. Kurikulum berhenti di laptop; Docker opsional.

## 6. Definisi selesai

1. Frontend berjalan penuh dengan backend buatan Anda: login, daftar, buat, lihat, ubah, hapus, logout.
2. Setiap baris di tabel 3.3 dan 3.4 terbukti oleh grader dan oleh test yang Anda tulis sendiri.
3. Semua modul wajib selesai: latihan hijau di grader dan pertanyaan pengecekan terjawab.
4. Modul jembatan Laravel selesai.
