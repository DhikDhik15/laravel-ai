<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(private GeminiService $gemini) {
        $this->gemini = $gemini;
    }

    public function send(Request $request)
    {
        $text = $request->message;
        $filePaths = [];
        $base64Images = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads/chats', 'public');
                $filePaths[] = $path;
                
                // For AI context, we use base64
                $imgData = file_get_contents($file);
                $base64Images[] = base64_encode($imgData);
            }
        }

        if (!$text && empty($base64Images)) {
            return response()->json([
                'error' => 'Message atau file tidak boleh kosong'
            ], 400);
        }

        $chat = Chat::find($request->chat_id);

        if (!$chat) {
            $chat = Chat::create([
                'user_id' => auth()->id(),
                'title' => $text ? substr($text, 0, 50) : 'Pesan Gambar (' . count($base64Images) . ')',
            ]);
        }

        // simpan user message
        Message::create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => $text ?? '',
            'files' => $base64Images,
        ]);

        try {
            // 🔥 kirim seluruh chat (context-aware)
            $reply = $this->gemini->generate($chat);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

        // simpan jawaban AI
        Message::create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => $reply,
            'files' => [],
        ]);

        return response()->json([
            'chat_id' => $chat->id,
            'reply' => $reply,
        ]);
    }
}
