<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Postal Records Table
        if (!Schema::hasTable('postal_records')) {
            Schema::create('postal_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->enum('type', ['Dispatch', 'Receive'])->default('Receive');
                $table->date('date');
                $table->string('from_name');
                $table->string('to_name');
                $table->text('to_address')->nullable();
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Campus Workers Table
        if (!Schema::hasTable('campus_workers')) {
            Schema::create('campus_workers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->default(1);
                $table->string('badge_number')->unique();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('category')->default('General'); // Electrician, Plumber, Security, Housekeeping, etc.
                $table->string('agency_name')->nullable();
                $table->string('agency_contact')->nullable();
                $table->string('id_proof')->nullable();
                $table->date('valid_from');
                $table->date('valid_to');
                $table->string('photo')->nullable();
                $table->boolean('police_verified')->default(false);
                $table->date('police_verified_on')->nullable();
                $table->string('police_document')->nullable();
                $table->boolean('is_on_campus')->default(true);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Extend admission_enquiries
        if (Schema::hasTable('admission_enquiries')) {
            Schema::table('admission_enquiries', function (Blueprint $table) {
                if (!Schema::hasColumn('admission_enquiries', 'parent_name')) {
                    $table->string('parent_name')->nullable();
                }
                if (!Schema::hasColumn('admission_enquiries', 'school_class_id')) {
                    $table->unsignedBigInteger('school_class_id')->nullable();
                }
                if (!Schema::hasColumn('admission_enquiries', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable();
                }
                if (!Schema::hasColumn('admission_enquiries', 'enquiry_date')) {
                    $table->date('enquiry_date')->nullable();
                }
            });
        }

        // 4. Extend complaints
        if (Schema::hasTable('complaints')) {
            Schema::table('complaints', function (Blueprint $table) {
                if (!Schema::hasColumn('complaints', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable();
                }
            });
        }

        // 5. Extend gate_passes
        if (Schema::hasTable('gate_passes')) {
            Schema::table('gate_passes', function (Blueprint $table) {
                if (!Schema::hasColumn('gate_passes', 'student_id')) {
                    $table->unsignedBigInteger('student_id')->nullable();
                }
                if (!Schema::hasColumn('gate_passes', 'reason_text')) {
                    $table->string('reason_text')->nullable();
                }
                if (!Schema::hasColumn('gate_passes', 'escort_name')) {
                    $table->string('escort_name')->nullable();
                }
                if (!Schema::hasColumn('gate_passes', 'escort_relation')) {
                    $table->string('escort_relation')->nullable();
                }
                if (!Schema::hasColumn('gate_passes', 'escort_phone')) {
                    $table->string('escort_phone')->nullable();
                }
                if (!Schema::hasColumn('gate_passes', 'escort_id_proof')) {
                    $table->string('escort_id_proof')->nullable();
                }
                if (!Schema::hasColumn('gate_passes', 'gate_name')) {
                    $table->string('gate_name')->default('Main Gate');
                }
                if (!Schema::hasColumn('gate_passes', 'is_return_expected')) {
                    $table->boolean('is_return_expected')->default(false);
                }
                if (!Schema::hasColumn('gate_passes', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('postal_records');
        Schema::dropIfExists('campus_workers');
    }
};

