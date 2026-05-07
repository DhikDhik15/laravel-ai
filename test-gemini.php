<?php

use App\Ai\Agents\SupportAgent;
use Illuminate\Contracts\Console\Kernel;
use Laravel\Ai\Streaming\Events\TextDelta;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

try {
    $agent = new SupportAgent;
    $response = $agent->stream('Hello');
    foreach ($response as $delta) {
        if ($delta instanceof TextDelta) {
            echo 'DELTA: '.$delta->delta."\n";
        }
    }
} catch (Throwable $e) {
    echo 'ERROR: '.get_class($e).': '.$e->getMessage()."\n";
    echo $e->getTraceAsString();
}
