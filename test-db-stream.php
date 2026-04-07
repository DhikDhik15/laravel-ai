<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$chat = App\Models\Chat::first();
if (!$chat) {
    echo "No chat found.\n";
    exit;
}

$message = $chat->messages()->where('role', 'user')->latest()->first();
if (!$message) {
    echo "No user message found.\n";
    exit;
}

echo "Testing stream for chat {$chat->id} and message {$message->id}...\n";

$service = new App\Services\GeminiService();
$stream = $service->stream($chat, $message->id);

foreach ($stream as $event) {
    if ($event instanceof \Laravel\Ai\Streaming\Events\TextDelta) {
        echo $event->delta;
    }
}
echo "\nDONE\n";
