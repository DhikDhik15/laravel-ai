<?php

use AiWorkspace\Http\Controllers\ChatController;
use AiWorkspace\Http\Controllers\DashboardController;
use AiWorkspace\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('ai-workspace.route_middleware', ['auth', 'verified']))
    ->group(function () {
        Route::get(config('ai-workspace.route_path', '/dashboard'), DashboardController::class)->name('dashboard');
        Route::post('/chat/send', [MessageController::class, 'send'])->name('messages.send');
        Route::get('/chat/stream/{chat}/{message}', [MessageController::class, 'stream'])->name('messages.stream');
        Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
        Route::patch('/chats/{chat}', [ChatController::class, 'update'])->name('chats.update');
        Route::delete('/chats/{chat}', [ChatController::class, 'destroy'])->name('chats.destroy');
        Route::get('/chat/send', fn () => redirect()->route('dashboard'));
    });
