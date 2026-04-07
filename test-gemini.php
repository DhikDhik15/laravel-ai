<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $agent = new App\Ai\Agents\SupportAgent();
    $response = $agent->stream('Hello');
    foreach ($response as $delta) {
        if ($delta instanceof \Laravel\Ai\Streaming\Events\TextDelta) {
            echo "DELTA: " . $delta->delta . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
