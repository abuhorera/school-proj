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
        // 1. OSM Evaluation Sessions
        Schema::create('osm_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('title');
            $table->unsignedBigInteger('school_class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('exam_type_id')->nullable();
            $table->decimal('total_marks', 8, 2)->default(100.00);
            $table->date('evaluation_deadline')->nullable();
            $table->string('moderation_level')->default('sample_10'); // none, sample_10, sample_20, full, borderline
            $table->boolean('sync_to_report_card')->default(false);
            $table->boolean('publish_to_parent')->default(false);
            $table->boolean('anonymous_mode')->default(true);
            $table->boolean('allow_annotations')->default(true);
            $table->string('status')->default('Evaluating'); // Draft, Uploading, Ready, Evaluating, Moderation, Completed, Archived
            $table->integer('total_sheets')->default(0);
            $table->integer('evaluated_sheets')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // 2. OSM Questions Structure
        Schema::create('osm_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('osm_session_id');
            $table->integer('question_number')->default(1);
            $table->string('question_label')->default('Q1');
            $table->text('question_text')->nullable();
            $table->decimal('max_marks', 8, 2)->default(10.00);
            $table->boolean('is_compulsory')->default(true);
            $table->integer('order_seq')->default(1);
            $table->timestamps();

            $table->foreign('osm_session_id')->references('id')->on('osm_sessions')->onDelete('cascade');
        });

        // 3. OSM Answer Papers / Booklets
        Schema::create('osm_papers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('osm_session_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('evaluator_staff_id')->nullable();
            $table->unsignedBigInteger('moderator_staff_id')->nullable();
            $table->string('anonymous_code')->nullable();
            $table->string('original_file_name')->nullable();
            $table->integer('page_count')->default(1);
            $table->string('file_path')->nullable();
            $table->text('pages_json')->nullable(); // JSON list of page image assets
            $table->decimal('total_score', 8, 2)->default(0.00);
            $table->decimal('moderated_score', 8, 2)->nullable();
            $table->boolean('is_moderated')->default(false);
            $table->string('status')->default('Pending'); // Pending, Evaluating, Evaluated, Moderated, Rejected
            $table->string('reject_reason')->nullable();
            $table->text('evaluator_notes')->nullable();
            $table->text('moderator_notes')->nullable();
            $table->integer('elapsed_seconds')->default(0);
            $table->dateTime('evaluated_at')->nullable();
            $table->dateTime('moderated_at')->nullable();
            $table->timestamps();

            $table->foreign('osm_session_id')->references('id')->on('osm_sessions')->onDelete('cascade');
        });

        // 4. OSM Paper Question Scores
        Schema::create('osm_paper_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('osm_paper_id');
            $table->unsignedBigInteger('osm_question_id');
            $table->decimal('marks_awarded', 8, 2)->default(0.00);
            $table->text('comment')->nullable();
            $table->boolean('is_checked')->default(true);
            $table->timestamps();

            $table->foreign('osm_paper_id')->references('id')->on('osm_papers')->onDelete('cascade');
            $table->foreign('osm_question_id')->references('id')->on('osm_questions')->onDelete('cascade');
        });

        // 5. OSM Page Annotations (Marks stamps, ticks, highlights, comments on canvas)
        Schema::create('osm_annotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('osm_paper_id');
            $table->integer('page_number')->default(1);
            $table->string('tool_type')->default('tick'); // tick, cross, circle, underline, highlight, pen, comment
            $table->decimal('x', 8, 2)->default(0);
            $table->decimal('y', 8, 2)->default(0);
            $table->text('data_json')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('osm_paper_id')->references('id')->on('osm_papers')->onDelete('cascade');
        });

        // 6. OSM Evaluator Allocations
        Schema::create('osm_session_evaluators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('osm_session_id');
            $table->unsignedBigInteger('staff_id');
            $table->integer('assigned_count')->default(0);
            $table->integer('completed_count')->default(0);
            $table->string('status')->default('Active'); // Active, Completed
            $table->timestamps();

            $table->foreign('osm_session_id')->references('id')->on('osm_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('osm_session_evaluators');
        Schema::dropIfExists('osm_annotations');
        Schema::dropIfExists('osm_paper_scores');
        Schema::dropIfExists('osm_papers');
        Schema::dropIfExists('osm_questions');
        Schema::dropIfExists('osm_sessions');
    }
};

