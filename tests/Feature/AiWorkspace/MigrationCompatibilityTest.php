<?php

namespace Tests\Feature\AiWorkspace;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationCompatibilityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropAllTables();
    }

    public function test_add_chat_metadata_migration_is_idempotent(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
        });

        $migration = require base_path('database/migrations/2026_04_07_000001_add_chat_metadata_columns.php');

        $migration->up();
        $migration->up();

        $this->assertTrue(Schema::hasColumn('chats', 'summary'));
        $this->assertTrue(Schema::hasColumn('chats', 'last_message_at'));
    }

    public function test_add_files_to_messages_migration_is_idempotent(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
        });

        $migration = require base_path('database/migrations/2026_04_02_062747_add_files_to_messages_table.php');

        $migration->up();
        $migration->up();

        $this->assertTrue(Schema::hasColumn('messages', 'files'));
    }

    public function test_package_create_table_migrations_skip_when_tables_already_exist(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
        });

        $chatsMigration = require base_path('packages/laravel-ai-workspace/database/migrations/2026_04_07_100000_create_ai_workspace_chats_table.php');
        $messagesMigration = require base_path('packages/laravel-ai-workspace/database/migrations/2026_04_07_100001_create_ai_workspace_messages_table.php');

        $chatsMigration->up();
        $messagesMigration->up();

        $this->assertTrue(Schema::hasTable('chats'));
        $this->assertTrue(Schema::hasTable('messages'));
    }
}

