<?php

namespace App\Services;

use AiWorkspace\Contracts\StreamsChatResponses;
use App\Ai\Agents\SupportAgent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Ai\Files\Base64Image;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Responses\StreamableAgentResponse;

class GeminiService implements StreamsChatResponses
{
    public function generate(Model $chat): string
    {
        try {
            $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

            if ($messages->isEmpty()) {
                throw new \InvalidArgumentException('No messages available in this chat.');
            }

            $lastMessage = $messages->pop();

            $history = $messages->map(function ($msg) {
                if ($msg->role === 'assistant') {
                    return new AssistantMessage($msg->content);
                }

                return $this->createUserMessage($msg);
            })->toArray();

            $promptMessage = $this->createUserMessage($lastMessage);

            $agent = new SupportAgent($history);
            $response = $agent->prompt($promptMessage->content, $promptMessage->attachments->all());

            return trim((string) $response);
        } catch (\Throwable $e) {
            Log::error('Gemini API Error', [
                'chat_id' => $chat->id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function stream(Model $chat, ?int $messageId = null): StreamableAgentResponse
    {
        try {
            [$history, $promptMessage] = $this->buildConversation($chat, $messageId);

            $agent = new SupportAgent($history);

            return $agent->stream($promptMessage->content, $promptMessage->attachments->all());
        } catch (\Throwable $e) {
            Log::error('Gemini API Stream Error', [
                'chat_id' => $chat->id ?? 'unknown',
                'message_id' => $messageId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    protected function buildConversation(Model $chat, ?int $messageId = null): array
    {
        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        if ($messages->isEmpty()) {
            throw new \InvalidArgumentException('No messages available in this chat.');
        }

        $promptSource = $messageId
            ? $messages->firstWhere('id', $messageId)
            : $messages->last();

        if (! $promptSource) {
            throw new \InvalidArgumentException('Prompt message was not found.');
        }

        $historyMessages = $messages->filter(fn ($message) => $message->id !== $promptSource->id)->values();

        $history = $historyMessages->map(function ($msg) {
            if ($msg->role === 'assistant') {
                return new AssistantMessage($msg->content);
            }

            // Optimization: Only send text for history messages to save tokens/rate-limit
            // Attachments are only needed for the current active prompt.
            return new UserMessage(Str::of($msg->content ?? '')->trim()->toString());
        })->toArray();

        return [$history, $this->createUserMessage($promptSource)];
    }

    protected function createUserMessage($msg): UserMessage
    {
        $attachments = [];
        $documentContext = [];

        if (! empty($msg->files) && is_array($msg->files)) {
            foreach ($msg->files as $file) {
                if (is_string($file)) {
                    $attachments[] = new Base64Image($file, 'image/jpeg');
                    continue;
                }

                if (! is_array($file)) {
                    continue;
                }

                if (($file['type'] ?? null) === 'image') {
                    if (! empty($file['base64'])) {
                        $attachments[] = new \Laravel\Ai\Files\Base64Image($file['base64'], $file['mime'] ?? 'image/jpeg');
                    } elseif (! empty($file['path'])) {
                        $attachments[] = new \Laravel\Ai\Files\StoredImage($file['path'], $file['disk'] ?? 'public');
                    }
                }

                $isVoiceNote = str_starts_with($file['name'] ?? '', 'Voice_Note_');
                
                // Force audio MIME for voice notes to avoid Gemini expecting video frames
                $isOgg = str_ends_with($file['name'] ?? '', '.ogg') || str_contains($file['mime'] ?? '', 'ogg');
                $mime = $isVoiceNote ? ($isOgg ? 'audio/ogg' : 'audio/webm') : ($file['mime'] ?? 'audio/webm');

                Log::info('Gemini Attachment Debug:', [
                    'name' => $file['name'] ?? 'unknown',
                    'type' => $file['type'] ?? 'unknown',
                    'mime' => $mime,
                    'is_voice_note' => $isVoiceNote
                ]);

                if ((($file['type'] ?? null) === 'audio' || $isVoiceNote) && ! empty($file['path'])) {
                    if ($content = \Illuminate\Support\Facades\Storage::disk($file['disk'] ?? 'public')->get($file['path'])) {
                        $attachments[] = new \Laravel\Ai\Files\Base64Audio(base64_encode($content), $mime);
                        continue;
                    }
                }

                if (($file['type'] ?? null) === 'document') {
                    if (! empty($file['text_content'])) {
                        $documentContext[] = "Dokumen {$file['name']}:\n" . $file['text_content'];
                    } elseif (! empty($file['path'])) {
                        if (in_array(($file['disk'] ?? 'public'), ['public', 'local'])) {
                            $absolutePath = \Illuminate\Support\Facades\Storage::disk($file['disk'] ?? 'public')->path($file['path']);
                            $attachments[] = new \Laravel\Ai\Files\LocalDocument($absolutePath, $file['mime'] ?? 'application/pdf');
                        } else {
                            $attachments[] = new \Laravel\Ai\Files\StoredDocument($file['path'], $file['disk'] ?? 'public');
                        }
                    }
                }

                if (in_array(($file['type'] ?? null), ['video', 'file']) && ! empty($file['path'])) {
                    if ($content = \Illuminate\Support\Facades\Storage::disk($file['disk'] ?? 'public')->get($file['path'])) {
                         // Use Base64Document which Gemini handles as inline_data for small videos
                         $attachments[] = new \Laravel\Ai\Files\Base64Document(base64_encode($content), $file['mime'] ?? 'application/octet-stream');
                    }
                }
            }
        }

        $content = trim((string) ($msg->content ?? ''));

        if ($documentContext !== []) {
            $content .= "\n\nKonteks dokumen:\n" . implode("\n\n", $documentContext);
        }

        return new UserMessage(Str::of($content)->trim()->toString(), $attachments);
    }
}
