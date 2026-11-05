<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Exam Types Master (Theory, Practical, Oral / Viva, Internal, Periodic Test, Project)
        if (!Schema::hasTable('exam_types')) {
            Schema::create('exam_types', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->string('name');
                $table->string('abbreviation', 20)->nullable();
                $table->string('weightage', 20)->nullable()->default('100%');
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Grading Scales Master (A1, A2, B1, B2, C1, C2, D, E/F)
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->string('name', 20); // A1, A2, etc.
                $table->decimal('min_percentage', 5, 2)->default(0);
                $table->decimal('max_percentage', 5, 2)->default(100);
                $table->decimal('grade_point', 4, 1)->default(10.0);
                $table->string('description')->nullable(); // Outstanding, Excellent, Good, Pass, Needs Improvement
                $table->timestamps();
            });
        }

        // 3. Co-curricular Grades (Work Education, Art Education, Health & Physical, Discipline)
        if (!Schema::hasTable('cocurricular_grades')) {
            Schema::create('cocurricular_grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('exam_id');
                $table->foreignId('student_id');
                $table->string('area_name'); // Work Education, Art Education, Physical Health, Discipline
                $table->string('grade', 10)->default('A'); // A, B, C, D, E
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 4. Uploaded External Marksheets (PDF Marksheets)
        if (!Schema::hasTable('uploaded_marksheets')) {
            Schema::create('uploaded_marksheets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('student_id');
                $table->foreignId('exam_id')->nullable();
                $table->foreignId('class_id')->nullable();
                $table->foreignId('section_id')->nullable();
                $table->string('title');
                $table->string('file_path')->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        // 5. Student Exam Remarks & Teacher Conduct Feedback
        if (!Schema::hasTable('student_exam_remarks')) {
            Schema::create('student_exam_remarks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('student_id');
                $table->foreignId('exam_id');
                $table->text('class_teacher_remarks')->nullable();
                $table->text('principal_remarks')->nullable();
                $table->decimal('attendance_percentage', 5, 2)->nullable()->default(92.5);
                $table->string('conduct')->nullable()->default('Exemplary');
                $table->timestamps();
            });
        }

        // 6. Extend exams table
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('exams', 'class_ids')) {
                $table->text('class_ids')->nullable()->after('description'); // JSON array of class IDs
            }
            if (!Schema::hasColumn('exams', 'status')) {
                $table->string('status', 30)->default('Active')->after('end_date'); // Upcoming, Active, Completed, Published
            }
            if (!Schema::hasColumn('exams', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('status');
            }
        });

        // 7. Extend exam_marks table
        Schema::table('exam_marks', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_marks', 'is_absent')) {
                $table->boolean('is_absent')->default(false)->after('marks_obtained');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_exam_remarks');
        Schema::dropIfExists('uploaded_marksheets');
        Schema::dropIfExists('cocurricular_grades');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('exam_types');
    }
};

