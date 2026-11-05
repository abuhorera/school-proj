<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Lead Stages Table
        if (!Schema::hasTable('lead_stages')) {
            Schema::create('lead_stages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->string('name');
                $table->string('color')->default('#6366f1');
                $table->integer('sort_order')->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Lead Sources Table
        if (!Schema::hasTable('lead_sources')) {
            Schema::create('lead_sources', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Leads Table
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->string('student_name');
                $table->string('parent_name')->nullable();
                $table->string('phone');
                $table->string('email')->nullable();
                $table->unsignedBigInteger('school_class_id')->nullable();
                $table->string('class_name')->nullable();
                $table->unsignedBigInteger('lead_source_id')->nullable();
                $table->string('source_name')->nullable();
                $table->unsignedBigInteger('lead_stage_id')->default(1);
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->date('follow_up_date')->nullable();
                $table->text('notes')->nullable();
                $table->string('status')->default('active'); // active, converted, lost
                $table->unsignedBigInteger('converted_student_id')->nullable();
                $table->timestamps();
            });
        }

        // 4. Lead Follow-ups Table
        if (!Schema::hasTable('lead_followups')) {
            Schema::create('lead_followups', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->date('follow_up_date');
                $table->string('type')->default('Phone Call'); // Phone Call, Campus Tour, Meeting, WhatsApp, Email
                $table->text('response_notes');
                $table->date('next_follow_up_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_followups');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('lead_sources');
        Schema::dropIfExists('lead_stages');
    }
};

