<?php

namespace Tests\Feature\AiWorkspace;

use AiWorkspace\Commands\InstallAiWorkspaceCommand;
use Tests\TestCase;

class InstallCommandTest extends TestCase
{
    public function test_install_command_exposes_expected_options(): void
    {
        $command = $this->app->make(InstallAiWorkspaceCommand::class);
        $options = array_keys($command->getDefinition()->getOptions());

        $this->assertContains('force', $options);
        $this->assertContains('without-docs', $options);
        $this->assertContains('without-migrations', $options);
        $this->assertContains('without-views', $options);
        $this->assertContains('migrate', $options);
    }

    public function test_install_command_can_run_without_optional_publish_targets(): void
    {
        $this->artisan('ai-workspace:install', [
            '--without-docs' => true,
            '--without-migrations' => true,
            '--without-views' => true,
        ])->assertSuccessful();
    }
}

