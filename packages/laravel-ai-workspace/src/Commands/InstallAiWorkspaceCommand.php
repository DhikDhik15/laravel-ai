<?php

namespace AiWorkspace\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallAiWorkspaceCommand extends Command
{
    protected $signature = 'ai-workspace:install
        {--force : Overwrite any existing published files}
        {--without-docs : Skip publishing package docs}
        {--without-migrations : Skip publishing migrations}
        {--without-views : Skip publishing views}
        {--with-stubs : Generate default Chat, Message, and responder classes when missing}
        {--migrate : Run database migrations after publishing resources}';

    protected $description = 'Publish Laravel AI Workspace package resources into the host application';

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        $this->publishTag('ai-workspace-config', $force);

        if (! $this->option('without-docs')) {
            $this->publishTag('ai-workspace-docs', $force);
        }

        if (! $this->option('without-migrations')) {
            $this->publishTag('ai-workspace-migrations', $force);
        }

        if (! $this->option('without-views')) {
            $this->publishTag('ai-workspace-views', $force);
        }

        if ($this->option('migrate')) {
            $this->components->task('Running migrations', function () {
                return $this->call('migrate', ['--force' => true]) === self::SUCCESS;
            });
        }

        if ($this->option('with-stubs')) {
            $this->publishDefaultStubs($force);
        }

        $this->validateConfiguration();

        $this->components->info('Laravel AI Workspace package foundation installed.');
        $this->newLine();
        $this->line('Next recommended steps:');
        if (! $this->option('migrate') && ! $this->option('without-migrations')) {
            $this->line(' - run php artisan migrate after publishing package migrations');
        }
        $this->line(' - set ai-workspace.models.chat and ai-workspace.models.message');
        $this->line(' - set ai-workspace.ai_responder to your AI service implementation');
        $this->line(' - publish views if you want to customize the default dashboard UI');

        return self::SUCCESS;
    }

    private function validateConfiguration(): void
    {
        $chatModel = config('ai-workspace.models.chat');
        $messageModel = config('ai-workspace.models.message');
        $responder = config('ai-workspace.ai_responder');

        $this->warnMissingClass('ai-workspace.models.chat', $chatModel);
        $this->warnMissingClass('ai-workspace.models.message', $messageModel);
        $this->warnMissingClass('ai-workspace.ai_responder', $responder);
    }

    private function warnMissingClass(string $key, mixed $value): void
    {
        if (! is_string($value) || $value === '' || ! class_exists($value)) {
            $this->components->warn("Set {$key} to a valid class in config/ai-workspace.php.");
        }
    }

    private function publishDefaultStubs(bool $force = false): void
    {
        $this->writeStub(
            app_path('Models/Chat.php'),
            $this->chatModelStub(),
            $force
        );
        $this->writeStub(
            app_path('Models/Message.php'),
            $this->messageModelStub(),
            $force
        );
        $this->writeStub(
            app_path('Services/AiWorkspaceResponder.php'),
            $this->responderStub(),
            $force
        );
    }

    private function writeStub(string $path, string $contents, bool $force = false): void
    {
        $fs = new Filesystem;
        $label = str_replace(base_path().'/', '', str_replace('\\', '/', $path));

        if (! $force && $fs->exists($path)) {
            $this->components->twoColumnDetail("Skipping {$label}", 'already exists');

            return;
        }

        $this->components->task("Generating {$label}", function () use ($fs, $path, $contents) {
            $fs->ensureDirectoryExists(dirname($path));
            $fs->put($path, $contents);

            return true;
        });
    }

    private function chatModelStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'title',
        'summary',
        'status',
        'model',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
PHP;
    }

    private function messageModelStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'role',
        'content',
        'status',
        'meta',
        'files',
    ];

    protected $casts = [
        'meta' => 'array',
        'files' => 'array',
    ];
}
PHP;
    }

    private function responderStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Services;

use AiWorkspace\Contracts\StreamsChatResponses;
use Illuminate\Database\Eloquent\Model;
use Laravel\Ai\Responses\StreamableAgentResponse;
use LogicException;

class AiWorkspaceResponder implements StreamsChatResponses
{
    public function generate(Model $chat): string
    {
        return 'Implement generate() in '.self::class;
    }

    public function stream(Model $chat, ?int $messageId = null): StreamableAgentResponse
    {
        throw new LogicException('Implement stream() in '.self::class);
    }
}
PHP;
    }

    private function publishTag(string $tag, bool $force = false): void
    {
        $this->components->task("Publishing {$tag}", function () use ($tag, $force) {
            $params = [
                '--tag' => $tag,
            ];

            if ($force) {
                $params['--force'] = true;
            }

            return $this->callSilent('vendor:publish', $params) === self::SUCCESS;
        });
    }
}
