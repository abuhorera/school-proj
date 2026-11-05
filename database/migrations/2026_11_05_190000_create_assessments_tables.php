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
        // 1. Classroom Assessments Table
        Schema::create('classroom_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('academic_session_id')->default(1);
            $table->string('title');
            $table->string('type')->default('Quiz'); // Quiz, Test, Oral, Practical, Assignment, Other
            $table->string('conducted_via')->default('Paper'); // Paper, Oral, Online Exam module, Other, Not recorded
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('section_id')->nullable(); // Null means whole class
            $table->unsignedBigInteger('subject_id');
            $table->decimal('total_marks', 6, 2)->default(30.00);
            $table->decimal('passing_marks', 6, 2)->default(12.00);
            $table->string('frequency')->default('One-off'); // One-off, Weekly, Fortnightly, Monthly, Custom
            $table->date('scheduled_date')->nullable();
            $table->text('instructions')->nullable();
            $table->string('status')->default('Scheduled'); // Draft, Scheduled, Completed, Grading
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        // 2. Assessment Sittings / Occurrences
        Schema::create('assessment_occurrences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->date('sitting_date');
            $table->string('status')->default('Grading'); // Grading, Published, Draft
            $table->integer('marks_entered_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('assessment_id')->references('id')->on('classroom_assessments')->onDelete('cascade');
        });

        // 3. Student Marks / Results
        Schema::create('assessment_student_marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('occurrence_id');
            $table->unsignedBigInteger('student_id');
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->boolean('is_absent')->default(false);
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->foreign('occurrence_id')->references('id')->on('assessment_occurrences')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_student_marks');
        Schema::dropIfExists('assessment_occurrences');
        Schema::dropIfExists('classroom_assessments');
    }
};

