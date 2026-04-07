<?php

namespace App\Http\Controllers;

use AiWorkspace\Contracts\StreamsChatResponses;
use AiWorkspace\Support\AttachmentPreparer;
use AiWorkspace\Support\ChatLifecycleManager;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Laravel\Ai\Streaming\Events\StreamEnd;
use Laravel\Ai\Streaming\Events\TextDelta;

class MessageController extends Controller
{
    public function __construct(
        private StreamsChatResponses $responder,
        private AttachmentPreparer $attachments,
        private ChatLifecycleManager $lifecycle
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

    public function stream(Request $request, Chat $chat, Message $message): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless($chat->user_id === $request->user()->id, 404);
        abort_unless($message->chat_id === $chat->id && $message->role === 'user', 404);

        return response()->stream(function () use ($chat, $message) {
            $reply = '';

            try {
                $stream = $this->responder->stream($chat->fresh(), $message->id);

                foreach ($stream as $event) {
                    if ($event instanceof TextDelta) {
                        $reply .= $event->delta;
                        echo "event: delta\n";
                        echo 'data: ' . json_encode(['delta' => $event->delta]) . "\n\n";
                        @ob_flush();
                        @flush();
                    }

                    if ($event instanceof StreamEnd) {
                        $assistantMessage = $this->lifecycle->storeAssistantMessage($chat, $reply);
                        $chat = $chat->fresh();

                        echo "event: done\n";
                        echo 'data: ' . json_encode([
                            'assistant_message' => [
                                'id' => $assistantMessage->getKey(),
                                'role' => 'assistant',
                                'content' => $reply,
                                'html' => Str::markdown($reply),
                            ],
                            'chat' => $this->lifecycle->chatPayload($chat),
                        ]) . "\n\n";
                        @ob_flush();
                        @flush();
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('SSE Stream Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
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
