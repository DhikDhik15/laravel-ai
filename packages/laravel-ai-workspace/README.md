# Laravel AI Workspace Package (`msi/laravel-ai-workspace`)

Paket ini adalah modul mandiri *(standalone package)* yang menyediakan fitur antarmuka *(interface)* percakapan AI lengkap dengan penyimpanan riwayat, dukungan lampiran dokumen (teks & gambar), serta *streaming* respons secara *real-time*.

Project ini dirancang agar bersifat *reusable* sehingga sangat bisa digunakan pada *project* Laravel lain.

## 📦 Kebutuhan Sistem
- **PHP** `^8.3`
- **Laravel Framework** `^13.0`
- **Laravel AI** (`laravel/ai`) `^0.4.2`  

## 🚀 Instalasi pada Proyek Laravel Lain

Karena paket ini saat ini disiapkan di struktur sumber daya lokal (`packages/laravel-ai-workspace`), Anda perlu menyalinnya ke proyek Anda yang lain atau mengatur repositori pada `composer.json` lokal.

### Cara 1: Menggunakan Path Repository (Local Symlink)
 
1. **Salin Direktori**
   Salin direktori `packages/laravel-ai-workspace` dari sumber aslinya ke proyek Laravel baru Anda, misalnya ditempatkan di `{Proyek_Baru}/packages/laravel-ai-workspace`.     
 
2. **Daftarkan di `composer.json`**
   Buka file `composer.json` di *root* proyek Laravel baru Anda dan tambahkan *repository* tipe *path*:
   ```json
   "repositories": [
       {
           "type": "path",
           "url": "packages/laravel-ai-workspace",
           "options": {
               "symlink": false
           }
       }
   ]
   ```

3. **Install Package**
   Jalankan perintah instalasi Composer untuk menarik modul tersebut:
   ```bash
   composer require msi/laravel-ai-workspace:*@dev
   ```

4. **Publish Konfigurasi & Migrasi**
   Publish aset dan *migration* milik package ini:
   ```bash
   php artisan vendor:publish --provider="AiWorkspace\AiWorkspaceServiceProvider"
   ```

5. **Jalankan Migrasi Database**
   Pastikan tabel `chats` dan `messages` terbuat di dalam database Anda:
   ```bash
   php artisan migrate
   ```

## 🛠️ Konfigurasi & Cara Penggunaan

Setelah terinstal, Anda dapat langsung mengonfigurasi dan memasangnya ke tampilan pengguna Anda:

1. **Mendefinisikan *Route* (Penting!)**
   Secara *default*, *package* ini menangani alur internal, tetapi keamanan sesi dan kontrol tampilan diserahkan kepada proyek inangnya. Anda wajib men-daftarkan rute-rute *controller* ke dalam `routes/web.php` milik aplikasi Anda di dalam grup berpelindung keamanan otentikasi (`auth` middleware):

   ```php
   use App\Http\Controllers\DashboardController;
   use App\Http\Controllers\ChatController;
   use App\Http\Controllers\MessageController;

   Route::middleware(['web', 'auth'])->group(function () {
       // Tampilan dashboard utama
       Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

       // Rute kontrol manajemen chat
       Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
       Route::patch('/chats/{chat}', [ChatController::class, 'update'])->name('chats.update');
       Route::delete('/chats/{chat}', [ChatController::class, 'destroy'])->name('chats.destroy');

       // Rute interaksi pesan AI
       Route::post('/chat/send', [MessageController::class, 'send'])->name('messages.send');
       Route::get('/chat/stream/{chat}/{message}', [MessageController::class, 'stream'])->name('messages.stream');
   });
   ```

   *(Catatan: Anda bebas menggunakan dan menyesuaikan kode Controller dari proyek contoh `laravel-ai` dan memasukkannya ke direktori `app/Http/Controllers` di proyek baru Anda)*.

2. **Pengaturan `.env` (Gemini & Driver)**
   Atur kunci API model *Generative AI* yang akan dihubungkan, misalnya:
   ```dotenv
   GEMINI_API_KEY="kunci-api-Anda-di-sini"
   ```

3. **Gunakan View Dashboard Anda**
   Package akan menangani logika bisnis chat (manajemen *lifecycle*, simpan-mendeteksi, dan konversi pesan balasan *streaming* AI Workspace Interface). Anda cukup menyediakan antarmuka pengguna (contoh `dashboard.blade.php`) dan menghubungkan fungsi-fungsi API Javascript-nya dengan titik rute di atas.

Selamat! Project *AI Workspace* Anda sudah terintegrasi dan siap digunakan oleh pengguna di berbagai macam versi project Laravel Anda!
