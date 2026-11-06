<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Engagement Designs / Creatives
        if (!Schema::hasTable('engagement_designs')) {
            Schema::create('engagement_designs', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category')->default('birthday_card'); // birthday_card, festival_banner, popup_card, story_card
                $table->string('editor_type')->default('canvas');
                $table->integer('width')->default(1080);
                $table->integer('height')->default(1080);
                $table->string('background_color')->default('#ffffff');
                $table->string('background_gradient')->nullable();
                $table->string('background_image')->nullable();
                $table->text('thumbnail_url')->nullable();
                $table->string('greeting_title')->nullable();
                $table->text('greeting_message')->nullable();
                $table->string('footer_text')->nullable();
                $table->longText('content_json')->nullable();
                $table->boolean('is_template')->default(false);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // 2. Engagement Festivals & Special Days
        if (!Schema::hasTable('engagement_festivals')) {
            Schema::create('engagement_festivals', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->date('festival_date');
                $table->string('region')->default('ALL');
                $table->unsignedBigInteger('design_id')->nullable();
                $table->text('greeting_message')->nullable();
                $table->boolean('auto_send')->default(true);
                $table->string('status')->default('Upcoming');
                $table->boolean('is_custom')->default(false);
                $table->timestamps();

                $table->foreign('design_id')->references('id')->on('engagement_designs')->onDelete('set null');
            });
        }

        // 3. Engagement Settings
        if (!Schema::hasTable('engagement_settings')) {
            Schema::create('engagement_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('birthday_students_enabled')->default(true);
                $table->unsignedBigInteger('birthday_student_design_id')->nullable();
                $table->boolean('birthday_staff_enabled')->default(true);
                $table->unsignedBigInteger('birthday_staff_design_id')->nullable();
                $table->boolean('festivals_enabled')->default(true);
                $table->unsignedBigInteger('festival_design_id')->nullable();
                $table->string('channels')->default('push,whatsapp,email,sms');
                $table->string('send_time')->default('09:00');
                $table->timestamps();

                $table->foreign('birthday_student_design_id')->references('id')->on('engagement_designs')->onDelete('set null');
                $table->foreign('birthday_staff_design_id')->references('id')->on('engagement_designs')->onDelete('set null');
                $table->foreign('festival_design_id')->references('id')->on('engagement_designs')->onDelete('set null');
            });
        }

        // 4. Engagement Dispatches History / Log
        if (!Schema::hasTable('engagement_dispatches')) {
            Schema::create('engagement_dispatches', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('Birthday'); // Birthday, Festival, Custom
                $table->string('recipient_type')->default('Student'); // Student, Staff, All Parents
                $table->unsignedBigInteger('recipient_id')->nullable();
                $table->string('recipient_name');
                $table->string('subject');
                $table->unsignedBigInteger('design_id')->nullable();
                $table->string('channels')->default('push');
                $table->dateTime('dispatched_at');
                $table->string('status')->default('Sent');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('design_id')->references('id')->on('engagement_designs')->onDelete('set null');
            });
        }

        // Add dob to staff table if missing
        if (Schema::hasTable('staff') && !Schema::hasColumn('staff', 'dob')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->date('dob')->nullable()->after('phone');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('engagement_dispatches');
        Schema::dropIfExists('engagement_settings');
        Schema::dropIfExists('engagement_festivals');
        Schema::dropIfExists('engagement_designs');
    }
};

