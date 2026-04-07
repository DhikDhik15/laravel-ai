<?php

namespace AiWorkspace\Commands;

use Illuminate\Console\Command;

class InstallAiWorkspaceCommand extends Command
{
    protected $signature = 'ai-workspace:install';

    protected $description = 'Publish Laravel AI Workspace package resources into the host application';

    public function handle(): int
    {
        $this->callSilent('vendor:publish', [
            '--tag' => 'ai-workspace-config',
            '--force' => false,
        ]);

        $this->callSilent('vendor:publish', [
            '--tag' => 'ai-workspace-docs',
            '--force' => false,
        ]);

        $this->callSilent('vendor:publish', [
            '--tag' => 'ai-workspace-migrations',
            '--force' => false,
        ]);

        $this->callSilent('vendor:publish', [
            '--tag' => 'ai-workspace-views',
            '--force' => false,
        ]);

        $this->components->info('Laravel AI Workspace package foundation installed.');
        $this->newLine();
        $this->line('Next recommended steps:');
        $this->line(' - run php artisan migrate after publishing package migrations');
        $this->line(' - set ai-workspace.models.chat and ai-workspace.models.message');
        $this->line(' - set ai-workspace.ai_responder to your AI service implementation');
        $this->line(' - publish views if you want to customize the default dashboard UI');

        return self::SUCCESS;
    }
}
