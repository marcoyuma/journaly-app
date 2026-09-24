# Journaly

Journaly adalah jurnal harian pribadi: login, lalu membuat, membaca, mengubah, dan menghapus catatan yang punya judul dan isi.

Repo ini adalah **proyek belajar**. Frontend (Next.js) sudah jadi. Backend (PHP native, tanpa framework) dibangun ulang dari nol, modul demi modul, oleh orang yang sedang belajar backend. Jembatan akhirnya: Laravel.

## Mulai belajar

1. Baca [progress](docs/progress.md) untuk tahu modul mana yang sedang berjalan.
2. Peta seluruh modul ada di [roadmap](docs/modules/00-roadmap.md).
3. Modul pertama: [00. Persiapan](docs/modules/00-persiapan.md).

Dokumen lain:

| Dokumen | Isi |
|---|---|
| [PRD](docs/PRD.md) | Apa yang dibangun, dan kontrak API persis yang diharapkan frontend |
| [ARCHITECTURE](docs/ARCHITECTURE.md) | Bagaimana backend disusun, versi alat, catatan keputusan |
| [Panduan modul](docs/modules/00-panduan-modul.md) | Aturan penulisan setiap modul |
| [Register konsep](docs/concept-register.md) | Konsep apa diajarkan di modul mana |
| [Glosarium](docs/glossary.md) | Arti setiap istilah teknis |

## Struktur repo

```text
backend/     PHP API yang Anda tulis sendiri
  grader/    test pemeriksa latihan (ditulis mentor, jangan diubah)
  playground/ file latihan per modul
frontend/    Next.js, sudah jadi, tidak diubah
docs/        kurikulum: modul, solution, progress
```

## Menjalankan frontend (mulai Modul 22)

```bash
cd frontend
npm install
npm run dev
```

Buka http://localhost:3000. Frontend meneruskan setiap request `/api/*` ke backend di `http://localhost:8000`.
