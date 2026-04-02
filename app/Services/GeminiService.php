<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use App\Ai\Agents\SupportAgent;

class GeminiService
{
    /**
     * Generate response from Gemini using SupportAgent.
     *
     * @param  \App\Models\Chat  $chat
     * @return string
     * @throws \Throwable
     */
    /**
     * Generate response from Gemini using SupportAgent.
     *
     * @param  \App\Models\Chat  $chat
     * @return string
     * @throws \Throwable
     */
    public function generate($chat): string
    {
        try {
            // Get message history in order
            $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

            if ($messages->isEmpty()) {
                throw new \InvalidArgumentException('No messages available in this chat.');
            }

            // Extract the latest user message
            $lastMessage = $messages->pop();
            
            // Map previous messages to history using specific AI Message classes
            $history = $messages->map(function($msg) {
                if ($msg->role === 'assistant') {
                    return new \Laravel\Ai\Messages\AssistantMessage($msg->content);
                }
                
                return $this->createUserMessage($msg);
            })->toArray();

            // Create the prompt message (latest message)
            $promptMessage = $this->createUserMessage($lastMessage);

            // Use SupportAgent with history
            $agent = new SupportAgent($history);
            
            // prompt() with text and detected attachments
            $response = $agent->prompt($promptMessage->content, $promptMessage->attachments->all());

            return (string) $response;

        } catch (\Throwable $e) {
            Log::error('Gemini API Error', [
                'chat_id' => $chat->id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Create a UserMessage, using the 'files' column for attachments.
     */
    protected function createUserMessage($msg): \Laravel\Ai\Messages\UserMessage
    {
        $attachments = [];

        if (!empty($msg->files) && is_array($msg->files)) {
            foreach ($msg->files as $base64) {
                $attachments[] = new \Laravel\Ai\Files\Base64Image($base64, 'image/jpeg');
            }
        }

        return new \Laravel\Ai\Messages\UserMessage($msg->content ?? '', $attachments);
    }
}
