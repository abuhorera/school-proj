<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Student Houses (Sports & activities houses)
        if (!Schema::hasTable('student_houses')) {
            Schema::create('student_houses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
                $table->string('name'); // Red Tigers, Blue Falcons, Green Cheetahs, Yellow Eagles
                $table->string('color')->nullable(); // #ef4444, #3b82f6, #10b981, #f59e0b
                $table->string('code')->nullable();
                $table->string('captain')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 2. Student Categories (General, OBC, SC, ST, EWS)
        if (!Schema::hasTable('student_categories')) {
            Schema::create('student_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
                $table->string('name');
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Update Students Table with extended SIS attributes & Soft Deletes
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'house_id')) {
                $table->foreignId('house_id')->nullable()->constrained('student_houses')->onDelete('set null');
            }
            if (!Schema::hasColumn('students', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('student_categories')->onDelete('set null');
            }
            if (!Schema::hasColumn('students', 'religion')) {
                $table->string('religion')->nullable();
            }
            if (!Schema::hasColumn('students', 'height')) {
                $table->decimal('height', 5, 2)->nullable(); // cm
            }
            if (!Schema::hasColumn('students', 'weight')) {
                $table->decimal('weight', 5, 2)->nullable(); // kg
            }
            if (!Schema::hasColumn('students', 'allergies')) {
                $table->string('allergies')->nullable();
            }
            if (!Schema::hasColumn('students', 'medical_alerts')) {
                $table->text('medical_alerts')->nullable();
            }
            if (!Schema::hasColumn('students', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('students', 'deleted_reason')) {
                $table->string('deleted_reason')->nullable();
            }
            if (!Schema::hasColumn('students', 'deleted_by')) {
                $table->string('deleted_by')->nullable();
            }
        });

        // 4. Student Behavior Records
        if (!Schema::hasTable('student_behavior_records')) {
            Schema::create('student_behavior_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->string('type')->default('Positive'); // Positive, Disciplinary, Warning, Award
                $table->string('title');
                $table->integer('points')->default(0); // +5 or -5
                $table->date('date');
                $table->string('reported_by')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 5. TC & Exit Records
        if (!Schema::hasTable('student_exits')) {
            Schema::create('student_exits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->string('tc_number')->unique();
                $table->string('reason')->default('Parent Relocation'); // Parent Relocation, Course Completed, Higher Studies, Personal
                $table->date('exit_date');
                $table->date('certificate_printed')->nullable();
                $table->string('status')->default('TC Issued'); // Applied, In-Review, TC Issued
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('student_exits');
        Schema::dropIfExists('student_behavior_records');
        Schema::dropIfExists('student_categories');
        Schema::dropIfExists('student_houses');
    }
};

