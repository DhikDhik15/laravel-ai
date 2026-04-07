<?php

namespace AiWorkspace\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatSummaryResolver
{
    public function fromChat(Model $chat): string
    {
        $summary = trim((string) ($chat->summary ?? ''));

        if ($summary !== '') {
            return $summary;
        }

        $message = $chat->relationLoaded('latestMessage')
            ? $chat->latestMessage
            : $chat->latestMessage()->first();

        return $this->fromMessage($message);
    }

    public function fromMessage(?Model $message): string
    {
        if (! $message) {
            return 'Chat baru dimulai.';
        }

        $content = trim((string) ($message->content ?? ''));

        if ($content !== '') {
            return Str::limit($content, config('ai-workspace.history_summary_limit', 140));
        }

        $files = $message->files ?? [];

        if (is_array($files) && count($files) > 0) {
            return 'Chat terakhir berisi lampiran file.';
        }

        return 'Chat baru dimulai.';
    }
}
