<?php

namespace AiWorkspace\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatLifecycleManager
{
    public function __construct(
        private WorkspaceModelResolver $models,
        private ChatSummaryResolver $summaries
    ) {
    }

    public function findUserChat(int $userId, ?int $chatId): ?Model
    {
        if (! $chatId) {
            return null;
        }

        return $this->models->newChatQuery()
            ->where('user_id', $userId)
            ->find($chatId);
    }

    public function createChat(int $userId, string $text, array $attachments): Model
    {
        $chat = $this->models->newChatInstance();
        $chat->fill([
            'user_id' => $userId,
            'title' => $this->makeTitle($text, $attachments),
            'summary' => $text !== ''
                ? Str::limit($text, config('ai-workspace.history_summary_limit', 140))
                : 'Chat baru dimulai dengan file.',
            'last_message_at' => now(),
        ]);
        $chat->save();

        return $chat;
    }

    public function touchChat(Model $chat, string $text, array $attachments): Model
    {
        $chat->forceFill([
            'title' => $chat->title ?: $this->makeTitle($text, $attachments),
            'summary' => $text !== ''
                ? Str::limit($text, config('ai-workspace.history_summary_limit', 140))
                : ($chat->summary ?: 'Chat dengan lampiran file.'),
            'last_message_at' => now(),
        ])->save();

        return $chat;
    }

    public function storeUserMessage(Model $chat, string $text, array $attachments): Model
    {
        $message = $this->models->newMessageInstance();
        $message->fill([
            'chat_id' => $chat->getKey(),
            'role' => 'user',
            'content' => $text,
            'files' => $attachments,
        ]);
        $message->save();

        return $message;
    }

    public function storeAssistantMessage(Model $chat, string $reply): Model
    {
        $message = $this->models->newMessageInstance();
        $message->fill([
            'chat_id' => $chat->getKey(),
            'role' => 'assistant',
            'content' => $reply,
            'files' => [],
        ]);
        $message->save();

        $chat->forceFill([
            'last_message_at' => now(),
            'summary' => Str::limit(strip_tags($reply), config('ai-workspace.history_summary_limit', 140)),
        ])->save();

        return $message;
    }

    public function makeTitle(string $text, array $attachments): string
    {
        if ($text !== '') {
            return Str::limit($text, 50, '...');
        }

        return 'Chat baru (' . count($attachments) . ' file)';
    }

    public function chatPayload(Model $chat): array
    {
        return [
            'id' => $chat->getKey(),
            'title' => $chat->title,
            'summary' => $this->summaries->fromChat($chat),
            'last_message_at' => optional($chat->last_message_at)->toISOString(),
        ];
    }
}
