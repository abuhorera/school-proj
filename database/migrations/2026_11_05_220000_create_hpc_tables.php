<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hpc_frameworks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->unique();
            $table->string('stage'); // Foundational, Preparatory, Middle, Secondary
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_locked')->default(false);
            $table->json('levels_json')->nullable();
            $table->json('domains_json')->nullable();
            $table->json('parts_json')->nullable();
            $table->timestamps();
        });

        Schema::create('hpc_framework_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->constrained('hpc_frameworks')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('classes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['framework_id', 'school_class_id']);
        });

        Schema::create('hpc_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->nullable()->constrained('hpc_frameworks')->nullOnDelete();
            $table->foreignId('school_class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('title');
            $table->string('activity_type')->default('Learning Activity');
            $table->string('domain');
            $table->string('interaction_type')->default('Individual');
            $table->integer('duration_minutes')->default(45);
            $table->string('term')->default('Term 1');
            $table->text('prompt')->nullable();
            $table->json('pedagogies_json')->nullable();
            $table->date('conducted_date')->nullable();
            $table->timestamps();
        });

        Schema::create('hpc_activity_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('hpc_activities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->json('levels_json')->nullable();
            $table->text('observations')->nullable();
            $table->text('challenges')->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'student_id']);
        });

        Schema::create('hpc_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('academic_session_id')->nullable();
            $table->foreignId('framework_id')->nullable()->constrained('hpc_frameworks')->nullOnDelete();
            $table->string('stage')->default('Foundational');
            $table->string('term')->default('Full year');
            $table->string('status')->default('draft'); // draft, teacher_submitted, reviewed, published
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('return_reason')->nullable();
            $table->json('general_info_json')->nullable();
            $table->json('attendance_json')->nullable();
            $table->json('self_assessment_json')->nullable();
            $table->json('peer_assessment_json')->nullable();
            $table->json('parent_feedback_json')->nullable();
            $table->json('teacher_summary_json')->nullable();
            $table->json('domain_scores_json')->nullable();
            $table->timestamps();
        });

        Schema::create('hpc_card_settings', function (Blueprint $table) {
            $table->id();
            $table->string('emblem_path')->nullable();
            $table->string('edition_label')->nullable();
            $table->string('theme')->default('playful');
            $table->boolean('show_radar_chart')->default(true);
            $table->boolean('show_monthly_attendance')->default(true);
            $table->boolean('show_health_metrics')->default(true);
            $table->string('principal_name')->nullable()->default('Dr. Rajesh Sharma');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hpc_card_settings');
        Schema::dropIfExists('hpc_cards');
        Schema::dropIfExists('hpc_activity_marks');
        Schema::dropIfExists('hpc_activities');
        Schema::dropIfExists('hpc_framework_classes');
        Schema::dropIfExists('hpc_frameworks');
    }
};

