<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Core Competencies Master (7 Core 21st-Century Competencies)
        if (!Schema::hasTable('cbc_competencies')) {
            Schema::create('cbc_competencies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->string('name');
                $table->string('category')->default('Core Competency'); // Foundational, Socio-Emotional, Cognitive, Digital
                $table->text('description')->nullable();
                $table->text('indicators')->nullable(); // JSON list of observable behaviors
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Curriculum Strands (Major Subject Domain Strands)
        if (!Schema::hasTable('cbc_strands')) {
            Schema::create('cbc_strands', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('subject_id')->nullable();
                $table->string('name'); // e.g., Numbers & Operations, Environmental Conservation
                $table->string('code', 30)->nullable();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(1);
                $table->timestamps();
            });
        }

        // 3. Learning Outcomes (Granular Demonstrable Skills)
        if (!Schema::hasTable('cbc_outcomes')) {
            Schema::create('cbc_outcomes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('cbc_strand_id')->constrained('cbc_strands')->onDelete('cascade');
                $table->string('name'); // Demonstrates understanding of fractions in real life
                $table->string('code', 30)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 4. CBC Assessments (Formative, Summative, Project Rubrics)
        if (!Schema::hasTable('cbc_assessments')) {
            Schema::create('cbc_assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('class_id')->nullable();
                $table->foreignId('section_id')->nullable();
                $table->foreignId('subject_id')->nullable();
                $table->string('title'); // Term 1 Practical Fieldwork Rubric
                $table->date('assessment_date')->nullable();
                $table->string('type')->default('Formative'); // Formative, Summative, Portfolio, Peer
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 5. Student Rubric Evaluations (EE, ME, AE, BE)
        if (!Schema::hasTable('cbc_student_evaluations')) {
            Schema::create('cbc_student_evaluations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('cbc_assessment_id')->constrained('cbc_assessments')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('cbc_outcome_id')->nullable();
                $table->string('rubric_level', 10)->default('ME'); // EE: Exceeding, ME: Meeting, AE: Approaching, BE: Below
                $table->text('teacher_feedback')->nullable();
                $table->timestamps();
            });
        }

        // 6. Senior Secondary Pathways (STEM, Humanities, Arts & Sports)
        if (!Schema::hasTable('cbc_pathways')) {
            Schema::create('cbc_pathways', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->string('name'); // STEM, Arts & Sports, Social Sciences
                $table->text('description')->nullable();
                $table->string('icon', 50)->nullable()->default('fa-solid fa-graduation-cap');
                $table->timestamps();
            });
        }

        // 7. Specialized Subject Tracks under Pathways
        if (!Schema::hasTable('cbc_tracks')) {
            Schema::create('cbc_tracks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('cbc_pathway_id')->constrained('cbc_pathways')->onDelete('cascade');
                $table->string('name'); // Pure Sciences & Medical, Visual Arts & Design
                $table->text('description')->nullable();
                $table->text('compulsory_subjects')->nullable(); // JSON list
                $table->text('elective_subjects')->nullable(); // JSON list
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cbc_tracks');
        Schema::dropIfExists('cbc_pathways');
        Schema::dropIfExists('cbc_student_evaluations');
        Schema::dropIfExists('cbc_assessments');
        Schema::dropIfExists('cbc_outcomes');
        Schema::dropIfExists('cbc_strands');
        Schema::dropIfExists('cbc_competencies');
    }
};

