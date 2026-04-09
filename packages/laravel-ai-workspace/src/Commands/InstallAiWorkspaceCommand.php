<?php

namespace AiWorkspace\Commands;

use Illuminate\Console\Command;

class InstallAiWorkspaceCommand extends Command
{
    protected $signature = 'ai-workspace:install
        {--force : Overwrite any existing published files}
        {--without-docs : Skip publishing package docs}
        {--without-migrations : Skip publishing migrations}
        {--without-views : Skip publishing views}
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
