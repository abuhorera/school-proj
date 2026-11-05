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
        // 1. Syllabus Units & Topics
        Schema::create('study_syllabuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('school_class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('parent_id')->nullable(); // For hierarchical tree
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order_no')->default(0);
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->timestamps();
        });

        // 2. Study Materials (PDF, Video, Links)
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('school_class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('syllabus_id')->nullable();
            $table->string('title');
            $table->string('type')->default('file'); // file, video, link
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->string('content_link')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->timestamps();
        });

        // 3. Classwork & Daily Teacher Logbook
        Schema::create('study_classworks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('school_class_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->date('date');
            $table->string('type')->default('Classwork'); // Classwork, Activity, Revision, Practical, Unit Test
            $table->string('topic');
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // 4. Homework & Assignments
        Schema::create('study_homeworks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('school_class_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->unsignedBigInteger('study_material_id')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('Active'); // Active, Closed, Evaluated
            $table->timestamps();
        });

        // 5. Homework Submissions & Grading
        Schema::create('study_homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homework_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamp('submitted_at')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->decimal('marks', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->default('Submitted'); // Submitted, Evaluated, Pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_homework_submissions');
        Schema::dropIfExists('study_homeworks');
        Schema::dropIfExists('study_classworks');
        Schema::dropIfExists('study_materials');
        Schema::dropIfExists('study_syllabuses');
    }
};
