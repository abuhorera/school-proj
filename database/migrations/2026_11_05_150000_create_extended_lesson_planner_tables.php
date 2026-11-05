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
        // 1. Upgrade lesson_plans table
        Schema::table('lesson_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('lesson_plans', 'staff_id')) {
                $table->unsignedBigInteger('staff_id')->nullable()->after('subject_id');
            }
            if (!Schema::hasColumn('lesson_plans', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            }
            if (!Schema::hasColumn('lesson_plans', 'title')) {
                $table->string('title')->nullable()->after('school_id');
            }
            if (!Schema::hasColumn('lesson_plans', 'week_start_date')) {
                $table->date('week_start_date')->nullable()->after('week_number');
            }
            if (!Schema::hasColumn('lesson_plans', 'week_end_date')) {
                $table->date('week_end_date')->nullable()->after('week_start_date');
            }
            if (!Schema::hasColumn('lesson_plans', 'planned_periods')) {
                $table->integer('planned_periods')->default(1)->after('week_end_date');
            }
            if (!Schema::hasColumn('lesson_plans', 'chapter_names')) {
                $table->text('chapter_names')->nullable()->after('title');
            }
            if (!Schema::hasColumn('lesson_plans', 'learning_objectives')) {
                $table->text('learning_objectives')->nullable()->after('chapter_names');
            }
            if (!Schema::hasColumn('lesson_plans', 'teaching_methods')) {
                $table->text('teaching_methods')->nullable()->after('teaching_method');
            }
            if (!Schema::hasColumn('lesson_plans', 'teaching_aids')) {
                $table->text('teaching_aids')->nullable()->after('teaching_methods');
            }
            if (!Schema::hasColumn('lesson_plans', 'resources_required')) {
                $table->text('resources_required')->nullable()->after('teaching_aids');
            }
            if (!Schema::hasColumn('lesson_plans', 'adhoc_topics')) {
                $table->text('adhoc_topics')->nullable()->after('subtopics');
            }
            if (!Schema::hasColumn('lesson_plans', 'homework_planned')) {
                $table->text('homework_planned')->nullable()->after('resources_required');
            }
            if (!Schema::hasColumn('lesson_plans', 'review_feedback')) {
                $table->text('review_feedback')->nullable()->after('status');
            }
            if (!Schema::hasColumn('lesson_plans', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('review_feedback');
            }
            if (!Schema::hasColumn('lesson_plans', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('lesson_plans', 'submitted_at')) {
                $table->dateTime('submitted_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('lesson_plans', 'approved_at')) {
                $table->dateTime('approved_at')->nullable()->after('submitted_at');
            }
            if (!Schema::hasColumn('lesson_plans', 'coverage_percentage')) {
                $table->decimal('coverage_percentage', 5, 2)->default(0.00)->after('status');
            }
        });

        // 2. Lesson Plan Topics & Coverage Table
        if (!Schema::hasTable('lesson_plan_topics')) {
            Schema::create('lesson_plan_topics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->unsignedBigInteger('lesson_plan_id');
                $table->string('topic_name');
                $table->string('subtopic_name')->nullable();
                $table->integer('periods_allocated')->default(1);
                $table->string('coverage_status')->default('Pending'); // Pending, Covered, Partial, Not Covered, Deferred
                $table->string('coverage_reason')->nullable();
                $table->date('covered_on')->nullable();
                $table->timestamps();

                $table->foreign('lesson_plan_id')->references('id')->on('lesson_plans')->onDelete('cascade');
            });
        }

        // 3. Lesson Planner Settings
        if (!Schema::hasTable('lesson_planner_settings')) {
            Schema::create('lesson_planner_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->boolean('hod_step_enabled')->default(true);
                $table->boolean('allow_edit_after_submit')->default(false);
                $table->boolean('reminder_enabled')->default(true);
                $table->string('reminder_day')->default('Friday');
                $table->string('reminder_time')->default('15:00');
                $table->string('submission_deadline_day')->default('Monday');
                $table->string('submission_deadline_time')->default('09:00');
                $table->timestamps();
            });
        }

        // 4. Department Heads Routing Table
        if (!Schema::hasTable('department_heads')) {
            Schema::create('department_heads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->string('department');
                $table->unsignedBigInteger('head_staff_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_heads');
        Schema::dropIfExists('lesson_planner_settings');
        Schema::dropIfExists('lesson_plan_topics');
    }
};

