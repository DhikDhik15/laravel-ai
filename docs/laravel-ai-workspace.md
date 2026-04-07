# Laravel AI Workspace

Package foundation untuk memisahkan fitur AI workspace dari aplikasi host Laravel dan mendorongnya menjadi modul reusable lintas project.

## Tujuan
- Menyediakan fondasi chat AI reusable
- Menyatukan logic summary chat, attachment handling, payload transform, dan lifecycle chat
- Membuat AI driver serta model host bisa diganti lewat config
- Menjadi titik awal untuk publish config, routes, views, migrations, dan assets ke project Laravel lain

## Komponen yang sudah diekstrak
- `AiWorkspace\\AiWorkspaceServiceProvider`
- `AiWorkspace\\Contracts\\StreamsChatResponses`
- `AiWorkspace\\Support\\WorkspaceModelResolver`
- `AiWorkspace\\Support\\ChatSummaryResolver`
- `AiWorkspace\\Support\\AttachmentPreparer`
- `AiWorkspace\\Support\\ChatPayloadTransformer`
- `AiWorkspace\\Support\\ChatLifecycleManager`
- `AiWorkspace\\Commands\\InstallAiWorkspaceCommand`

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
1. Publish config: `php artisan ai-workspace:install`
2. Arahkan `models.chat` dan `models.message` ke model Eloquent milik aplikasi host
3. Arahkan `ai_responder` ke service AI host yang mengimplementasikan `AiWorkspace\\Contracts\\StreamsChatResponses`
4. Gunakan service package seperti `ChatLifecycleManager` dan `ChatPayloadTransformer` di controller host

## Langkah pengembangan berikutnya
1. Pindahkan route, controller, dan view chat ke package publishable resources
2. Tambahkan migration publish untuk tabel chat/message default
3. Tambahkan contracts untuk authorization, title generation, dan summary strategy
4. Tambahkan test package terpisah untuk host app dan package behavior
5. Sediakan preset install agar package bisa langsung dipakai di project Laravel baru
