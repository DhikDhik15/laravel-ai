<?php

namespace App\Http\Controllers;

use AiWorkspace\Support\ChatPayloadTransformer;
use App\Models\Chat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private ChatPayloadTransformer $transformer)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $chats = Chat::query()
            ->where('user_id', $user->id)
            ->with('latestMessage')
            ->withCount('messages')
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (Chat $chat) => $this->transformer->transform($chat))
            ->values();

        $selectedChatId = (int) ($request->query('chat') ?: optional($chats->first())['id']);

        $selectedChat = $selectedChatId
            ? Chat::query()
                ->where('user_id', $user->id)
                ->with(['messages' => fn ($query) => $query->orderBy('created_at')])
                ->with('latestMessage')
                ->find($selectedChatId)
            : null;

        return view('dashboard', [
            'chats' => $chats,
            'selectedChat' => $selectedChat,
            'selectedChatId' => $selectedChatId,
            'adminStatsUrl' => $user->canAccessAdmin() ? route('admin.dashboard') : null,
        ]);
    }
}
