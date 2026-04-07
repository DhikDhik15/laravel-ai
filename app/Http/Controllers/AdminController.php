<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->canAccessAdmin(), 403);

        $totalUsers = User::count();
        $totalChats = Chat::count();
        $totalMessages = Message::count();
        $assistantMessages = Message::where('role', 'assistant')->count();
        $userMessages = Message::where('role', 'user')->count();

        $topUsers = User::query()
            ->withCount('chats')
            ->orderByDesc('chats_count')
            ->limit(5)
            ->get();

        $recentChats = Chat::query()
            ->with('user')
            ->withCount('messages')
            ->latest('last_message_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalChats',
            'totalMessages',
            'assistantMessages',
            'userMessages',
            'topUsers',
            'recentChats'
        ));
    }
}
