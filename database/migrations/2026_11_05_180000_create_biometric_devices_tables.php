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
        // 1. Biometric Devices Master Table
        Schema::create('biometric_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('name');
            $table->string('serial_number')->nullable();
            $table->string('brand')->default('ZKTeco'); // ZKTeco, eSSL, Realtime, Hikvision, Mantra, etc.
            $table->string('push_mode')->default('agent'); // agent, cloud_push, camera
            $table->string('ip_address')->nullable();
            $table->integer('port')->default(4370);
            $table->string('direction')->default('auto'); // auto, in, out
            $table->string('status')->default('active'); // active, inactive
            $table->string('online_status')->default('offline'); // online, offline, agent_silent
            $table->boolean('is_attendance')->default(true);
            $table->boolean('is_monitoring')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->string('firmware_version')->nullable();
            $table->integer('user_count')->default(0);
            $table->integer('fingerprint_count')->default(0);
            $table->integer('face_count')->default(0);
            $table->integer('log_count')->default(0);
            $table->json('device_info')->nullable();
            $table->timestamps();
        });

        // 2. Raw Biometric Attendance Logs (Punches straight off the devices)
        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('person_type')->default('student'); // student, staff, unmapped
            $table->unsignedBigInteger('person_id')->nullable();
            $table->string('user_device_pin')->nullable();
            $table->timestamp('punch_time');
            $table->string('direction')->default('Check-IN'); // Check-IN, Check-OUT
            $table->string('method')->default('Fingerprint'); // Fingerprint, Face, RFID Card, Password
            $table->boolean('processed')->default(true);
            $table->string('status')->default('synced'); // synced, pending, error
            $table->text('raw_data')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('biometric_devices')->onDelete('cascade');
        });

        // 3. Face Monitoring AI Detection Events
        Schema::create('biometric_monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('person_type')->default('student'); // student, staff, unknown
            $table->unsignedBigInteger('person_id')->nullable();
            $table->string('person_name')->default('Unknown / Visitor');
            $table->string('snapshot_url')->nullable();
            $table->string('camera_name')->default('Main Gate AI Camera');
            $table->string('detection_type')->default('Face'); // Face, QR Code
            $table->decimal('confidence', 5, 2)->default(98.50);
            $table->string('liveness')->default('Verified'); // Verified, Spoof Suspected, Real
            $table->timestamp('detected_at');
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('biometric_devices')->onDelete('cascade');
        });

        // 4. Windows Agent & Sync Engine Debug System Logs
        Schema::create('biometric_system_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('level')->default('INFO'); // INFO, WARNING, ERROR, DEBUG
            $table->text('message');
            $table->timestamp('logged_at');
            $table->timestamps();
        });

        // 5. Device Remote Commands Queue
        Schema::create('biometric_device_commands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->string('command'); // force_sync, sync_time, voice_test, reboot, shutdown, clear_logs, etc.
            $table->string('status')->default('pending'); // pending, executed, failed
            $table->text('response')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('biometric_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometric_device_commands');
        Schema::dropIfExists('biometric_system_logs');
        Schema::dropIfExists('biometric_monitoring_logs');
        Schema::dropIfExists('biometric_logs');
        Schema::dropIfExists('biometric_devices');
    }
};

