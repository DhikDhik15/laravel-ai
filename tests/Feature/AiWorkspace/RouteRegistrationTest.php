<?php

namespace Tests\Feature\AiWorkspace;

use Illuminate\Routing\RouteCollection;
use Tests\TestCase;

class RouteRegistrationTest extends TestCase
{
    public function test_package_routes_support_prefix_and_name_prefix(): void
    {
        config([
            'ai-workspace.route_middleware' => [],
            'ai-workspace.route_path' => '/assistant',
            'ai-workspace.route_prefix' => 'workspace-ai',
            'ai-workspace.route_name_prefix' => 'aiw.',
        ]);

        app('router')->setRoutes(new RouteCollection());

        require base_path('packages/laravel-ai-workspace/routes/web.php');

        $this->app['url']->setRoutes(app('router')->getRoutes());
        app('router')->getRoutes()->refreshNameLookups();
        app('router')->getRoutes()->refreshActionLookups();

        $routeNames = collect(app('router')->getRoutes()->getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter()
            ->values()
            ->all();

        $this->assertContains('aiw.dashboard', $routeNames, 'Registered route names: ' . implode(', ', $routeNames));
        $this->assertContains('aiw.messages.send', $routeNames);
        $this->assertContains('aiw.messages.stream', $routeNames);
        $this->assertContains('aiw.chats.show', $routeNames);

        $this->assertSame('/workspace-ai/assistant', route('aiw.dashboard', [], false));
        $this->assertSame('/workspace-ai/chat/send', route('aiw.messages.send', [], false));
        $this->assertSame('/workspace-ai/chats/123', route('aiw.chats.show', ['chat' => 123], false));

        $this->get('/workspace-ai/chat/send')
            ->assertRedirect('/workspace-ai/assistant');
    }
}
