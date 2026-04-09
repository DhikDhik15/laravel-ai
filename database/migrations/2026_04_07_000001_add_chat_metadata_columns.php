<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chats')) {
            return;
        }

        Schema::table('chats', function (Blueprint $table) {
            if (! Schema::hasColumn('chats', 'summary')) {
                $table->text('summary')->nullable()->after('title');
            }

            if (! Schema::hasColumn('chats', 'last_message_at')) {
                $table->timestamp('last_message_at')->nullable()->after('summary')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn(['summary', 'last_message_at']);
        });
    }
};
