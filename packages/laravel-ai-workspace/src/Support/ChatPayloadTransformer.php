<?php

namespace AiWorkspace\Support;

use Illuminate\Database\Eloquent\Model;

class ChatPayloadTransformer
{
    public function __construct(private ChatSummaryResolver $summaryResolver)
    {
    }

    public function transform(Model $chat, bool $withMessages = false): array
    {
        $payload = [
            'id' => $chat->getKey(),
            'title' => $chat->title ?: 'Chat tanpa judul',
            'summary' => $this->summaryResolver->fromChat($chat),
            'last_message_at' => optional($chat->last_message_at)->toISOString(),
            'created_at' => optional($chat->created_at)->toISOString(),
            'messages_count' => $chat->messages_count ?? $chat->messages()->count(),
        ];

        if ($withMessages) {
            $payload['messages'] = $chat->messages->map(fn ($message) => [
                'id' => $message->getKey(),
                'role' => $message->role,
                'content' => $message->content,
                'files' => $message->files ?? [],
                'created_at' => optional($message->created_at)->toISOString(),
            ])->all();
        }

        return $payload;
    }
}
