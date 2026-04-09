<?php

return [
    'models' => [
        'chat' => App\Models\Chat::class,
        'message' => App\Models\Message::class,
    ],

    'ai_responder' => App\Services\GeminiService::class,

    'route_enabled' => env('AI_WORKSPACE_ROUTE_ENABLED', true),

    'route_path' => env('AI_WORKSPACE_ROUTE_PATH', '/dashboard'),

    'route_prefix' => env('AI_WORKSPACE_ROUTE_PREFIX', ''),

    'route_name_prefix' => env('AI_WORKSPACE_ROUTE_NAME_PREFIX', ''),

    'route_middleware' => ['auth'],

    'disk' => env('AI_WORKSPACE_DISK', 'public'),

    'upload_path' => env('AI_WORKSPACE_UPLOAD_PATH', 'uploads/chats'),

    'max_file_kb' => (int) env('AI_WORKSPACE_MAX_FILE_KB', 10240),

    'stream_retry_attempts' => (int) env('AI_WORKSPACE_STREAM_RETRY_ATTEMPTS', 2),

    'stream_retry_delay_ms' => (int) env('AI_WORKSPACE_STREAM_RETRY_DELAY_MS', 700),

    'document_extensions' => ['txt', 'md', 'csv', 'json', 'log', 'xml'],

    'document_context_limit' => (int) env('AI_WORKSPACE_DOCUMENT_LIMIT', 12000),

    'history_summary_limit' => (int) env('AI_WORKSPACE_HISTORY_SUMMARY_LIMIT', 140),
];
