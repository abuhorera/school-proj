<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Chapters & Topics
        Schema::create('question_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('name');
            $table->string('chapter')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // 2. Question Bank Master
        Schema::create('online_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('school_class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('question_topic_id')->nullable();
            $table->string('question_type')->default('single_choice'); // single_choice (MCQ), multiple_choice, true_false, short_answer
            $table->string('difficulty')->default('Medium'); // Easy, Medium, Hard
            $table->text('question');
            $table->json('options')->nullable(); // [{"key": "A", "text": "Option 1"}, ...]
            $table->json('correct_answers')->nullable(); // ["A"]
            $table->decimal('marks', 8, 2)->default(1.00);
            $table->decimal('negative_marks', 8, 2)->default(0.00);
            $table->text('solution_html')->nullable();
            $table->text('hint_html')->nullable();
            $table->string('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('question_topic_id')->references('id')->on('question_topics')->onDelete('set null');
        });

        // 3. Online Exams Master
        Schema::create('online_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('school_class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('title');
            $table->string('kind')->default('Quiz'); // Exam, Quiz, Practice
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('duration')->default(30); // in minutes
            $table->decimal('total_marks', 8, 2)->default(100.00);
            $table->decimal('passing_percentage', 5, 2)->default(40.00);
            $table->boolean('negative_marking')->default(false);
            $table->decimal('negative_mark_rate', 5, 2)->default(0.25);
            $table->boolean('auto_publish_results')->default(true);
            $table->boolean('randomize_questions')->default(false);
            $table->text('instructions')->nullable();
            $table->string('status')->default('Upcoming'); // Draft, Upcoming, Active, Completed
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // 4. Online Exam Questions Pivot
        Schema::create('online_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('online_exam_id');
            $table->unsignedBigInteger('online_question_id');
            $table->decimal('marks', 8, 2)->default(1.00);
            $table->integer('sort_order')->default(1);
            $table->timestamps();

            $table->foreign('online_exam_id')->references('id')->on('online_exams')->onDelete('cascade');
            $table->foreign('online_question_id')->references('id')->on('online_questions')->onDelete('cascade');
        });

        // 5. Online Exam Attempts & Student Results
        Schema::create('online_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('online_exam_id');
            $table->unsignedBigInteger('student_id');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->integer('total_questions')->default(0);
            $table->integer('total_answered')->default(0);
            $table->integer('correct_answers_count')->default(0);
            $table->integer('incorrect_answers_count')->default(0);
            $table->decimal('score_obtained', 8, 2)->default(0.00);
            $table->decimal('percentage', 5, 2)->default(0.00);
            $table->string('status')->default('Passed'); // Passed, Failed
            $table->json('student_answers')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('online_exam_id')->references('id')->on('online_exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('online_exam_attempts');
        Schema::dropIfExists('online_exam_questions');
        Schema::dropIfExists('online_exams');
        Schema::dropIfExists('online_questions');
        Schema::dropIfExists('question_topics');
    }
};

