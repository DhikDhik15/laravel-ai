# Laravel AI Workspace (`msi/laravel-ai-workspace`)

Reusable Laravel package for AI chat workspace with persistent history, file attachments, and SSE streaming responses.

## Features
- Chat history with title and summary metadata
- Attachments for image, audio, video, and text documents
- Streaming AI responses via Server-Sent Events (SSE)
- Blade UI that can be published and customized
- Configurable models and responder implementation

## Requirements
- PHP `^8.3`
- Laravel `^13.0`
- `laravel/ai` `^0.4.2`

## Install in Another Laravel App

1. Require package

```bash
composer require msi/laravel-ai-workspace
```

2. Publish resources

```bash
php artisan ai-workspace:install
```

Optional install variants:

```bash
php artisan ai-workspace:install --migrate
php artisan ai-workspace:install --force
php artisan ai-workspace:install --without-views
```

3. Configure responder and models in `config/ai-workspace.php`

```php
'models' => [
    'chat' => App\Models\Chat::class,
    'message' => App\Models\Message::class,
],

'ai_responder' => App\Services\GeminiService::class,
```

## Route Configuration

```php
'route_enabled' => true,
'route_path' => '/dashboard',
'route_prefix' => '',
'route_name_prefix' => '',
'route_middleware' => ['auth'],
```

Tips:
- Use `route_prefix` if host app already has `/chat/*` routes.
- Use `route_name_prefix` if host app already has route names like `dashboard` or `messages.send`.

## Migration Safety
Package migrations are idempotent for `chats` and `messages` table creation. If the table already exists, migration will skip creation.

## Development Roadmap
1. Add automated tests for install command and route prefixed mode.
2. Add provider adapters (Gemini/OpenAI/Anthropic) via one responder interface.
3. Add event hooks (message_sent, stream_started, stream_finished).
4. Add authorization policy hooks for chat/message actions.
5. Prepare semantic versioning and release notes for Packagist distribution.
