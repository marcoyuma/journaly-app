# 05. Membaca request

**Tujuan**
1. Membaca method dan path sebuah request dari `$_SERVER` dan `parse_url`.
2. Membaca query string dari `$_GET` dengan nilai bawaan lewat `??`.
3. Memilih status code dan body berdasarkan path request.

**Prasyarat**: [Modul 04](./04-http-server-bawaan.md) | **File yang Anda isi**: `backend/playground/05-request.php`, `05-latihan-1.php`, `05-latihan-2.php`, `05-latihan-3.php` (sudah dibuat kosong oleh mentor) | **Perkiraan waktu**: 60 sampai 75 menit

**Hal baru (5/5)**
1. Superglobal `$_SERVER`: `$_SERVER['REQUEST_METHOD']` dan `$_SERVER['REQUEST_URI']`
2. `curl -X POST`: mengirim request dengan method selain `GET`
3. `parse_url($uri, PHP_URL_PATH)`: mengambil path dari URI
4. Query string dan `$_GET`
5. `??`: nilai bawaan bila key tidak ada

## Pemanasan

1. Setiap request ke `php -S localhost:8001 playground/04-halo.php` menjalankan file apa, dan dari baris mana?
2. Apa status code response bila skrip tidak memanggil `http_response_code`?

<details><summary>Jawaban</summary>

