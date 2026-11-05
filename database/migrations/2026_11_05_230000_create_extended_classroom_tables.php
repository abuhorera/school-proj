<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Extend buildings table
        Schema::table('buildings', function (Blueprint $table) {
            if (!Schema::hasColumn('buildings', 'notes')) {
                $table->text('notes')->nullable()->after('floors');
            }
            if (!Schema::hasColumn('buildings', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('notes');
            }
        });

        // 2. Extend classroom_rooms table
        Schema::table('classroom_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('classroom_rooms', 'name')) {
                $table->string('name')->nullable()->after('room_number');
            }
            if (!Schema::hasColumn('classroom_rooms', 'floor')) {
                $table->integer('floor')->default(1)->after('name');
            }
            if (!Schema::hasColumn('classroom_rooms', 'exam_capacity')) {
                $table->integer('exam_capacity')->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('classroom_rooms', 'facilities_json')) {
                $table->json('facilities_json')->nullable()->after('exam_capacity');
            }
            if (!Schema::hasColumn('classroom_rooms', 'home_section_id')) {
                $table->foreignId('home_section_id')->nullable()->constrained('sections')->nullOnDelete()->after('facilities_json');
            }
            if (!Schema::hasColumn('classroom_rooms', 'is_exam_hall')) {
                $table->boolean('is_exam_hall')->default(false)->after('home_section_id');
            }
            if (!Schema::hasColumn('classroom_rooms', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        // 3. Create room_temporary_allocations
        if (!Schema::hasTable('room_temporary_allocations')) {
            Schema::create('room_temporary_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
                $table->foreignId('room_id')->constrained('classroom_rooms')->cascadeOnDelete();
                $table->date('from_date');
                $table->date('to_date');
                $table->string('reason')->nullable();
                $table->timestamps();
            });
        }

        // 4. Create room_label_matches
        if (!Schema::hasTable('room_label_matches')) {
            Schema::create('room_label_matches', function (Blueprint $table) {
                $table->id();
                $table->string('label')->unique();
                $table->foreignId('room_id')->nullable()->constrained('classroom_rooms')->nullOnDelete();
                $table->integer('timetable_count')->default(0);
                $table->integer('exam_count')->default(0);
                $table->timestamps();
            });
        }

        // 5. Create room_maintenances
        if (!Schema::hasTable('room_maintenances')) {
            Schema::create('room_maintenances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_id')->constrained('classroom_rooms')->cascadeOnDelete();
                $table->string('problem');
                $table->string('priority')->default('Normal'); // Urgent, High, Normal, Low
                $table->string('given_to')->nullable();
                $table->string('status')->default('Reported'); // Reported, Being fixed, Resolved, Closed
                $table->boolean('is_unusable')->default(false);
                $table->string('reported_by')->default('School Admin');
                $table->date('reported_at')->nullable();
                $table->date('resolved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 6. Create room_booking_settings
        if (!Schema::hasTable('room_booking_settings')) {
            Schema::create('room_booking_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('teachers_can_request')->default(true);
                $table->boolean('requests_need_approval')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('room_booking_settings');
        Schema::dropIfExists('room_maintenances');
        Schema::dropIfExists('room_label_matches');
        Schema::dropIfExists('room_temporary_allocations');
    }
};

