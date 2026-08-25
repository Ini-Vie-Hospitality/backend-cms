# Ini Vie Hospitality CMS

Backend CMS dan API untuk Ini Vie Hospitality. Stack: Laravel 13, Inertia.js, React, Vite, Laravel Fortify, Google Analytics, serta AI.

## Daftar Isi

- [Persyaratan](#persyaratan)
- [Setup Lokal](#setup-lokal)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Fitur](#fitur)
  - [CMS Homepage](#cms-homepage)
  - [Google Analytics Dashboard](#google-analytics-dashboard)
  - [AI Knowledge Base RAG](#ai-knowledge-base-rag)
  - [AI Copilot CMS](#ai-copilot-cms)
- [API Publik](#api-publik)
- [Quality Checks](#quality-checks)
- [Deploy Production](#deploy-production)
- [Troubleshooting](#troubleshooting)

## Persyaratan

- PHP `8.3+`
- Composer
- Node.js dan npm
- Database Laravel; MariaDB direkomendasikan untuk pencarian vector RAG
- Ollama untuk embedding, disediakan di luar Docker
- Google Cloud project dengan Google Analytics Data API jika dashboard Analytics digunakan

## Setup Lokal

```bash
cd backend-cms
composer run setup
```

Perintah tersebut memasang dependency Composer, membuat `.env` jika belum ada, membuat `APP_KEY`, menjalankan migrasi, memasang dependency npm, lalu membangun asset frontend.

Untuk database demo bersih:

```bash
php artisan migrate:fresh --seed
```

Seeder membuat akun admin awal:

- Email: `admin@gmail.com`
- Password: `admin123`

Ganti password atau hapus akun ini pada production.

## Konfigurasi Environment

Salin `.env.example` menjadi `.env`, lalu sesuaikan nilai berikut.

| Variabel | Kegunaan |
| --- | --- |
| `APP_URL` | URL CMS Laravel |
| `FRONTEND_WEB_URL` | URL website publik Next.js |
| `HOMEPAGE_PREVIEW_SECRET` | Secret preview homepage |
| `FRONTEND_REVALIDATE_URL` | Endpoint revalidate cache frontend |
| `FRONTEND_REVALIDATE_SECRET` | Secret komunikasi revalidate |
| `DEEPSEEK_API_KEY` | API key provider text DeepSeek |
| `OLLAMA_URL` | URL server Ollama |
| `CONCIERGE_TEXT_PROVIDER` | Provider text AI Concierge |
| `CONCIERGE_DEEPSEEK_MODEL` | Model DeepSeek Concierge |
| `CONCIERGE_EMBEDDING_PROVIDER` | Provider embedding knowledge base |
| `CONCIERGE_EMBEDDING_MODEL` | Model embedding, default `bge-m3:567m` |
| `CONCIERGE_SIMILARITY_THRESHOLD` | Ambang similarity, default `0.55` |
| `CONCIERGE_RESULT_LIMIT` | Batas hasil pencarian, default `20` |
| `GOOGLE_ANALYTICS_PROPERTY_ID` | ID property GA4 |
| `GOOGLE_ANALYTICS_CREDENTIALS` | Path JSON service account Google |
| `GOOGLE_ANALYTICS_CACHE_MINUTES` | Durasi cache Analytics, default `5` menit |
| `COPILOT_TEXT_PROVIDER` | Provider text CMS Copilot |
| `COPILOT_TEXT_MODEL` | Model text CMS Copilot |

### Secret Frontend dan Backend

Nilai kedua secret berikut wajib sama pada kedua aplikasi:

| Backend CMS | Frontend Web |
| --- | --- |
| `backend-cms/.env:HOMEPAGE_PREVIEW_SECRET` | `frontend-web/.env:HOMEPAGE_PREVIEW_SECRET` |
| `backend-cms/.env:FRONTEND_REVALIDATE_SECRET` | `frontend-web/.env:FRONTEND_REVALIDATE_SECRET` |

Gunakan nilai acak yang kuat dan berbeda untuk setiap secret. Jika `HOMEPAGE_PREVIEW_SECRET` tidak sama, preview homepage gagal dan dapat menghasilkan `404`. Jika `FRONTEND_REVALIDATE_SECRET` tidak sama, request revalidation ditolak dengan `403 Forbidden` sehingga homepage dapat tetap menampilkan cache lama.

Jangan commit `.env`, API key, atau JSON credential. Simpan credential Google di luar direktori public dan gunakan path melalui environment variable.

Contoh konfigurasi lokal minimal:

```dotenv
APP_URL=http://localhost:8000
FRONTEND_WEB_URL=http://localhost:3000
DB_CONNECTION=mysql
DB_DATABASE=ini_vie
DB_USERNAME=root
DB_PASSWORD=
OLLAMA_URL=http://127.0.0.1:11434
CONCIERGE_EMBEDDING_PROVIDER=ollama
CONCIERGE_EMBEDDING_MODEL=bge-m3:567m
```

## Menjalankan Aplikasi

Mode development menjalankan Laravel, queue worker, dan Vite bersamaan:

```bash
composer run dev
```

Alternatif manual:

```bash
php artisan serve
php artisan queue:listen --tries=1
npm run dev
php artisan storage:link
```

Build asset production:

```bash
npm run build
```

## Fitur

### CMS Homepage

Section homepage yang dapat dikelola:

- Navbar
- Brand Introduction
- Popup
- Featured Properties
- Culinary
- Wellness
- Membership
- Our Story
- Special Offers
- What's New
- Featured In
- FAQ
- Footer

Workflow konten:

1. Edit data pada mode draft.
2. Gunakan preview sebelum publikasi.
3. Publish perubahan melalui workspace.
4. Gunakan history untuk melihat perubahan.
5. Gunakan rollback untuk mengembalikan versi terdahulu.

Setelah mutasi publikasi berhasil, CMS memanggil `FRONTEND_REVALIDATE_URL` agar cache homepage Next.js diperbarui.

Data homepage publik tersedia melalui `GET /api/homepage` dan `GET /api/homepage/preview`. Preview membutuhkan secret yang sesuai dengan konfigurasi frontend.

### Google Analytics Dashboard

Dashboard tersedia di `/dashboard` dan membutuhkan user yang login serta terverifikasi.

Integrasi memakai GA4 Data API. Dashboard menyediakan active users, sessions, page views, engagement rate, traffic harian, device breakdown, session source, top pages, dan events.

Periode default membandingkan 30 hari berjalan dengan 30 hari sebelumnya. Hasil disimpan dalam cache sesuai `GOOGLE_ANALYTICS_CACHE_MINUTES`.

Konfigurasi Google Cloud:

1. Aktifkan Google Analytics Data API.
2. Buat service account.
3. Berikan akses baca property GA4 kepada email service account.
4. Simpan JSON credential di lokasi aman.
5. Isi `GOOGLE_ANALYTICS_PROPERTY_ID` dan `GOOGLE_ANALYTICS_CREDENTIALS`.

Konfigurasi project ini:

```dotenv
GOOGLE_ANALYTICS_PROPERTY_ID=551482850
GOOGLE_ANALYTICS_CREDENTIALS=g4/ini-vie-c7ee54c100ce.json
```

`GOOGLE_ANALYTICS_PROPERTY_ID` menunjuk property GA4 yang dibaca dashboard. `GOOGLE_ANALYTICS_CREDENTIALS` adalah path relatif dari root `backend-cms`, sehingga contoh tersebut menunjuk ke `backend-cms/g4/ini-vie-c7ee54c100ce.json`. Email service account di dalam credential harus memiliki akses baca ke property GA4 tersebut.

Simpan file JSON hanya di server backend. Jangan commit file credential, jangan menaruhnya dalam direktori `public`, dan jangan membagikannya ke frontend.

Jika API atau credential gagal, dashboard menampilkan status unavailable secara aman tanpa menghentikan CMS.

### AI Knowledge Base RAG

Knowledge Base tersedia di `/cms/concierge/knowledge`.

Fitur utama:

- CRUD knowledge item.
- Status published atau non-published.
- Reindex satu item atau seluruh knowledge base.
- Pencarian semantic berbasis embedding.

Alur RAG:

1. Admin menyimpan knowledge item.
2. Item published dibuatkan embedding.
3. Item non-published tidak ikut pencarian dan embedding-nya dibersihkan.
4. Pertanyaan user diubah menjadi embedding.
5. Sistem mencari item published dengan cosine similarity.
6. Hasil relevan dikirim ke provider text AI sebagai konteks jawaban.

MariaDB digunakan untuk pencarian vector cosine. Pada database non-MariaDB, sistem memakai fallback berupa item published yang dapat dicari dan dibatasi oleh `CONCIERGE_RESULT_LIMIT`.

Jika memakai Ollama, sediakan server di luar Docker lalu unduh model pada server tersebut:

```bash
ollama pull bge-m3:567m
```

Pastikan Ollama berjalan dan dapat diakses dari backend melalui `OLLAMA_URL`; Docker tidak menjalankan atau mengunduh Ollama.

API chat publik:

```http
POST /api/concierge/chat
Content-Type: application/json
```

Body minimal:

```json
{
  "message": "Apa fasilitas membership yang tersedia?"
}
```

`history` bersifat opsional. Response berisi `message`, `language`, `sources`, `suggestions`, dan `handoff`.

Jika knowledge atau provider tidak menghasilkan jawaban tepercaya, sistem mengembalikan handoff aman daripada mengarang informasi.

### AI Copilot CMS

CMS Copilot tersedia pada sidebar form homepage. Endpoint internalnya adalah `POST /cms/copilot/generate`.

Endpoint membutuhkan autentikasi dan memiliki rate limit `12` request per menit.

Copilot dapat membaca konteks form homepage yang didukung, menggunakan nilai field yang diizinkan, memakai URL eksternal sebagai sumber tambahan, lalu menghasilkan balasan dan saran pengisian field.

Copilot hanya menerima field terdaftar dengan batas panjang yang ditentukan aplikasi. Konten URL eksternal dan nilai form dianggap tidak tepercaya. Copilot tidak boleh mengarang harga, ketersediaan, kebijakan, atau konfirmasi booking. Output HTML dan field tidak didukung ditolak.

Konfigurasi provider menggunakan `COPILOT_TEXT_PROVIDER` dan `COPILOT_TEXT_MODEL`. Jika tidak diisi, konfigurasi Concierge digunakan sebagai fallback.

## API Publik

| Method | Endpoint | Kegunaan |
| --- | --- | --- |
| `GET` | `/api/homepage` | Data homepage published |
| `GET` | `/api/homepage/preview` | Data preview homepage |
| `POST` | `/api/concierge/chat` | Pertanyaan ke AI Concierge |

Endpoint CMS dan Copilot membutuhkan autentikasi sesuai route middleware. Jangan mengekspos secret preview, revalidate, API key, atau credential Google ke browser.

## Quality Checks

Jalankan dari direktori `backend-cms`:

```bash
npm run lint:check
npm run format:check
npm run types:check
vendor/bin/pint --test
php artisan test
```

Command gabungan:

```bash
composer run test
```

Test menggunakan SQLite in-memory sesuai konfigurasi PHPUnit.

## Deploy Production

Workflow GitHub Actions berada di `.github/workflows/deploy.yml`. Push ke branch `master` memicu build dan deployment sesuai konfigurasi workflow. Deployment melakukan build asset, migrasi, pembersihan/cache Laravel, serta reload service aplikasi dan queue.

Sebelum deployment:

- Isi GitHub Actions secrets sesuai workflow.
- Pastikan `.env` production tersedia di server.
- Pastikan database dapat diakses.
- Pastikan Ollama dan model tersedia jika RAG aktif.
- Pastikan credential Google tersedia di server, bukan repository.
- Pastikan URL production, revalidate, dan scheme HTTPS benar.

## Troubleshooting

### Asset terkena Mixed Content

Pastikan `APP_URL` memakai scheme yang sama dengan domain CMS. Jika CMS dibuka melalui HTTPS, gunakan `https://` pada `APP_URL`, reverse proxy, dan konfigurasi asset. Jalankan build ulang setelah perubahan environment.

### Dashboard Analytics unavailable

Periksa `GOOGLE_ANALYTICS_PROPERTY_ID`, path `GOOGLE_ANALYTICS_CREDENTIALS`, izin service account pada property GA4, dan log Laravel.

### Jawaban RAG kosong atau tidak relevan

Pastikan knowledge item berstatus published, embedding sudah dibuat, Ollama berjalan, model tersedia, dan threshold tidak terlalu tinggi.

### Homepage belum berubah setelah publish

Periksa `FRONTEND_REVALIDATE_URL`, `FRONTEND_REVALIDATE_SECRET`, konektivitas CMS ke frontend, dan log request revalidation.

### Queue tidak memproses pekerjaan

Jalankan worker untuk diagnosis:

```bash
php artisan queue:listen --tries=1
```

Periksa konfigurasi queue production dan status process manager di server.