1. `playground/04-halo.php`, dari awal sampai akhir, untuk setiap request ([Modul 04 Konsep 1](./04-http-server-bawaan.md#konsep-1-server-bawaan-php)).
2. `200` ([Modul 04 Konsep 3](./04-http-server-bawaan.md#konsep-3-http_response_code)).

</details>

**Mulai sekarang:** `git switch main`, lalu `git switch -c feat/05-membaca-request`.

## Kenapa modul ini ada

Di Modul 04, `GET /journals` dan `POST /auth/login` dijawab sama persis, karena skrip Anda tidak tahu apa yang diminta. Journaly punya delapan endpoint ([PRD](../PRD.md) bagian 3.3) yang dibedakan oleh **method** dan **path**, dan fitur pencarian nanti membaca kata kunci dari `?q=`. Modul ini membuat skrip membaca ketiganya.

## Konsep 1: `$_SERVER`

**Definisi.** Superglobal (array bawaan yang diisi PHP untuk setiap request dan bisa dibaca di mana saja, termasuk di dalam fungsi) `$_SERVER` adalah array asosiatif berisi keterangan request dan server. Dua key pertama yang Anda pakai:

| Key | Isinya | Contoh |
|---|---|---|
| `REQUEST_METHOD` | method request | `GET` |
| `REQUEST_URI` | URI (alamat yang diminta: path, lalu query string bila ada) | `/journals?q=php` |

**KETIK SENDIRI** di `backend/playground/05-request.php`:

```php
<?php

declare(strict_types=1);

// 1. PHP fills $_SERVER for every request
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
echo "Method: {$method}\n";
echo "URI: {$uri}\n";
```

**Jalankan** server di terminal pertama: `php -S localhost:8001 playground/05-request.php`. Di terminal kedua:

```bash
curl localhost:8001/journals
```

```text
Method: GET
URI: /journals
```

**Kesalahan umum: menjalankan file server dengan `php` biasa.** `php playground/05-request.php` tidak melalui HTTP, jadi tidak ada request dan key-nya tidak ada (setiap warning juga tercetak dua kali):

```text
Warning: Undefined array key "REQUEST_METHOD" in /Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/05-request.php on line 6
```

File yang membaca request selalu dijalankan lewat `php -S` dan diakses dengan `curl`.

Sumber: [$_SERVER](https://www.php.net/manual/en/reserved.variables.server.php), [Superglobals](https://www.php.net/manual/en/language.variables.superglobals.php)

## Konsep 2: `curl -X`

**Definisi.** Tanpa pilihan tambahan, `curl` mengirim `GET`. `curl -X POST <alamat>` mengirim method yang ditulis setelah `-X`. Method yang dipakai Journaly:

| Method | Artinya | Contoh di Journaly |
|---|---|---|
| `GET` | ambil data | `GET /journals` |
| `POST` | kirim data baru atau lakukan aksi | `POST /auth/login` |
| `PUT` | ganti data yang ada | `PUT /journals/5` |
| `DELETE` | hapus data | `DELETE /journals/5` |

**Jalankan** (server Konsep 1 masih menyala):

```bash
curl -X POST localhost:8001/auth/login
curl -X DELETE localhost:8001/journals/5
```

```text
Method: POST
URI: /auth/login
Method: DELETE
URI: /journals/5
```

**Kesalahan umum: method huruf kecil.** Method ditulis huruf besar. `curl -X post localhost:8001/auth/login` ditolak server bawaan PHP tanpa jawaban:

```text
curl: (52) Empty reply from server
```

Sumber: [curl manual: -X](https://curl.se/docs/manpage.html#-X), [RFC 9110: Methods](https://www.rfc-editor.org/rfc/rfc9110#name-methods)

## Konsep 3: Path dan `parse_url`

**Masalah.** URI bisa membawa query string (bagian setelah `?`, berisi pasangan `nama=nilai`): `/journals?q=php`. Untuk memilih endpoint, yang dibutuhkan hanya path-nya, `/journals`.

**Definisi.** `parse_url($uri, PHP_URL_PATH)` mengembalikan bagian path saja. `PHP_URL_PATH` adalah konstanta (nama tetap bawaan PHP yang mewakili sebuah nilai) yang memberi tahu `parse_url` bagian mana yang diminta.

**KETIK SENDIRI** di akhir file:

```php

// 2. The path is the URI without the query string
$path = parse_url($uri, PHP_URL_PATH);
echo "Path: {$path}\n";
```

**Jalankan** (server tidak perlu dinyalakan ulang). Alamat yang berisi `?` **wajib diberi petik satu**:

```bash
curl 'localhost:8001/journals?q=php'
```

```text
Method: GET
URI: /journals?q=php
Path: /journals
```

**Kesalahan umum: alamat dengan `?` tanpa petik.** Bagi zsh, `?` adalah karakter khusus untuk mencari nama file, jadi perintahnya bahkan tidak sampai ke curl:

```text
zsh: no matches found: localhost:8001/journals?q=php
```

Sumber: [parse_url](https://www.php.net/manual/en/function.parse-url.php), [RFC 3986: URI](https://www.rfc-editor.org/rfc/rfc3986#section-3)

## Konsep 4: `$_GET` dan `??`

**Definisi.**
- `$_GET` adalah superglobal berisi pasangan dari query string, sebagai array asosiatif. Beberapa pasangan dipisah `&`: `?q=php&page=2`. PHP sudah menerjemahkan kode seperti `%20` (spasi yang ditulis di URL) menjadi karakter aslinya. **Nilainya selalu string**, termasuk `page=2`.
- `$a ?? $b` (null coalescing) menghasilkan `$a` bila ada dan tidak `null`, selain itu `$b`. Dengan `$_GET['q'] ?? ''`, key `q` yang tidak ada tidak memicu warning.

**KETIK SENDIRI** di akhir file:

```php

// 3. Query parameters arrive in $_GET, always as strings
var_dump($_GET);

// 4. ?? gives a default when the key does not exist
$q = $_GET['q'] ?? '';
echo "Cari: {$q}\n";
```

**Prediksi** dua bagian terakhir output untuk kedua request, lalu **Jalankan**:

```bash
curl 'localhost:8001/journals?q=belajar%20php&page=2'
curl localhost:8001/journals
```

```text
Method: GET
URI: /journals?q=belajar%20php&page=2
Path: /journals
array(2) {
  ["q"]=>
  string(11) "belajar php"
  ["page"]=>
  string(1) "2"
}
Cari: belajar php
Method: GET
URI: /journals
Path: /journals
array(0) {
}
Cari: 
```

**Kesalahan umum: membaca key yang mungkin tidak ada tanpa `??`.** Dengan `$q = $_GET['q'];`, request tanpa `?q=` menghasilkan warning di terminal server, dan warning yang sama ikut masuk ke body response sebagai HTML:

```text
<br />
<b>Warning</b>:  Undefined array key "q" in <b>/Users/marcoyumarafiinursaid/Projects/journaly/backend/playground/05-request.php</b> on line <b>19</b><br />
Cari: 
```

Sumber: [$_GET](https://www.php.net/manual/en/reserved.variables.get.php), [Null coalescing operator](https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.coalesce)

## Latihan

Seperti Modul 04: server di terminal pertama (hentikan server sebelumnya dengan Ctrl+C), `curl` di terminal kedua.

### Latihan 1: Method dan path (Tujuan: #1)

- **Soal**: Sebelum membuat router, pastikan skrip bisa melihat method dan path setiap request.
- **Kontrak**: setiap request dijawab status `200` dengan dua baris: `Method: <method>` lalu `Path: <path>`, dengan path tanpa query string. Contoh untuk `DELETE /journals/5?confirm=1`:

  ```text
  Method: DELETE
  Path: /journals/5
  ```

- **File**: `backend/playground/05-latihan-1.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: read the method from $_SERVER
  // TODO 2: read the path (the URI without its query string)
  // TODO 3: print "Method: <method>" and "Path: <path>", one per line
  ```

- **Jalankan**: `php -S localhost:8001 playground/05-latihan-1.php`, lalu `curl -X DELETE 'localhost:8001/journals/5?confirm=1'`
- **Harapan**: persis seperti contoh di Kontrak.
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 1 dan Konsep 3.</details>
  <details><summary>Petunjuk 2</summary>Yang dicetak adalah hasil <code>parse_url</code>, bukan <code>$_SERVER['REQUEST_URI']</code> langsung.</details>
  <details><summary>Petunjuk 3</summary><code>$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);</code></details>
- **Periksa**: `composer grade 05` (bagian Latihan 1 harus `✔`)
- **Jawaban**: [solution.md#latihan-1](../solutions/05-membaca-request/solution.md#latihan-1)

### Latihan 2: Sapaan dari query string (Tujuan: #2)

- **Soal**: Halaman sambutan menyapa nama yang dikirim lewat `?name=`. Bila tidak ada nama, sapa `tamu`.
- **Kontrak**: body persis `Halo, <name>!` diikuti baris baru. Tanpa `?name=`, `<name>` adalah `tamu`, dan tidak boleh ada warning.

  ```text
  Halo, Sari!
  ```

- **File**: `backend/playground/05-latihan-2.php`. Ketik kerangka ini, lalu ganti setiap `// TODO`:

  ```php
  <?php

  declare(strict_types=1);

  // TODO 1: read name from the query string, 'tamu' when it is missing
  // TODO 2: print "Halo, <name>!" followed by a new line
  ```

- **Jalankan**: `php -S localhost:8001 playground/05-latihan-2.php`, lalu `curl 'localhost:8001/?name=Sari'` dan `curl localhost:8001`
- **Harapan**: `Halo, Sari!` lalu `Halo, tamu!`
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 4.</details>
  <details><summary>Petunjuk 2</summary>Nilai bawaan ditulis di sebelah kanan <code>??</code>.</details>
  <details><summary>Petunjuk 3</summary><code>$name = $_GET['name'] ?? 'tamu';</code></details>
- **Periksa**: `composer grade 05` (bagian Latihan 2 harus `✔`)
- **Jawaban**: [solution.md#latihan-2](../solutions/05-membaca-request/solution.md#latihan-2)

### Latihan 3: Endpoint pencarian pertama (mandiri) (Tujuan: #3)

- **Soal**: Buat satu endpoint pencarian. Path lain belum ada, jadi dijawab "tidak ditemukan".
- **Kontrak**:
  - Path `/search`: status `200`, body `Hasil pencarian: <q>` diikuti baris baru. Tanpa `?q=`, `<q>` adalah `(semua)`.
  - Path lain (termasuk `/`, `/journals`, dan `/search/extra`): status `404`, body persis `Endpoint tidak ditemukan.` diikuti baris baru.
  - Yang menentukan hanya path, jadi `/search?q=php` tetap endpoint pencarian.
- **File**: `backend/playground/05-latihan-3.php` (tanpa kerangka).
- **Jalankan**: `php -S localhost:8001 playground/05-latihan-3.php`, lalu `curl -i 'localhost:8001/search?q=php'` dan `curl -i localhost:8001/journals`
- **Harapan**: status `200 OK` dengan body `Hasil pencarian: php`, lalu status `404 Not Found` dengan body `Endpoint tidak ditemukan.`
- **Petunjuk**:
  <details><summary>Petunjuk 1</summary>Konsep 3 dan 4, ditambah <code>if</code>/<code>else</code> (Modul 02) dan <code>http_response_code</code> (Modul 04).</details>
  <details><summary>Petunjuk 2</summary>Bandingkan path hasil <code>parse_url</code> dengan <code>===</code>, bukan <code>REQUEST_URI</code>.</details>
  <details><summary>Petunjuk 3</summary><code>if ($path === '/search') { ... } else { http_response_code(404); ... }</code></details>
- **Periksa**: `composer grade 05` (semua `✔`, diakhiri `OK (5 tests, 24 assertions)`)
- **Jawaban**: [solution.md#latihan-3](../solutions/05-membaca-request/solution.md#latihan-3)

## Ringkasan

1. `$_SERVER['REQUEST_METHOD']` berisi method, dan `$_SERVER['REQUEST_URI']` berisi path beserta query string.
2. `curl -X POST <alamat>` mengirim method lain; method ditulis huruf besar.
3. `parse_url($uri, PHP_URL_PATH)` membuang query string; alamat dengan `?` diberi petik satu di terminal.
4. `$_GET` berisi pasangan query string, dan nilainya selalu string.
5. `$_GET['q'] ?? ''` memberi nilai bawaan tanpa warning bila key tidak ada.

## Utang belajar

Tidak ada.

## Simpan pekerjaan

Hentikan server dengan Ctrl+C. Setelah `composer grade 05` hijau:

```bash
git status
git add playground/05-request.php playground/05-latihan-1.php playground/05-latihan-2.php playground/05-latihan-3.php
git status
git commit -m "feat(playground): add module 05 exercises"
git switch main
git merge feat/05-membaca-request
git branch -d feat/05-membaca-request
```

Peringatan dari [Modul 00 Konsep 4](./00-persiapan.md#konsep-4-commit-dan-branch-fitur) tetap berlaku:
- Jangan pindah branch saat masih ada perubahan yang belum di-commit.
- Baca `git status` sebelum `git add`, dan sebut file satu per satu. Jangan `git add -A` atau `git add .`.
- Hapus branch dengan `-d`, bukan `-D`.
- Bila `git merge` menulis `CONFLICT`, jalankan `git merge --abort` lalu tanya mentor.
- Jangan pernah `git reset --hard` atau `git push --force`.
- Branch fitur hanya mengubah `backend/`. `git push` opsional dan keputusan Anda.

## Di Laravel

Laravel membungkus `$_SERVER` dan `$_GET` dalam satu objek request (objek dijelaskan di Modul 23). `$request->method()` sama dengan `$_SERVER['REQUEST_METHOD']`, `$request->path()` mengembalikan path tanpa query string (tanpa garis miring di depan, misalnya `journals`), dan `$request->query('q', '')` sama dengan `$_GET['q'] ?? ''`. Tanda `->` juga dijelaskan di Modul 23.

## Pertanyaan pengecekan

1. Server menjalankan file Latihan 1. Apa output persis dari `curl -X PUT 'localhost:8001/journals/12?draft=1'`? (Tujuan #1) [Jawaban](../solutions/05-membaca-request/solution.md#pertanyaan-1)

2. Sebuah skrip berisi `$page = $_GET['page'] ?? '1';`. Apa isi `$page` untuk `/journals?page=3` dan untuk `/journals`? Apa tipe `$page` di kasus pertama? (Tujuan #2) [Jawaban](../solutions/05-membaca-request/solution.md#pertanyaan-2)

3. Server menjalankan file Latihan 3. Apa status code untuk `curl -i 'localhost:8001/search/?q=php'` (ada `/` setelah `search`), dan kenapa? (Tujuan #3) [Jawaban](../solutions/05-membaca-request/solution.md#pertanyaan-3)
