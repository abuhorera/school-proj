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
        // 1. QR Attendance Settings
        Schema::create('qr_attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->boolean('auto_attendance')->default(true);
            $table->text('machine_types')->nullable(); // JSON: ['camera', 'barcode_gun']
            $table->string('camera_facing')->default('environment'); // environment, user
            $table->text('allowed_formats')->nullable(); // JSON: ['QR_CODE', 'CODE_128', 'EAN_13']
            $table->integer('cooldown_ms')->default(3000);
            $table->string('late_cutoff_time')->default('08:30');
            $table->string('half_day_cutoff_time')->default('12:00');
            $table->timestamps();
        });

        // 2. QR Attendance Scans (Daily attendance register entries)
        Schema::create('qr_attendance_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->date('date');
            $table->string('person_type'); // student, staff
            $table->unsignedBigInteger('person_id');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('status')->default('Present'); // Present, Late, Half Day, Absent
            $table->string('device_name')->default('Main Gate Scanner');
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->string('qr_code_raw')->nullable();
            $table->timestamps();

            $table->index(['date', 'person_type', 'person_id']);
        });

        // 3. QR Scan Audits (Detailed security, device & operator decision log)
        Schema::create('qr_scan_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->dateTime('scan_time');
            $table->string('outcome')->default('Accepted'); // Accepted, Blocked
            $table->string('result_reason'); // Marked IN, Marked OUT, Already Done, Not Recognised, Off Campus, Fake GPS
            $table->string('operator_name')->default('Gate Officer / System');
            $table->string('target_type')->default('Student'); // Student, Staff, Unknown
            $table->string('target_name')->nullable();
            $table->string('target_identifier')->nullable(); // Admission no or staff code
            $table->string('mode')->default('Camera'); // Camera, Barcode Gun, Kiosk
            $table->string('location')->default('Main Gate Terminal');
            $table->string('device')->default('Gate Terminal 01 (Chrome/Android)');
            $table->string('ip_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_scan_audits');
        Schema::dropIfExists('qr_attendance_scans');
        Schema::dropIfExists('qr_attendance_settings');
    }
};

