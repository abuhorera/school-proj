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
        // 1. Upgrade ptm_meetings with additional columns
        Schema::table('ptm_meetings', function (Blueprint $table) {
            if (!Schema::hasColumn('ptm_meetings', 'scope')) {
                $table->string('scope')->default('whole_school')->after('mode');
            }
            if (!Schema::hasColumn('ptm_meetings', 'school_class_id')) {
                $table->unsignedBigInteger('school_class_id')->nullable()->after('scope');
            }
            if (!Schema::hasColumn('ptm_meetings', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('school_class_id');
            }
            if (!Schema::hasColumn('ptm_meetings', 'venue')) {
                $table->string('venue')->nullable()->after('mode');
            }
            if (!Schema::hasColumn('ptm_meetings', 'online_link')) {
                $table->string('online_link')->nullable()->after('venue');
            }
            if (!Schema::hasColumn('ptm_meetings', 'description')) {
                $table->text('description')->nullable()->after('agenda');
            }
        });

        // 2. PTM Attendances & Student Remarks
        if (!Schema::hasTable('ptm_attendances')) {
            Schema::create('ptm_attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->unsignedBigInteger('ptm_meeting_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('guardian_id')->nullable();
                $table->string('slot_time')->nullable();
                $table->string('attendance_status')->default('Pending'); // Pending, Present, Absent, Rescheduled
                $table->boolean('is_notified')->default(false);
                $table->text('performance_remark')->nullable();
                $table->text('attendance_remark')->nullable();
                $table->text('behaviour_remark')->nullable();
                $table->integer('overall_rating')->nullable(); // 1 to 5
                $table->boolean('visible_to_parent')->default(true);
                $table->timestamps();

                $table->foreign('ptm_meeting_id')->references('id')->on('ptm_meetings')->onDelete('cascade');
            });
        }

        // 3. PTM Follow-ups (Action Plans)
        if (!Schema::hasTable('ptm_followups')) {
            Schema::create('ptm_followups', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->unsignedBigInteger('ptm_meeting_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('staff_id')->nullable();
                $table->text('action_plan');
                $table->string('assigned_to')->nullable();
                $table->date('due_date')->nullable();
                $table->string('status')->default('Open'); // Open, In progress, Completed
                $table->text('admin_notes')->nullable();
                $table->timestamps();

                $table->foreign('ptm_meeting_id')->references('id')->on('ptm_meetings')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptm_followups');
        Schema::dropIfExists('ptm_attendances');
    }
};
