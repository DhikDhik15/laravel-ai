<?php

namespace AiWorkspace\Http\Controllers;

use AiWorkspace\Contracts\StreamsChatResponses;
use AiWorkspace\Support\AttachmentPreparer;
use AiWorkspace\Support\ChatLifecycleManager;
use AiWorkspace\Support\WorkspaceModelResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Laravel\Ai\Streaming\Events\StreamEnd;
use Laravel\Ai\Streaming\Events\TextDelta;

class MessageController
{
    public function __construct(
        private StreamsChatResponses $responder,
        private AttachmentPreparer $attachments,
        private ChatLifecycleManager $lifecycle,
        private WorkspaceModelResolver $models
    ) {
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chat_id' => ['nullable', 'integer'],
            'message' => ['nullable', 'string'],
            'files.*' => ['nullable', 'file', 'max:' . config('ai-workspace.max_file_kb', 10240)],
        ]);

        $text = trim((string) ($validated['message'] ?? ''));
        $attachments = $this->attachments->fromRequest($request);

        if ($text === '' && empty($attachments)) {
            return response()->json([
                'error' => 'Message atau file tidak boleh kosong',
            ], 422);
        }

        $chat = $this->lifecycle->findUserChat($request->user()->id, $validated['chat_id'] ?? null)
            ?? $this->lifecycle->createChat($request->user()->id, $text, $attachments);

        $userMessage = $this->lifecycle->storeUserMessage($chat, $text, $attachments);
        $this->lifecycle->touchChat($chat, $text, $attachments);

        return response()->json([
            'chat_id' => $chat->getKey(),
            'chat' => $this->lifecycle->chatPayload($chat->fresh()),
            'user_message' => [
                'id' => $userMessage->getKey(),
                'role' => 'user',
                'content' => $text,
                'files' => $attachments,
            ],
            'stream_url' => route('messages.stream', ['chat' => $chat->getKey(), 'message' => $userMessage->getKey()]),
        ]);
    }

    public function stream(Request $request, int $chat, int $message): Response
    {
        $chatModel = $this->models->newChatQuery()
            ->where('user_id', $request->user()->id)
            ->findOrFail($chat);

        $messageModel = $this->models->newMessageQuery()
            ->where('chat_id', $chatModel->getKey())
            ->where('role', 'user')
            ->findOrFail($message);

        return response()->stream(function () use ($chatModel, $messageModel) {
            $reply = '';

            try {
                $stream = $this->responder->stream($chatModel->fresh(), (int) $messageModel->getKey());

                foreach ($stream as $event) {
                    if ($event instanceof TextDelta) {
                        $reply .= $event->delta;
                        echo "event: delta\n";
                        echo 'data: ' . json_encode(['delta' => $event->delta]) . "\n\n";
                        @ob_flush();
                        @flush();
                    }

                    if ($event instanceof StreamEnd) {
                        $assistantMessage = $this->lifecycle->storeAssistantMessage($chatModel, $reply);
                        $freshChat = $chatModel->fresh();

                        echo "event: done\n";
                        echo 'data: ' . json_encode([
                            'assistant_message' => [
                                'id' => $assistantMessage->getKey(),
                                'role' => 'assistant',
                                'content' => $reply,
                                'html' => Str::markdown($reply),
                            ],
                            'chat' => $this->lifecycle->chatPayload($freshChat),
                        ]) . "\n\n";
                        @ob_flush();
                        @flush();
                    }
                }
            } catch (\Throwable $e) {
                echo "event: error\n";
                echo 'data: ' . json_encode(['message' => $e->getMessage()]) . "\n\n";
                @ob_flush();
                @flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
