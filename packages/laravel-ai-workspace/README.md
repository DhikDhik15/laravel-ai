# Laravel AI Workspace Package (`msi/laravel-ai-workspace`)

Paket ini adalah modul mandiri *(standalone package)* yang menyediakan fitur antarmuka *(interface)* percakapan AI lengkap dengan penyimpanan riwayat, dukungan lampiran multimedia (gambar, audio, video, & dokumen), serta *streaming* respons secara *real-time* menggunakan Server-Sent Events (SSE).

Project ini dirancang agar bersifat *reusable* sehingga sangat mudah diintegrasikan ke dalam berbagai proyek Laravel.

## ✨ Fitur Utama
- **Multimedia Support**: Unggah dan kirim file gambar, audio, video, serta dokumen (txt, md, dsb) sebagai konteks ke AI.
- **Real-time Streaming**: Respons AI dikirim secara bertahap menggunakan SSE untuk pengalaman pengguna yang lebih responsif.
- **Chat Management**: Manajemen riwayat chat otomatis (buat baru, ambil riwayat, hapus, dan ringkasan judul otomatis).
- **Auto-Routing**: Rute dashboard dan API chat sudah tersedia secara bawaan dan dapat dikonfigurasi.
- **Customizable UI**: View Blade yang siap pakai dan dapat di-*publish* untuk kustomisasi tampilan.

## 📦 Kebutuhan Sistem
- **PHP** `^8.3`
- **Laravel Framework** `^13.0`
- **Laravel AI** (`laravel/ai`) `^0.4.2`

## 🚀 Instalasi

### 1. Tambahkan Package
Karena paket ini berada dalam direktori lokal, tambahkan konfigurasi *path repository* pada `composer.json` proyek Anda:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/laravel-ai-workspace",
        "options": { "symlink": true }
    }
]
```

Lalu jalankan:
```bash
composer require msi/laravel-ai-workspace:*@dev
```

### 2. Jalankan Perintah Instalasi
Paket ini menyediakan perintah automasi untuk mempublikasikan konfigurasi, migrasi, dan aset yang diperlukan:

```bash
php artisan ai-workspace:install
```

### 3. Jalankan Migrasi Database
Siapkan tabel untuk menyimpan riwayat chat dan pesan:
```bash
php artisan migrate
```

## 🛠️ Konfigurasi

Setelah menjalankan `install`, file konfigurasi akan tersedia di `config/ai-workspace.php`. Berikut adalah beberapa opsi utama yang dapat Anda sesuaikan melalui `.env`:

| Key | Environment Variable | Default | Deskripsi |
|-----|----------------------|---------|-----------|
| `route_enabled` | `AI_WORKSPACE_ROUTE_ENABLED` | `true` | Mengaktifkan rute bawaan package. |
| `route_path` | `AI_WORKSPACE_ROUTE_PATH` | `/dashboard` | URL utama untuk antarmuka chat. |
| `disk` | `AI_WORKSPACE_DISK` | `public` | Disk penyimpanan untuk file unggahan. |
| `upload_path` | `AI_WORKSPACE_UPLOAD_PATH` | `uploads/chats` | Direktori penyimpanan file. |
| `max_file_kb` | `AI_WORKSPACE_MAX_FILE_KB` | `10240` | Batas ukuran file per ungguhan (10MB). |

### Hubungkan dengan AI Service
Pastikan Anda mengatur `ai_responder` di `config/ai-workspace.php` ke kelas yang mengimplementasikan `AiWorkspace\Contracts\StreamsChatResponses`. Secara default, ini mengarah ke `App\Services\GeminiService`.

```php
'ai_responder' => App\Services\GeminiService::class,
```

## 🖥️ Penggunaan

### Rute Bawaan
Jika `route_enabled` bernilai `true`, rute berikut akan otomatis tersedia (dilindungi oleh middleware `auth` secara default):
- `GET /dashboard` (atau sesuai `route_path`) -> Menampilkan interface chat.
- `POST /chat/send` -> Mengirim pesan dan file.
- `GET /chat/stream/{chat}/{message}` -> Endpoint streaming SSE.
- `GET/PATCH/DELETE /chats/{chat}` -> Manajemen resource chat.

### Kustomisasi Tampilan
Jika Anda ingin mengubah tampilan antarmuka chat, publish views milik package:
```bash
php artisan vendor:publish --tag=ai-workspace-views
```
File view akan tersedia di `resources/views/vendor/ai-workspace/`.

---
Selamat membangun! Project *AI Workspace* Anda sekarang siap mendukung interaksi AI yang lebih kaya dan interaktif.
