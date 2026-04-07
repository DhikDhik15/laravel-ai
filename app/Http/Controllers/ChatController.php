<?php

namespace App\Http\Controllers;

use AiWorkspace\Support\ChatPayloadTransformer;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(private ChatPayloadTransformer $transformer)
    {
    }

    public function show(Request $request, Chat $chat): JsonResponse
    {
        abort_unless($chat->user_id === $request->user()->id, 404);

        $chat->load(['messages' => fn ($query) => $query->orderBy('created_at'), 'latestMessage']);

        return response()->json([
            'chat' => $this->transformer->transform($chat, true),
        ]);
    }

    public function update(Request $request, Chat $chat): JsonResponse
    {
        abort_unless($chat->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $chat->update([
            'title' => $validated['title'],
        ]);

        return response()->json([
            'chat' => $this->transformer->transform($chat->fresh()->loadCount('messages')->load('latestMessage')),
        ]);
    }

    public function destroy(Request $request, Chat $chat): JsonResponse
    {
        abort_unless($chat->user_id === $request->user()->id, 404);

        $chat->delete();

        return response()->json([
            'deleted' => true,
            'chat_id' => $chat->id,
        ]);
    }
}
