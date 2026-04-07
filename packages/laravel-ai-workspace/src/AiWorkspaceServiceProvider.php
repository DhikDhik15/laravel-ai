<?php

namespace AiWorkspace;

use AiWorkspace\Commands\InstallAiWorkspaceCommand;
use AiWorkspace\Contracts\StreamsChatResponses;
use AiWorkspace\Support\AttachmentPreparer;
use AiWorkspace\Support\ChatLifecycleManager;
use AiWorkspace\Support\ChatPayloadTransformer;
use AiWorkspace\Support\ChatSummaryResolver;
use AiWorkspace\Support\WorkspaceModelResolver;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AiWorkspaceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ai-workspace.php', 'ai-workspace');

        $this->app->singleton(ChatSummaryResolver::class);
        $this->app->singleton(AttachmentPreparer::class);
        $this->app->singleton(WorkspaceModelResolver::class);
        $this->app->singleton(ChatPayloadTransformer::class, function ($app) {
            return new ChatPayloadTransformer($app->make(ChatSummaryResolver::class));
        });
        $this->app->singleton(ChatLifecycleManager::class);

        $this->app->bind(StreamsChatResponses::class, function ($app) {
            $responder = config('ai-workspace.ai_responder');

            if (! is_string($responder) || ! class_exists($responder)) {
                throw new InvalidArgumentException('Configured AI responder class is invalid.');
            }

            $instance = $app->make($responder);

            if (! $instance instanceof StreamsChatResponses) {
                throw new InvalidArgumentException('Configured AI responder must implement AiWorkspace\\Contracts\\StreamsChatResponses.');
            }

            return $instance;
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ai-workspace');

        if (config('ai-workspace.route_enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        $this->publishes([
            __DIR__ . '/../config/ai-workspace.php' => config_path('ai-workspace.php'),
        ], 'ai-workspace-config');

        $this->publishes([
            __DIR__ . '/../README.md' => base_path('docs/laravel-ai-workspace.md'),
        ], 'ai-workspace-docs');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'ai-workspace-migrations');

        $this->publishes([
            __DIR__ . '/../resources/views/' => resource_path('views/vendor/ai-workspace'),
        ], 'ai-workspace-views');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallAiWorkspaceCommand::class,
            ]);
        }
    }
}
