# 00. Panduan menulis modul

Dokumen ini adalah aturan untuk menulis **setiap** modul Journaly. Mentor membacanya sebelum menulis modul, dan Anda boleh memakainya untuk menagih: bila sebuah modul melanggar aturan di sini, modul itu yang salah, bukan Anda.

## Kenapa panduan ini ada

Aturan ini lahir dari audit kurikulum growth-log (proyek belajar Express sebelumnya) tanggal 24 September 2026. Pola kesalahan yang ditemukan, beserta aturan yang mencegahnya:

| Kesalahan di growth-log | Contoh nyata | Aturan |
|---|---|---|
| Konsep dipakai sebelum diajarkan | `app.get` dan `res.json` muncul di Modul 03 sebelum aplikasi Express dijelaskan; `async` dipakai sejak Modul 02, baru diajarkan di 05a | 2 |
| Konsep disebut lalu ditinggalkan | `next(err)`, stream `Writable`, regex yang "tidak wajib dipahami" | 2 |
| "Maksimal 3 konsep" hanya di atas kertas | 05c Konsep 3 memuat `app.post`, body parser, `express.json`, `app.use`, `?.`, 400/201, `curl -d` | 1 |
| Latihan butuh cara yang baru muncul di soal atau solution | `findIndex`/`splice` di 05c, `res.set` di 05d, middleware di 03 | 2, 6 |
| Hanya 3 dari 16 latihan memenuhi template; ada yang tanpa soal atau tanpa output yang diharapkan | 05c Latihan 1, 05e Latihan 1 | 6 |
| Jawaban pertanyaan pengecekan melebar ke materi lain | 03 Q1 membahas flaky test dan NestJS; 05d Q2 membahas crash `req.log` | 7 |
| Solution tersebar di 4 lokasi, sebagian basi | `src/modules/health/solution/`, `docs/solutions/` | 8 |
| Dokumen jauh lebih banyak dari kode | ±7.080 baris dokumen untuk ±230 baris kode yang diketik (30:1) | 9 |
| Praktik production sebelum dasar | supply chain di modul pertama, request id sebelum middleware | 4 |
| Tidak ada yang memeriksa kode | Response tanpa pembungkus `data` di latihan 05c tidak tertangkap | 6 (grader) |

