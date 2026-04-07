<?php

namespace AiWorkspace\Http\Controllers;

use AiWorkspace\Support\ChatPayloadTransformer;
use AiWorkspace\Support\WorkspaceModelResolver;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController
{
    public function __construct(
        private ChatPayloadTransformer $transformer,
        private WorkspaceModelResolver $models
    ) {
    }

    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $chatClass = $this->models->chatClass();

        $chats = $chatClass::query()
            ->where('user_id', $user->id)
            ->with('latestMessage')
            ->withCount('messages')
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn ($chat) => $this->transformer->transform($chat))
            ->values();

        $selectedChatId = (int) ($request->query('chat') ?: optional($chats->first())['id']);

        $selectedChat = $selectedChatId
            ? $chatClass::query()
                ->where('user_id', $user->id)
                ->with(['messages' => fn ($query) => $query->orderBy('created_at')])
                ->with('latestMessage')
                ->find($selectedChatId)
            : null;

        return view('ai-workspace::dashboard', [
            'chats' => $chats,
            'selectedChat' => $selectedChat,
            'selectedChatId' => $selectedChatId,
            'adminStatsUrl' => method_exists($user, 'canAccessAdmin') && $user->canAccessAdmin() ? route('admin.dashboard') : null,
        ]);
    }
}
