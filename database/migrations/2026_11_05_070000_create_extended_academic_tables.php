<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Update Classes table
        Schema::table('classes', function (Blueprint $table) {
            if (!Schema::hasColumn('classes', 'coordinator_id')) {
                $table->foreignId('coordinator_id')->nullable()->constrained('staff')->onDelete('set null');
            }
        });

        // 2. Update Sections table
        Schema::table('sections', function (Blueprint $table) {
            if (!Schema::hasColumn('sections', 'in_charge_teacher_id')) {
                $table->foreignId('in_charge_teacher_id')->nullable()->constrained('staff')->onDelete('set null');
            }
        });

        // 3. Update Subjects table
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'group')) {
                $table->string('group')->default('General')->after('type'); // Languages, Science & Math, Social Studies, Elective
            }
            if (!Schema::hasColumn('subjects', 'is_elective')) {
                $table->boolean('is_elective')->default(false)->after('group');
            }
        });

        // 4. Class Subject Teacher Allocations
        if (!Schema::hasTable('class_subjects')) {
            Schema::create('class_subjects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
                $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
                $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->foreignId('teacher_id')->nullable()->constrained('staff')->onDelete('set null');
                $table->boolean('is_elective')->default(false);
                $table->timestamps();
            });
        }

        // 5. Time Slots / Periods
        if (!Schema::hasTable('time_slots')) {
            Schema::create('time_slots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
                $table->string('name'); // Period 1, Assembly, Morning Break, Period 2
                $table->time('start_time');
                $table->time('end_time');
                $table->boolean('is_break')->default(false);
                $table->integer('sort_order')->default(1);
                $table->timestamps();
            });
        }

        // 6. Timetables (Weekly Matrix)
        if (!Schema::hasTable('timetables')) {
            Schema::create('timetables', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
                $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
                $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
                $table->string('day_of_week'); // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday
                $table->foreignId('time_slot_id')->constrained('time_slots')->onDelete('cascade');
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
                $table->foreignId('teacher_id')->nullable()->constrained('staff')->onDelete('set null');
                $table->string('room_number')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('time_slots');
        Schema::dropIfExists('class_subjects');
    }
};

