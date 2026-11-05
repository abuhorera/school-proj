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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('academic_session_id')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            $table->string('access_token')->unique();
            $table->string('kind')->default('survey'); // survey, poll, feedback_form
            $table->string('status')->default('draft'); // draft, scheduled, published, closed, archived
            $table->dateTime('closes_at')->nullable();
            $table->string('recurrence_freq')->nullable(); // One-off, Weekly, Monthly, Termly
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('allow_resubmit')->default(false);
            $table->boolean('force_popup')->default(false);
            $table->boolean('one_per_device')->default(false);
            $table->string('audience_type')->default('all'); // all, roles, classes, public
            $table->json('target_role_ids')->nullable();
            $table->json('target_class_ids')->nullable();
            $table->integer('invited_count')->default(0);
            $table->integer('responses_count')->default(0);
            $table->boolean('is_report_published')->default(false);
            $table->dateTime('report_published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->integer('order_num')->default(1);
            $table->text('question_text');
            $table->string('question_type')->default('rating_scale'); // rating_scale, nps_score, yes_no, single_choice, multiple_choice, paragraph, short_text
            $table->integer('scale_min')->default(1);
            $table->integer('scale_max')->default(5);
            $table->boolean('is_required')->default(true);
            $table->json('options')->nullable(); // For choices
            $table->timestamps();
        });

        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('respondent_name')->nullable();
            $table->string('respondent_role')->nullable();
            $table->string('device_ip')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('survey_responses')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('survey_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->decimal('numeric_value', 8, 2)->nullable();
            $table->json('choice_values')->nullable();
            $table->timestamps();
        });

        Schema::create('feedback_triage_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->foreignId('survey_id')->nullable()->constrained('surveys')->nullOnDelete();
            $table->foreignId('response_id')->nullable()->constrained('survey_responses')->nullOnDelete();
            $table->string('title');
            $table->string('status')->default('New'); // New, Reviewed, In progress, Actioned, Dismissed
            $table->string('priority')->default('Normal'); // Low, Normal, High, Urgent
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('tags')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('flagged_by')->default('school admin');
            $table->dateTime('flagged_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_triage_items');
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('surveys');
    }
};

