<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Extend kb_categories
        if (Schema::hasTable('kb_categories')) {
            Schema::table('kb_categories', function (Blueprint $table) {
                if (!Schema::hasColumn('kb_categories', 'parent_id')) {
                    $table->unsignedBigInteger('parent_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('kb_categories', 'description')) {
                    $table->text('description')->nullable()->after('icon');
                }
                if (!Schema::hasColumn('kb_categories', 'visibility')) {
                    $table->string('visibility')->default('Public')->after('description');
                }
                if (!Schema::hasColumn('kb_categories', 'sort_order')) {
                    $table->integer('sort_order')->default(1)->after('visibility');
                }
                if (!Schema::hasColumn('kb_categories', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('sort_order');
                }
            });
        }

        // 2. Extend kb_articles
        if (Schema::hasTable('kb_articles')) {
            Schema::table('kb_articles', function (Blueprint $table) {
                if (!Schema::hasColumn('kb_articles', 'excerpt')) {
                    $table->text('excerpt')->nullable()->after('slug');
                }
                if (!Schema::hasColumn('kb_articles', 'attachments')) {
                    $table->text('attachments')->nullable()->after('content');
                }
                if (!Schema::hasColumn('kb_articles', 'status')) {
                    $table->string('status')->default('Published')->after('attachments');
                }
                if (!Schema::hasColumn('kb_articles', 'visibility')) {
                    $table->string('visibility')->default('Public')->after('status');
                }
                if (!Schema::hasColumn('kb_articles', 'tags')) {
                    $table->string('tags')->nullable()->after('visibility');
                }
                if (!Schema::hasColumn('kb_articles', 'is_pinned')) {
                    $table->boolean('is_pinned')->default(false)->after('tags');
                }
                if (!Schema::hasColumn('kb_articles', 'helpful_count')) {
                    $table->integer('helpful_count')->default(0)->after('views_count');
                }
            });
        }

        // 3. Create ai_chat_conversations
        if (!Schema::hasTable('ai_chat_conversations')) {
            Schema::create('ai_chat_conversations', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('last_message')->nullable();
                $table->text('messages_json')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_conversations');
    }
};

