<?php

use App\Models\Chat;
use App\Services\GeminiService;
use Illuminate\Contracts\Console\Kernel;
use Laravel\Ai\Streaming\Events\TextDelta;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$chat = Chat::first();
if (! $chat) {
    echo "No chat found.\n";
    exit;
}

$message = $chat->messages()->where('role', 'user')->latest()->first();
if (! $message) {
    echo "No user message found.\n";
    exit;
}

echo "Testing stream for chat {$chat->id} and message {$message->id}...\n";

$service = new GeminiService;
$stream = $service->stream($chat, $message->id);

foreach ($stream as $event) {
    if ($event instanceof TextDelta) {
        echo $event->delta;
    }
}
echo "\nDONE\n";
