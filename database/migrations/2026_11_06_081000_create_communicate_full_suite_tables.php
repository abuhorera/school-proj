<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Extend Notices Table with Live Demo Columns
        Schema::table('notices', function (Blueprint $table) {
            if (!Schema::hasColumn('notices', 'recipient_type')) {
                $table->string('recipient_type')->default('all')->nullable(); // all, staff, parents, class
            }
            if (!Schema::hasColumn('notices', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            }
            if (!Schema::hasColumn('notices', 'priority')) {
                $table->string('priority')->default('normal')->nullable(); // normal, high, urgent
            }
            if (!Schema::hasColumn('notices', 'is_popup')) {
                $table->boolean('is_popup')->default(false);
            }
            if (!Schema::hasColumn('notices', 'popup_expires_at')) {
                $table->date('popup_expires_at')->nullable();
            }
            if (!Schema::hasColumn('notices', 'attachment')) {
                $table->string('attachment')->nullable();
            }
        });

        // 2. Extend Events Table with Live Demo Columns
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'type')) {
                $table->string('type')->default('Event')->nullable(); // Event, Holiday, Exam, Meeting, Celebration, Sports
            }
            if (!Schema::hasColumn('events', 'applies_to')) {
                $table->string('applies_to')->default('All Staff & Students')->nullable();
            }
            if (!Schema::hasColumn('events', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('events', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('events', 'audience_type')) {
                $table->string('audience_type')->default('All')->nullable();
            }
            if (!Schema::hasColumn('events', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            }
        });

        // 3. Broadcast Messages
        if (!Schema::hasTable('broadcast_messages')) {
            Schema::create('broadcast_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
                $table->string('subject');
                $table->text('body')->nullable();
                $table->text('channels')->nullable(); // JSON: ['sms', 'whatsapp', 'email', 'push']
                $table->string('recipient_group')->default('All Parents');
                $table->text('class_ids')->nullable(); // JSON array
                $table->text('student_ids')->nullable(); // JSON array
                $table->integer('recipients_count')->default(0);
                $table->integer('sent_count')->default(0);
                $table->integer('delivered_count')->default(0);
                $table->integer('opened_count')->default(0);
                $table->integer('failed_count')->default(0);
                $table->decimal('estimated_cost', 10, 2)->default(0.00);
                $table->string('status')->default('Completed'); // Completed, Processing, Failed
                $table->timestamps();
            });
        }

        // 4. Broadcast SMS/WhatsApp Templates
        if (!Schema::hasTable('broadcast_templates')) {
            Schema::create('broadcast_templates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
                $table->string('channel')->default('SMS'); // SMS, WhatsApp
                $table->string('name');
                $table->string('category')->default('General Notice');
                $table->string('dlt_template_id')->nullable();
                $table->text('body');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Image Gallery Albums
        if (!Schema::hasTable('gallery_albums')) {
            Schema::create('gallery_albums', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('cover_image')->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        // 6. Gallery Photos
        if (!Schema::hasTable('gallery_photos')) {
            Schema::create('gallery_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('album_id')->constrained('gallery_albums')->cascadeOnDelete();
                $table->string('image_path');
                $table->string('caption')->nullable();
                $table->string('file_size')->nullable();
                $table->timestamps();
            });
        }

        // 7. Communication Wallet
        if (!Schema::hasTable('comms_wallets')) {
            Schema::create('comms_wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
                $table->decimal('balance', 10, 2)->default(1497.45);
                $table->string('currency')->default('INR');
                $table->boolean('use_platform_whatsapp')->default(true);
                $table->boolean('use_platform_sms')->default(true);
                $table->decimal('low_balance_threshold', 10, 2)->default(200.00);
                $table->boolean('auto_block')->default(false);
                $table->timestamps();
            });
        }

        // 8. Communication Wallet Transactions
        if (!Schema::hasTable('comms_transactions')) {
            Schema::create('comms_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->nullable()->constrained('comms_wallets')->cascadeOnDelete();
                $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
                $table->string('transaction_title');
                $table->string('transaction_type')->default('Top-up'); // Top-up, Broadcast SMS, Broadcast WhatsApp, Broadcast Email
                $table->decimal('amount', 10, 2)->default(0.00);
                $table->decimal('balance_after', 10, 2)->default(0.00);
                $table->string('payment_gateway')->nullable();
                $table->string('reference_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('comms_transactions');
        Schema::dropIfExists('comms_wallets');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('gallery_albums');
        Schema::dropIfExists('broadcast_templates');
        Schema::dropIfExists('broadcast_messages');
    }
};