Dasar riset:
- Urutan kurikulum proyek PHP native [Laracasts PHP For Beginners](https://laracasts.com/series/php-for-beginners-2023-edition): bahasa, superglobal, router, MySQL dan PDO, CRUD, autoload, session, Composer, test. Pola "Programming is Rewriting" di kursus itu sama dengan cara pemula dan utang belajar di sini.
- [Dicoding](https://www.dicoding.com/academies/123), [The Odin Project](https://www.theodinproject.com/paths/full-stack-javascript/courses/nodejs), dan [freeCodeCamp](https://www.freecodecamp.org/learn/back-end-development-and-apis/). Dari freeCodeCamp khususnya: pemeriksa otomatis per latihan.
- Temuan ilmu belajar:
  - Kapasitas memori kerja sekitar 4 potong informasi ([Cowan 2001](https://www.researchgate.net/publication/11830840_The_Magical_Number_4_in_Short-Term_Memory_A_Reconsideration_of_Mental_Storage_Capacity)).
  - Pemula belajar lebih baik dari contoh lengkap ([Kirschner, Sweller, Clark 2006](https://itgs.ict.usc.edu/papers/Constructivism_KirschnerEtAl_EP_06.pdf)).
  - Kurikulum spiral ([Harden](https://med.virginia.edu/faculty-affairs/wp-content/uploads/sites/458/2016/04/2010-3-23.pdf)).
  - Keselarasan tujuan, latihan, dan penilaian ([constructive alignment](https://www.qmul.ac.uk/queenmaryacademy/educators/resources/curriculum-design/constructive-alignment/)).

## Aturan

1. **Maksimal 5 hal baru per modul, dihitung ketat.** Hal baru adalah apa pun yang harus Anda ingat untuk mengerjakan latihan: fungsi atau sintaks PHP (`echo`, `$_GET`, `match`), perintah terminal (`php -S`), header atau status HTTP (`Content-Type`, `201`), dan istilah (share-nothing).
   - Semuanya didaftar di awal modul dalam kotak **"Hal baru (N/5)"**.
   - Istilah pendukung kecil yang tidak perlu diingat untuk latihan (misalnya "terminal") boleh ditambahkan, asal dijelaskan di tempat.
   - Lebih dari 5 berarti modul dipecah dengan sufiks huruf (09a, 09b).
2. **Tidak ada referensi maju.**
   - Setiap identifier di blok kode harus sudah tercatat di [register konsep](../concept-register.md) sebagai diajarkan di modul ini atau sebelumnya. Bila benar-benar tidak bisa dihindari, tandai di tempat: "belum perlu dipahami, dijelaskan di Modul X".
   - Setiap cara yang dibutuhkan latihan harus sudah muncul di badan modul. Soal, petunjuk, dan solution tidak boleh memperkenalkan fungsi baru.
   - Pemanasan hanya menanyakan modul sebelumnya.
   - Setiap istilah teknis ditulis `istilah (penjelasan singkat)` pada kemunculan pertama di setiap dokumen.
3. **Playground dulu, lalu Journaly.** Konsep baru dicoba di `backend/playground/` dengan contoh sekecil mungkin, lalu diterapkan ke backend Journaly bila modulnya menyentuh aplikasi. Modul ditutup dengan milestone yang bisa dicek.
4. **Cara pemula dulu, best practice kemudian.** Bila best practice terlalu berat, tulis versi sederhana yang benar, lalu catat di tabel "Utang belajar" di [progress](../progress.md) lengkap dengan modul pelunasnya. Modul pelunas kembali ke file yang sama dan menunjukkan kode sebelum dan sesudah.
5. **Anda mengetik kode PHP dan SQL.** Mentor tidak menulis kode belajar di `backend/`. Semua kode ada di dokumen modul dengan dua label:
   - **KETIK SENDIRI**: PHP dan SQL yang sedang dipelajari. Maksimal 15 baris per blok, dengan path file dan posisinya ("di bawah baris `$appName`"), langkah berlabel (`// 1. ...`), lalu **Prediksi**, **Jalankan**, dan **output persis**.
   - **SALIN-TEMPEL**: konfigurasi dan boilerplate yang bukan materi modul. Selalu disertai satu kalimat "untuk apa" dan modul yang nanti menjelaskannya.
   - Mentor boleh: menulis `docs/`, menulis `backend/grader/`, dan membuat file serta folder **kosong** yang disebut modul.
6. **Latihan lengkap dan diperiksa grader.** Setiap latihan mengikuti template di bawah tanpa field yang hilang, memetakan satu tujuan modul, dan punya test di `backend/grader/ModulNN/`. Latihan pertama terpandu (kerangka dengan `// TODO`), latihan terakhir mandiri.
7. **Pertanyaan pengecekan selaras dengan materi.** Tiga pertanyaan, masing-masing memetakan satu tujuan. Jawabannya di solution diawali **"Dasar: Konsep N"** dan tidak memuat fakta yang tidak ada di bagian itu.
8. **Solution di satu tempat.** Selalu di `docs/solutions/NN-slug/solution.md`, dengan heading `## Latihan N` dan `## Pertanyaan N`. Setiap soal menautkan jawabannya langsung (`solution.md#latihan-2`). Output di solution berasal dari eksekusi sungguhan. Solution tidak pernah menggantikan penjelasan di modul.
9. **Pendek dan cepat mengetik.** Modul sekitar 250 baris berisi, solution sekitar 120 baris berisi. Baris kosong tidak dihitung karena hanya pemisah Markdown; cara menghitung: `grep -c . <file>`. Di modul yang berisi kode PHP, kode pertama sudah dijalankan sebelum baris 60. Penjelasan sampingan (sejarah, alasan desain panjang, perbandingan) masuk `<details>` yang boleh dilewati.
10. **Laravel di akhir.** Satu bagian "Di Laravel", pendek, hanya memakai istilah yang sudah diajarkan. Istilah Laravel yang belum diajarkan dijelaskan di tempat atau tidak disebut.
11. **Sumber resmi.** Setiap klaim non-trivial punya URL dokumentasi resmi (php.net, dev.mysql.com, RFC, phpunit.de, git-scm.com) di baris "Sumber" di akhir konsep. API tidak pernah ditebak: dokumentasinya dibuka dulu, lalu kodenya dijalankan.
12. **Bahasa.** Penjelasan dalam bahasa Indonesia; kode, identifier, nama file, dan pesan commit dalam bahasa Inggris. Pesan error API tetap bahasa Indonesia sesuai [PRD](../PRD.md). String contoh di playground boleh berbahasa Indonesia. Tidak ada em dash (U+2014).
13. **Simpan pekerjaan dengan branch.** Setiap modul yang mengubah `backend/` ditutup bagian "Simpan pekerjaan" berisi alur branch fitur beserta semua peringatannya (template di bawah).
14. **Diverifikasi sebelum terbit.** Mentor menjalankan modul di salinan repo di scratchpad: menempel blok SALIN-TEMPEL, mengetik solution, menjalankan grader sampai hijau, dan memastikan file kosong atau salah membuat grader merah dengan pesan yang bisa dipahami.

## Template modul

```text
# NN. Judul

Tujuan (2 sampai 3 poin, kata kerja yang bisa diamati: "menulis", "menjalankan", "memprediksi")
Prasyarat | File yang Anda isi | Perkiraan waktu
Hal baru (N/5): daftar bernomor

## Pemanasan               2 sampai 3 pertanyaan dari modul sebelumnya, jawaban di <details>
## Kenapa modul ini ada    masalah nyata yang belum bisa diselesaikan dengan yang sudah diketahui

## Konsep N
  a. Masalah
  b. Definisi (istilah (penjelasan))
  c. Cara kerja
  d. KETIK SENDIRI: langkah berlabel, Prediksi, Jalankan, output persis
  e. Kesalahan umum (maks 2), masing-masing dengan pesan error aslinya
  Sumber: URL resmi

## Latihan               terpandu dulu, lalu mandiri
## Ringkasan             5 poin
## Utang belajar         penyederhanaan di modul ini dan modul pelunasnya (atau "tidak ada")
## Simpan pekerjaan      alur branch (bila modul mengubah backend/)
## Di Laravel            satu bagian pendek
## Pertanyaan pengecekan 3 pertanyaan, masing-masing menautkan jawabannya
```

## Template latihan

```text
### Latihan N: <kata kerja + hasil>          (Tujuan: #2)
Soal:      cerita singkat dalam bahasa sehari-hari
Kontrak:   output persis (skrip terminal) atau method, path, body, status, dan JSON persis (HTTP)
File:      path di backend/, posisi kode
Jalankan:  perintah persis
Harapan:   output persis
Petunjuk:  <details> Petunjuk 1 (konsep mana), Petunjuk 2 (sintaks mana), Petunjuk 3 (hampir kode) </details>
Periksa:   composer grade NN
Jawaban:   link ke solution.md#latihan-n
```

## Template "Simpan pekerjaan"

````text
1. Pastikan Anda di main dan tidak ada perubahan tertunda:
   git switch main
   git status                      -> "nothing to commit, working tree clean"
2. Buat branch fitur:  git switch -c feat/NN-slug
3. Kerjakan modul sampai `composer grade NN` hijau.
4. Periksa lalu pilih file satu per satu:
   git status
   git add <path eksplisit>
   git status                      -> hanya file modul ini di "Changes to be committed"
5. git commit -m "feat(backend): ..."
6. git switch main
   git merge feat/NN-slug
7. git branch -d feat/NN-slug

Peringatan (selalu ditulis lengkap di modul):
- Jangan pindah branch saat masih ada perubahan yang belum di-commit.
- Baca git status sebelum git add. backend/config/config.php tidak boleh pernah muncul di daftar itu.
- Jangan pakai git add -A atau git add . tanpa membaca git status lebih dulu.
- Hapus branch dengan -d (menolak bila belum di-merge), jangan -D.
- Bila merge melaporkan CONFLICT: jalankan git merge --abort, lalu tanya mentor.
- Jangan pernah git reset --hard atau git push --force.
- Branch fitur hanya mengubah backend/. docs/ diubah mentor di main.
- git push ke GitHub opsional dan keputusan Anda. Mentor tidak pernah push.
````

## Checklist sebelum modul dianggap siap

- [ ] Kotak "Hal baru" berisi ≤5 butir, dan setiap hal yang dibutuhkan latihan ada di kotak itu atau di modul sebelumnya.
- [ ] Setiap identifier di blok kode ada di register konsep, atau ditandai "dijelaskan di Modul X".
- [ ] Setiap latihan punya semua field template, test grader, dan link jawaban yang anchor-nya ada.
- [ ] Setiap jawaban pertanyaan diawali "Dasar: Konsep N" dan tidak menambah fakta baru.
- [ ] Setiap blok kode berlabel KETIK SENDIRI atau SALIN-TEMPEL. Blok KETIK SENDIRI ≤15 baris.
- [ ] Semua output berasal dari eksekusi di scratchpad. Grader hijau dengan solution, merah dengan file kosong.
- [ ] Modul ±250 baris berisi, solution ±120 baris berisi (`grep -c . <file>`).
- [ ] Utang belajar, register konsep, glosarium, dan progress diperbarui.
- [ ] Tidak ada em dash: `grep -n $'\u2014' <file>` (zsh) kosong.
