<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/chats/{chat}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chats.show');
    Route::patch('/chats/{chat}', [\App\Http\Controllers\ChatController::class, 'update'])->name('chats.update');
    Route::delete('/chats/{chat}', [\App\Http\Controllers\ChatController::class, 'destroy'])->name('chats.destroy');

    Route::post('/chat/send', [\App\Http\Controllers\MessageController::class, 'send'])->name('messages.send');
    Route::get('/chat/stream/{chat}/{message}', [\App\Http\Controllers\MessageController::class, 'stream'])->name('messages.stream');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';
