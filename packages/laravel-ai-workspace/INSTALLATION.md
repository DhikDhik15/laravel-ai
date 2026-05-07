# Installation Guide - Laravel AI Workspace

Panduan ini menjelaskan instalasi package `msi/laravel-ai-workspace` dari nol di project Laravel lain, termasuk konfigurasi, verifikasi, dan troubleshooting.

## 1. Prasyarat

- PHP `8.3+`
- Laravel `13+`
- Composer aktif
- Database sudah siap (MySQL/PostgreSQL/SQLite)
- Package `laravel/ai` akan ikut terpasang sebagai dependency

## 2. Install Package

Jalankan di root project Laravel host:

```bash
composer require msi/laravel-ai-workspace
```

## 3. Jalankan Installer Package

Pilihan paling aman untuk instalasi awal:

```bash
php artisan ai-workspace:install --with-stubs --migrate
```

Perintah di atas akan:

- publish config package
- publish dokumentasi package ke `docs/laravel-ai-workspace.md`
- publish migration package
- publish views package
- generate stub model/responder jika belum ada
- menjalankan migration

## 4. Opsi Installer

Gunakan opsi sesuai kebutuhan:

```bash
php artisan ai-workspace:install --migrate
php artisan ai-workspace:install --force
php artisan ai-workspace:install --without-docs
php artisan ai-workspace:install --without-migrations
php artisan ai-workspace:install --without-views
php artisan ai-workspace:install --with-stubs
```

Keterangan singkat:

- `--migrate`: langsung menjalankan migration setelah publish
- `--force`: overwrite file publish/stub yang sudah ada
- `--without-*`: skip publish resource tertentu
- `--with-stubs`: generate stub default untuk `Chat`, `Message`, `AiWorkspaceResponder`

## 5. Hasil File Setelah Install

Secara default, file berikut akan muncul di project host:

- `config/ai-workspace.php`
- `database/migrations/*ai_workspace*`
- `resources/views/vendor/ai-workspace/*`
- `docs/laravel-ai-workspace.md`

Jika pakai `--with-stubs`, juga akan dibuat:

- `app/Models/Chat.php`
- `app/Models/Message.php`
- `app/Services/AiWorkspaceResponder.php`

## 6. Konfigurasi Wajib

Set di `.env`:

```env
AI_WORKSPACE_CHAT_MODEL=App\\Models\\Chat
AI_WORKSPACE_MESSAGE_MODEL=App\\Models\\Message
AI_WORKSPACE_RESPONDER=App\\Services\\AiWorkspaceResponder
```

Atau set langsung di `config/ai-workspace.php`:

```php
'models' => [
    'chat' => App\Models\Chat::class,
    'message' => App\Models\Message::class,
],

'ai_responder' => App\Services\AiWorkspaceResponder::class,
```

## 7. Implementasi Responder (Wajib)

Jika pakai stub bawaan, edit `app/Services/AiWorkspaceResponder.php` karena method `stream()` default masih melempar exception.

Minimal, class responder harus:

- implement `AiWorkspace\Contracts\StreamsChatResponses`
- implement method:
  - `generate(Model $chat): string`
  - `stream(Model $chat, ?int $messageId = null): StreamableAgentResponse`

## 8. Konfigurasi Route

Default route config:

```php
'route_enabled' => true,
'route_path' => '/dashboard',
'route_prefix' => '',
'route_name_prefix' => '',
'route_middleware' => ['auth'],
```

Jika bentrok route di host app:

- ubah `route_prefix` untuk prefix URL
- ubah `route_name_prefix` untuk prefix nama route

Contoh:

```env
AI_WORKSPACE_ROUTE_PREFIX=workspace-ai
AI_WORKSPACE_ROUTE_NAME_PREFIX=aiw.
AI_WORKSPACE_ROUTE_PATH=/assistant
```

## 9. Verifikasi Instalasi

1. Cek command tersedia:

```bash
php artisan list | grep ai-workspace
```

2. Cek route terdaftar:

```bash
php artisan route:list
```

3. Cek migration status:

```bash
php artisan migrate:status
```

4. Jalankan aplikasi:

```bash
php artisan serve
```

## 10. Upgrade / Reinstall Aman

Jika update package dan ingin publish ulang:

```bash
php artisan ai-workspace:install --force
```

Jika hanya ingin refresh config:

```bash
php artisan vendor:publish --tag=ai-workspace-config --force
```

## 11. Troubleshooting

### A. `Configured AI responder class is invalid`

Penyebab:

- `AI_WORKSPACE_RESPONDER` belum diisi
- class tidak ada
- class tidak implement interface yang benar

Solusi:

- pastikan class ada
- pastikan namespace benar
- pastikan class implement `StreamsChatResponses`

### B. Route bentrok dengan project host

Solusi:

- set `AI_WORKSPACE_ROUTE_PREFIX`
- set `AI_WORKSPACE_ROUTE_NAME_PREFIX`
- jalankan `php artisan optimize:clear`

### C. Migration bentrok tabel existing

Migration package bersifat idempotent untuk tabel utama, namun tetap pastikan struktur tabel host sesuai model yang dipakai.

### D. Build frontend gagal karena asset/logo

Pastikan file di folder `public` benar-benar ada, lalu rebuild:

```bash
npm run build
```

## 12. Rekomendasi Workflow Tim

Untuk tim, disarankan:

1. install package di branch fitur
2. commit hasil publish yang memang perlu ditrack
3. jalankan test dan build
4. merge hanya jika CI hijau

