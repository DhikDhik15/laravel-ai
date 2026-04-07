<?php

namespace AiWorkspace\Http\Controllers;

use AiWorkspace\Support\ChatPayloadTransformer;
use AiWorkspace\Support\WorkspaceModelResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController
{
    public function __construct(
        private ChatPayloadTransformer $transformer,
        private WorkspaceModelResolver $models
    ) {
    }

    public function show(Request $request, int $chat): JsonResponse
    {
        $chatModel = $this->models->newChatQuery()
            ->where('user_id', $request->user()->id)
            ->with(['messages' => fn ($query) => $query->orderBy('created_at'), 'latestMessage'])
            ->findOrFail($chat);

        return response()->json([
            'chat' => $this->transformer->transform($chatModel, true),
        ]);
    }

    public function update(Request $request, int $chat): JsonResponse
    {
        $chatModel = $this->models->newChatQuery()
            ->where('user_id', $request->user()->id)
            ->findOrFail($chat);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $chatModel->update([
            'title' => $validated['title'],
        ]);

        return response()->json([
            'chat' => $this->transformer->transform($chatModel->fresh()->loadCount('messages')->load('latestMessage')),
        ]);
    }

    public function destroy(Request $request, int $chat): JsonResponse
    {
        $chatModel = $this->models->newChatQuery()
            ->where('user_id', $request->user()->id)
            ->findOrFail($chat);

        $chatId = $chatModel->getKey();
        $chatModel->delete();

        return response()->json([
            'deleted' => true,
            'chat_id' => $chatId,
        ]);
    }
}
