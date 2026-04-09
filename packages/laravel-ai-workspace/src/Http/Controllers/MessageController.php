<?php

namespace AiWorkspace\Http\Controllers;

use AiWorkspace\Contracts\StreamsChatResponses;
use AiWorkspace\Support\AttachmentPreparer;
use AiWorkspace\Support\ChatLifecycleManager;
use AiWorkspace\Support\WorkspaceModelResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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
            'stream_url' => route($this->routeName('messages.stream'), ['chat' => $chat->getKey(), 'message' => $userMessage->getKey()]),
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
            $attempt = 0;
            $maxAttempts = max(1, (int) config('ai-workspace.stream_retry_attempts', 2));
            $retryDelayMs = max(0, (int) config('ai-workspace.stream_retry_delay_ms', 700));

            while ($attempt < $maxAttempts) {
                try {
                    $attempt++;
                    $stream = $this->responder->stream($chatModel->fresh(), (int) $messageModel->getKey());

                    foreach ($stream as $event) {
                        if ($event instanceof TextDelta) {
                            $reply .= $event->delta;
                            $this->emitSseEvent('delta', ['delta' => $event->delta]);
                        }

                        if ($event instanceof StreamEnd) {
                            $assistantMessage = $this->lifecycle->storeAssistantMessage($chatModel, $reply);
                            $freshChat = $chatModel->fresh();

                            $this->emitSseEvent('done', [
                                'assistant_message' => [
                                    'id' => $assistantMessage->getKey(),
                                    'role' => 'assistant',
                                    'content' => $reply,
                                    'html' => Str::markdown($reply),
                                ],
                                'chat' => $this->lifecycle->chatPayload($freshChat),
                            ]);

                            return;
                        }
                    }
                } catch (\Throwable $e) {
                    $retryable = $this->isRetryableAiError($e);

                    Log::warning('SSE Stream attempt failed', [
                        'chat_id' => $chatModel->getKey(),
                        'message_id' => $messageModel->getKey(),
                        'attempt' => $attempt,
                        'max_attempts' => $maxAttempts,
                        'retryable' => $retryable,
                        'error' => $e->getMessage(),
                    ]);

                    if ($retryable && $reply === '' && $attempt < $maxAttempts) {
                        $this->emitSseEvent('status', [
                            'message' => "Koneksi ke layanan AI terputus, mencoba ulang ({$attempt}/{$maxAttempts})...",
                        ]);

                        if ($retryDelayMs > 0) {
                            usleep($retryDelayMs * 1000);
                        }

                        continue;
                    }

                    $this->emitSseEvent('error', [
                        'message' => $this->friendlyAiErrorMessage($e),
                        'retryable' => $retryable,
                    ]);

                    return;
                }
            }

            $this->emitSseEvent('error', [
                'message' => 'Layanan AI sedang tidak tersedia. Silakan coba lagi sebentar.',
                'retryable' => true,
            ]);
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function routeName(string $name): string
    {
        return (string) config('ai-workspace.route_name_prefix', '') . $name;
    }

    private function emitSseEvent(string $event, array $payload): void
    {
        echo "event: {$event}\n";
        echo 'data: ' . json_encode($payload) . "\n\n";
        @ob_flush();
        @flush();
    }

    private function isRetryableAiError(\Throwable $e): bool
    {
        if ($e instanceof \Illuminate\Http\Client\ConnectionException) {
            return true;
        }

        $message = Str::lower($e->getMessage());

        return Str::contains($message, [
            'connection refused',
            'could not connect',
            'could not resolve host',
            'failed to connect',
            'operation timed out',
            'timed out',
            'network is unreachable',
            'temporary failure',
            'ssl connection timeout',
            'curl error 6',
            'curl error 7',
            'curl error 28',
        ]);
    }

    private function friendlyAiErrorMessage(\Throwable $e): string
    {
        $message = Str::lower($e->getMessage());

        if ($this->isRetryableAiError($e)) {
            return 'Koneksi ke layanan AI sedang bermasalah. Silakan coba kirim ulang dalam beberapa saat.';
        }

        if (Str::contains($message, ['api key', 'unauthorized', 'permission denied', 'forbidden', 'invalid authentication'])) {
            return 'Konfigurasi layanan AI belum valid (API key/izin akses). Mohon cek pengaturan server.';
        }

        if (Str::contains($message, ['quota', 'rate limit', 'resource exhausted', 'too many requests'])) {
            return 'Kuota atau batas permintaan AI sedang tercapai. Coba lagi beberapa menit lagi.';
        }

        return 'Terjadi gangguan saat menghubungi layanan AI. Silakan coba lagi.';
    }
}
