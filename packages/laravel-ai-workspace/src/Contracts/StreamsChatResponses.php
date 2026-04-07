<?php

namespace AiWorkspace\Contracts;

use Illuminate\Database\Eloquent\Model;
use Laravel\Ai\Responses\StreamableAgentResponse;

interface StreamsChatResponses
{
    public function generate(Model $chat): string;

    public function stream(Model $chat, ?int $messageId = null): StreamableAgentResponse;
}
