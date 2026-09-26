# 04. HTTP dan server bawaan PHP

**Tujuan**
1. Menjalankan server bawaan PHP dan mengirim request ke sana dengan `curl`.
2. Membaca status line, header, dan body dari output `curl -i`.
3. Mengatur status code dan header response dari PHP.

**Prasyarat**: [Modul 03](./03-array.md) | **File yang Anda isi**: `backend/playground/04-halo.php`, `04-status.php`, `04-latihan-1.php`, `04-latihan-2.php`, `04-latihan-3.php` (sudah dibuat kosong oleh mentor) | **Perkiraan waktu**: 60 sampai 75 menit

**Hal baru (5/5)**
1. Request dan response HTTP: method, path, status code, header, body
2. `php -S localhost:8001 file.php`: server bawaan PHP
3. `curl` dan `curl -i`: mengirim request dari terminal dan melihat response lengkap
4. `http_response_code(404)`: mengatur status code
5. `header('Nama: nilai')`: mengatur header response

## Pemanasan

1. Apa output `echo json_encode(['data' => []]);`?
2. Di file yang diawali `declare(strict_types=1);`, apa yang terjadi bila fungsi yang parameternya `int` diberi `'404'`?

<details><summary>Jawaban</summary>

1. `{"data":[]}`: `[]` adalah list kosong, jadi menjadi array JSON kosong ([Modul 03 Konsep 4](./03-array.md#konsep-4-json-dan-json_encode)).
2. Skrip berhenti dengan `TypeError`, karena `'404'` bertipe string ([Modul 02 Konsep 4](./02-tipe-kondisi-fungsi.md#konsep-4-declarestrict_types1-dan-typeerror)).

</details>

**Mulai sekarang:** `git switch main`, lalu `git switch -c feat/04-http-server-bawaan`.

## Kenapa modul ini ada

Sampai sekarang, output skrip Anda hanya muncul di terminal. Frontend Journaly tidak membaca terminal: ia mengirim **request** lewat jaringan ke `localhost:8000` dan menunggu **response** ([ARCHITECTURE](../ARCHITECTURE.md) bagian 1). Modul ini membuat `echo` yang sama sampai ke program lain lewat HTTP, dan memperlihatkan bagian response yang tidak tercetak oleh `echo`: status code dan header.

## Konsep 1: Server bawaan PHP

**Definisi.**
- Server (program yang terus berjalan, menunggu permintaan, lalu menjawabnya). PHP punya server bawaan untuk belajar dan development: `php -S localhost:8001 playground/04-halo.php`.
- `localhost` berarti "komputer ini", dan `8001` adalah port (nomor pintu tempat server menunggu). Playground memakai 8001; port 8000 disimpan untuk aplikasi Journaly.
- File di akhir perintah dijalankan **dari awal sampai akhir untuk setiap request**. Apa pun yang di-`echo` menjadi jawaban.
- `curl` (program terminal untuk mengirim request) berperan sebagai pengganti frontend: `curl localhost:8001`.

**KETIK SENDIRI** di `backend/playground/04-halo.php`:

```php
<?php

declare(strict_types=1);

// 1. Everything printed becomes the response body
echo "Halo dari server PHP\n";
```

**Jalankan** di terminal pertama, dari folder `backend`. Perintah ini tidak selesai: server terus berjalan sampai Anda menekan **Ctrl+C**.

```bash
php -S localhost:8001 playground/04-halo.php
```

```text
[Sun Sep 27 03:33:13 2026] PHP 8.5.10 Development Server (http://localhost:8001) started
```

Buka **terminal kedua** (di VS Code: tombol `+` di panel terminal), lalu:

```bash
curl localhost:8001
```

```text
Halo dari server PHP
```

Terminal pertama mencatat setiap request: satu baris `Accepted` saat request masuk dan `Closing` saat selesai.

```text
[Sun Sep 27 03:33:14 2026] [::1]:50383 Accepted
[Sun Sep 27 03:33:14 2026] [::1]:50383 Closing
```

**Prediksi**: apa jawaban `curl localhost:8001/journals`? Coba. Hasilnya sama, karena setiap path menjalankan file yang sama. Modul 05 membuat path yang berbeda dijawab berbeda.

**Kesalahan umum**

1. **Server belum dijalankan atau sudah dihentikan.** `curl` tidak menemukan siapa pun di port 8001:

   ```text
   curl: (7) Failed to connect to localhost port 8001 after 2 ms: Couldn't connect to server
   ```

2. **Server lain masih memakai port yang sama.** Menjalankan `php -S localhost:8001 ...` kedua kalinya menghasilkan `Failed to listen on localhost:8001 (reason: Address already in use)`. Cari terminal yang masih menjalankan server, lalu hentikan dengan Ctrl+C.

Sumber: [Built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php), [curl manual](https://curl.se/docs/manpage.html)

## Konsep 2: Request dan response

**Definisi.** HTTP (HyperText Transfer Protocol: aturan percakapan antara klien dan server) selalu berjalan berpasangan. Klien (program yang meminta: curl, browser, atau Next.js) mengirim **request**, lalu server mengirim **response**.

Request yang dikirim `curl localhost:8001/journals` berbentuk teks seperti ini (tidak perlu dihafal):

```text
GET /journals HTTP/1.1
Host: localhost:8001
User-Agent: curl/8.7.1
Accept: */*
```

Baris pertama berisi **method** (jenis permintaan; `GET` berarti "ambil") dan **path** (alamat yang diminta, `/journals`). Baris sesudahnya adalah **header** (keterangan tambahan berbentuk `Nama: nilai`).

`curl -i` menampilkan response lengkap, bukan hanya body. **Jalankan** (server Konsep 1 masih menyala):

```bash
curl -i localhost:8001/journals
```

```text
HTTP/1.1 200 OK
Host: localhost:8001
Date: Sat, 26 Sep 2026 20:33:14 GMT
Connection: close
X-Powered-By: PHP/8.5.10
Content-type: text/html; charset=UTF-8

Halo dari server PHP
```

| Bagian | Contoh | Artinya |
|---|---|---|
| Status line | `HTTP/1.1 200 OK` | versi HTTP, **status code** (angka hasil request), dan teks alasannya |
| Header | `Content-type: text/html; charset=UTF-8` | jenis isi body; PHP menganggap body HTML bila tidak diberi tahu |
| Baris kosong | | batas antara header dan body |
| Body | `Halo dari server PHP` | isi response, yaitu semua yang di-`echo` |

Header `Date` selalu dalam zona GMT (sama dengan UTC). Nama header tidak membedakan huruf besar kecil: `Content-type` dan `Content-Type` sama.

Status code dikelompokkan menurut angka pertamanya: `2xx` berhasil, `4xx` kesalahan dari klien, `5xx` kesalahan di server.

| Status | Teks | Dipakai Journaly saat |
|---|---|---|
| `200` | OK | request berhasil |
| `401` | Unauthorized | belum login |
| `404` | Not Found | path atau jurnal tidak ada |
| `500` | Internal Server Error | error tak terduga di server |
| `503` | Service Unavailable | server sedang tidak bisa melayani |

Sumber: [RFC 9110: HTTP Semantics](https://www.rfc-editor.org/rfc/rfc9110), [RFC 9110: Status codes](https://www.rfc-editor.org/rfc/rfc9110#name-status-codes)

## Konsep 3: `http_response_code`

**Definisi.** `http_response_code(404)` mengatur status code response. Tanpa pemanggilan ini, statusnya `200`. Status line dan header dikirim **sebelum** body, jadi tulis `http_response_code` dan `header` (Konsep 4) **sebelum** `echo` pertama.

Hentikan server Konsep 1 dengan Ctrl+C. **KETIK SENDIRI** di `backend/playground/04-status.php`:

```php
<?php

declare(strict_types=1);

// 1. Status code first, before anything is printed (the default is 200)
http_response_code(404);

// 2. Then the body
echo "Endpoint tidak ditemukan.\n";
```

**Jalankan** server dengan file baru ini di terminal pertama: `php -S localhost:8001 playground/04-status.php`. Di terminal kedua:

```bash
curl -i localhost:8001/apa-saja
```

**Prediksi** status line-nya, lalu bandingkan:

```text
HTTP/1.1 404 Not Found
Host: localhost:8001
Date: Sat, 26 Sep 2026 20:33:25 GMT
Connection: close
X-Powered-By: PHP/8.5.10
Content-type: text/html; charset=UTF-8

Endpoint tidak ditemukan.
```

**Kesalahan umum: status ditulis sebagai string.** Dengan `http_response_code('404');`, mode ketat melempar `TypeError`. Di server, error tidak muncul di terminal Anda sendiri, melainkan di dua tempat lain:
- Terminal server mencatat `PHP Fatal error:  Uncaught TypeError: http_response_code(): Argument #1 ($response_code) must be of type int, string given in .../playground/04-status.php:6`.
- Body response berisi pesan yang sama dalam HTML (`<b>Fatal error</b>: ...`), dan status line-nya justru `HTTP/1.1 200 OK`.

Status `200` untuk skrip yang gagal itu menyesatkan. Modul 24 membuat penanganan error sendiri supaya kasus seperti ini dijawab `500`.

Sumber: [http_response_code](https://www.php.net/manual/en/function.http-response-code.php)

## Konsep 4: `header`

**Definisi.** `header('Nama: nilai')` menambah atau mengganti satu header response. Header pertama yang Anda atur adalah `Content-Type` (jenis isi body):

| Nilai | Artinya |
|---|---|
| `text/html; charset=UTF-8` | halaman HTML (bawaan PHP) |
| `text/plain; charset=UTF-8` | teks biasa, seperti output playground |
| `application/json` | JSON, dipakai API Journaly mulai Modul 06 |

`charset=UTF-8` memberi tahu klien bahwa teksnya ditulis dalam UTF-8 (cara menyimpan huruf, termasuk emoji, sebagai byte).

**KETIK SENDIRI** di `04-status.php`, tepat di bawah baris `http_response_code(404);`:

```php
header('Content-Type: text/plain; charset=UTF-8');
```

Server **tidak perlu** dinyalakan ulang, karena setiap request menjalankan file dari awal. **Jalankan**: `curl -i localhost:8001/apa-saja`

```text
HTTP/1.1 404 Not Found
Host: localhost:8001
Date: Sat, 26 Sep 2026 20:33:26 GMT
Connection: close
X-Powered-By: PHP/8.5.10
Content-Type: text/plain; charset=UTF-8

Endpoint tidak ditemukan.
```

Header `Content-type` bawaan PHP diganti oleh header Anda. Header dengan nama lain ditambahkan dengan cara yang sama, satu `header()` per header.

Sumber: [header](https://www.php.net/manual/en/function.header.php), [RFC 9110: Content-Type](https://www.rfc-editor.org/rfc/rfc9110#name-content-type)

## Latihan

Setiap latihan dijalankan dengan dua terminal: server di terminal pertama, `curl -i` di terminal kedua. Hentikan server latihan sebelumnya dengan Ctrl+C sebelum menyalakan yang berikutnya. Baris `Date` di output Anda pasti berbeda. Grader menyalakan servernya sendiri di port lain, jadi server Anda boleh tetap menyala saat menjalankan `composer grade 04`.

### Latihan 1: Sambutan API (Tujuan: #1)

- **Soal**: Siapa pun yang mengakses server, apa pun path-nya, disambut dengan satu kalimat.
- **Kontrak**: setiap request (method dan path apa pun) dijawab status `200` dengan body persis `Selamat datang di Journaly API` diikuti baris baru.
- **File**: `backend/playground/04-latihan-1.php`. Ketik kerangka ini, lalu ganti `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: print "Selamat datang di Journaly API" followed by a new line
  ```

- **Jalankan**: `php -S localhost:8001 playground/04-latihan-1.php`, lalu di terminal kedua `curl -i localhost:8001/journals`
- **Harapan**:

  ```text
  HTTP/1.1 200 OK
  Host: localhost:8001
  Date: Sat, 26 Sep 2026 20:34:42 GMT
  Connection: close
  X-Powered-By: PHP/8.5.10
  Content-type: text/html; charset=UTF-8

  Selamat datang di Journaly API
  ```

- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 1: body adalah semua yang di-<code>echo</code>.</details>
  <details><summary>Petunjuk 2</summary>Status <code>200</code> adalah bawaan, jadi tidak perlu <code>http_response_code</code>.</details>
  <details><summary>Petunjuk 3</summary><code>echo "Selamat datang di Journaly API\n";</code></details>
- **Periksa**: `composer grade 04` (bagian Latihan 1 harus `✔`)
- **Jawaban**: [solution.md#latihan-1](../solutions/04-http-server-bawaan/solution.md#latihan-1)

### Latihan 2: Menolak tamu dengan 401 (Tujuan: #3)

- **Soal**: Halaman profil hanya untuk pengguna yang sudah login. Untuk sekarang, semua orang dianggap belum login.
- **Kontrak**: setiap request dijawab status `401` dengan body persis `Silakan login terlebih dahulu.` diikuti baris baru (pesan dari [PRD](../PRD.md) bagian 3.4).
- **File**: `backend/playground/04-latihan-2.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: set the status code to 401
  // TODO 2: print "Silakan login terlebih dahulu." followed by a new line
  ```

- **Jalankan**: `php -S localhost:8001 playground/04-latihan-2.php`, lalu `curl -i localhost:8001/auth/me`
- **Harapan**: status line `HTTP/1.1 401 Unauthorized`, lalu header yang sama seperti Latihan 1, lalu body `Silakan login terlebih dahulu.`
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3.</details>
  <details><summary>Petunjuk 2</summary>Status code ditulis sebagai int, sebelum <code>echo</code>.</details>
  <details><summary>Petunjuk 3</summary><code>http_response_code(401);</code></details>
- **Periksa**: `composer grade 04` (bagian Latihan 2 harus `✔`)
- **Jawaban**: [solution.md#latihan-2](../solutions/04-http-server-bawaan/solution.md#latihan-2)

### Latihan 3: Mode perawatan (mandiri) (Tujuan: #3)

- **Soal**: Saat server sedang diperbaiki, setiap request dijawab dengan pesan perawatan dalam teks biasa, dan klien diberi tahu kapan boleh mencoba lagi.
- **Kontrak**: setiap request dijawab status `503`, header `Content-Type: text/plain; charset=UTF-8`, header `Retry-After: 120` (klien boleh mencoba lagi setelah 120 detik), dan body persis `Journaly sedang perawatan. Coba lagi nanti.` diikuti baris baru.
- **File**: `backend/playground/04-latihan-3.php` (tanpa kerangka).
- **Jalankan**: `php -S localhost:8001 playground/04-latihan-3.php`, lalu `curl -i localhost:8001/`
- **Harapan**:

  ```text
  HTTP/1.1 503 Service Unavailable
  Host: localhost:8001
  Date: Sat, 26 Sep 2026 20:34:44 GMT
  Connection: close
  X-Powered-By: PHP/8.5.10
  Content-Type: text/plain; charset=UTF-8
  Retry-After: 120

  Journaly sedang perawatan. Coba lagi nanti.
  ```

- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3 (status) dan Konsep 4 (header).</details>
  <details><summary>Petunjuk 2</summary>Dua header berarti dua pemanggilan <code>header()</code>. Semuanya sebelum <code>echo</code>.</details>
  <details><summary>Petunjuk 3</summary><code>header('Retry-After: 120');</code></details>
- **Periksa**: `composer grade 04` (semua `✔`, diakhiri `OK (5 tests, 12 assertions)`)
- **Jawaban**: [solution.md#latihan-3](../solutions/04-http-server-bawaan/solution.md#latihan-3)

## Ringkasan

1. `php -S localhost:8001 file.php` menyalakan server yang menjalankan `file.php` dari awal untuk setiap request; Ctrl+C menghentikannya.
2. `curl` mengirim request dari terminal; `curl -i` menampilkan status line, header, baris kosong, lalu body.
3. Request berisi method, path, dan header; response berisi status code, header, dan body.
4. `http_response_code(404)` mengatur status (bawaan `200`); `header('Nama: nilai')` mengatur header.
5. Status dan header dikirim sebelum body, jadi keduanya ditulis sebelum `echo`.

## Utang belajar

Tidak ada.

## Simpan pekerjaan

Hentikan server dengan Ctrl+C. Setelah `composer grade 04` hijau:

```bash
git status
git add playground/04-halo.php playground/04-status.php playground/04-latihan-1.php playground/04-latihan-2.php playground/04-latihan-3.php
git status
git commit -m "feat(playground): add module 04 exercises"
git switch main
git merge feat/04-http-server-bawaan
git branch -d feat/04-http-server-bawaan
```

Peringatan dari [Modul 00 Konsep 4](./00-persiapan.md#konsep-4-commit-dan-branch-fitur) tetap berlaku:
- Jangan pindah branch saat masih ada perubahan yang belum di-commit.
- Baca `git status` sebelum `git add`, dan sebut file satu per satu. Jangan `git add -A` atau `git add .`.
- Hapus branch dengan `-d`, bukan `-D`.
- Bila `git merge` menulis `CONFLICT`, jalankan `git merge --abort` lalu tanya mentor.
- Jangan pernah `git reset --hard` atau `git push --force`.
- Branch fitur hanya mengubah `backend/`. `git push` opsional dan keputusan Anda.

## Di Laravel

`php artisan serve` menyalakan server bawaan PHP yang sama dengan `php -S`, di port 8000. Di Laravel, status code dan header ditentukan saat membuat response, misalnya `response('Endpoint tidak ditemukan.', 404)`: fungsi `response` bawaan Laravel yang menerima body dan status code, lalu mengirim status line dan header untuk Anda.

## Pertanyaan pengecekan

1. Dari output `curl -i` berikut, sebutkan status code dan kelompoknya, jenis isi body, dan body-nya. (Tujuan #2) [Jawaban](../solutions/04-http-server-bawaan/solution.md#pertanyaan-1)

   ```text
   HTTP/1.1 404 Not Found
   Host: localhost:8001
   Date: Sat, 26 Sep 2026 20:33:26 GMT
   Connection: close
   X-Powered-By: PHP/8.5.10
   Content-Type: text/plain; charset=UTF-8

   Endpoint tidak ditemukan.
   ```

2. Di terminal kedua, `curl localhost:8001` menampilkan `curl: (7) Failed to connect to localhost port 8001 after 2 ms: Couldn't connect to server`. Apa artinya, dan apa yang Anda periksa? (Tujuan #1) [Jawaban](../solutions/04-http-server-bawaan/solution.md#pertanyaan-2)

3. Anda ingin response `404` dengan `Content-Type: text/plain; charset=UTF-8` dan body `Tidak ada.`. Tulis ketiga barisnya dalam urutan yang benar, dan jelaskan kenapa urutannya begitu. (Tujuan #3) [Jawaban](../solutions/04-http-server-bawaan/solution.md#pertanyaan-3)
