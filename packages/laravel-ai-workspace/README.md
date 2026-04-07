# Laravel AI Workspace

Package foundation untuk memisahkan fitur AI workspace dari aplikasi host Laravel dan mendorongnya menjadi modul reusable lintas project.

## Tujuan
- Menyediakan fondasi chat AI reusable
- Menyatukan logic summary chat, attachment handling, payload transform, dan lifecycle chat
- Membuat AI driver serta model host bisa diganti lewat config
- Menjadi titik awal untuk publish config, migrations, routes, views, dan assets ke project Laravel lain

## Komponen yang sudah diekstrak
- `AiWorkspace\\AiWorkspaceServiceProvider`
- `AiWorkspace\\Contracts\\StreamsChatResponses`
- `AiWorkspace\\Support\\WorkspaceModelResolver`
- `AiWorkspace\\Support\\ChatSummaryResolver`
- `AiWorkspace\\Support\\AttachmentPreparer`
- `AiWorkspace\\Support\\ChatPayloadTransformer`
- `AiWorkspace\\Support\\ChatLifecycleManager`
- `AiWorkspace\\Commands\\InstallAiWorkspaceCommand`
- publishable migrations untuk tabel `chats` dan `messages`

## Konfigurasi penting
File config `ai-workspace.php` sekarang mendukung:
- `models.chat`
- `models.message`
- `ai_responder`
- `disk`
- `upload_path`
- `max_file_kb`
- `document_extensions`
- `document_context_limit`
- `history_summary_limit`

## Cara integrasi host app
1. Tambahkan package ini lewat Composer
2. Jalankan `php artisan ai-workspace:install`
3. Jalankan `php artisan migrate`
4. Arahkan `models.chat` dan `models.message` ke model Eloquent milik aplikasi host
5. Arahkan `ai_responder` ke service AI host yang mengimplementasikan `AiWorkspace\\Contracts\\StreamsChatResponses`
6. Gunakan service package seperti `ChatLifecycleManager` dan `ChatPayloadTransformer` di controller host

## Langkah pengembangan berikutnya
1. Pindahkan route, controller, dan view chat ke package publishable resources
2. Tambahkan contracts untuk authorization, title generation, dan summary strategy
3. Tambahkan presenter atau API resources default untuk payload frontend
4. Tambahkan test package terpisah untuk host app dan package behavior
5. Sediakan preset install agar package bisa langsung dipakai di project Laravel baru
