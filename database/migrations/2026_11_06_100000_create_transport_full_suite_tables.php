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
        // 1. Alter transport_vehicles
        if (Schema::hasTable('transport_vehicles')) {
            Schema::table('transport_vehicles', function (Blueprint $table) {
                if (!Schema::hasColumn('transport_vehicles', 'driver_id')) {
                    $table->unsignedBigInteger('driver_id')->nullable();
                }
                if (!Schema::hasColumn('transport_vehicles', 'driver_password')) {
                    $table->string('driver_password')->nullable();
                }
                if (!Schema::hasColumn('transport_vehicles', 'gps_device_imei')) {
                    $table->string('gps_device_imei')->nullable();
                }
                if (!Schema::hasColumn('transport_vehicles', 'api_token')) {
                    $table->string('api_token')->nullable();
                }
                if (!Schema::hasColumn('transport_vehicles', 'tracking_enabled')) {
                    $table->boolean('tracking_enabled')->default(true);
                }
                if (!Schema::hasColumn('transport_vehicles', 'note')) {
                    $table->text('note')->nullable();
                }
                if (!Schema::hasColumn('transport_vehicles', 'current_latitude')) {
                    $table->decimal('current_latitude', 10, 6)->nullable();
                }
                if (!Schema::hasColumn('transport_vehicles', 'current_longitude')) {
                    $table->decimal('current_longitude', 10, 6)->nullable();
                }
                if (!Schema::hasColumn('transport_vehicles', 'speed')) {
                    $table->integer('speed')->default(0);
                }
                if (!Schema::hasColumn('transport_vehicles', 'last_ping')) {
                    $table->timestamp('last_ping')->nullable();
                }
            });
        }

        // 2. Alter transport_routes
        if (Schema::hasTable('transport_routes')) {
            Schema::table('transport_routes', function (Blueprint $table) {
                if (!Schema::hasColumn('transport_routes', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('transport_routes', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('transport_routes', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        // 3. Create transport_drivers
        if (!Schema::hasTable('transport_drivers')) {
            Schema::create('transport_drivers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('driver_id_code')->unique()->nullable();
                $table->string('phone')->nullable();
                $table->string('license_number')->nullable();
                $table->date('license_expiry')->nullable();
                $table->string('address')->nullable();
                $table->string('status')->default('Active'); // Active, Suspended, Left
                $table->string('device_info')->nullable();
                $table->timestamp('last_signed_in_at')->nullable();
                $table->timestamps();
            });
        }

        // 4. Create transport_route_schedules (Runs)
        if (!Schema::hasTable('transport_route_schedules')) {
            Schema::create('transport_route_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transport_route_id')->constrained('transport_routes')->onDelete('cascade');
                $table->string('label'); // e.g. Morning Inbound, Afternoon Outbound
                $table->string('direction')->default('Inbound'); // Inbound, Outbound, Both
                $table->string('start_time')->nullable();
                $table->string('end_time')->nullable();
                $table->text('days_of_week_json')->nullable();
                $table->string('status')->default('Active'); // Active, Suspended
                $table->timestamps();
            });
        }

        // 5. Create transport_route_stops
        if (!Schema::hasTable('transport_route_stops')) {
            Schema::create('transport_route_stops', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transport_route_id')->constrained('transport_routes')->onDelete('cascade');
                $table->string('name');
                $table->decimal('latitude', 10, 6)->nullable();
                $table->decimal('longitude', 10, 6)->nullable();
                $table->decimal('fare', 10, 2)->nullable();
                $table->string('pickup_time')->nullable();
                $table->string('drop_time')->nullable();
                $table->integer('order_sequence')->default(1);
                $table->timestamps();
            });
        }

        // 6. Create transport_student_assignments
        if (!Schema::hasTable('transport_student_assignments')) {
            Schema::create('transport_student_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('transport_route_id')->constrained('transport_routes')->onDelete('cascade');
                $table->foreignId('transport_route_stop_id')->nullable()->constrained('transport_route_stops')->onDelete('set null');
                $table->date('start_date');
                $table->date('stop_date')->nullable();
                $table->string('status')->default('Active'); // Active, Cancelled
                $table->string('fee_status')->default('Paid'); // Paid, Pending, Exempt
                $table->timestamps();
            });
        }

        // 7. Create transport_fee_settings
        if (!Schema::hasTable('transport_fee_settings')) {
            Schema::create('transport_fee_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fee_type_id')->nullable();
                $table->unsignedBigInteger('fee_group_id')->nullable();
                $table->integer('billing_months')->default(10);
                $table->string('session_start_month')->default('April');
                $table->integer('due_day_of_month')->default(10);
                $table->boolean('auto_assign_on_route')->default(true);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 8. Create transport_boarding_logs
        if (!Schema::hasTable('transport_boarding_logs')) {
            Schema::create('transport_boarding_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transport_route_id')->nullable()->constrained('transport_routes')->onDelete('set null');
                $table->foreignId('transport_vehicle_id')->nullable()->constrained('transport_vehicles')->onDelete('set null');
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('transport_route_stop_id')->nullable()->constrained('transport_route_stops')->onDelete('set null');
                $table->string('trip_type')->default('Pickup'); // Pickup, Drop
                $table->date('date');
                $table->string('time')->nullable();
                $table->string('status')->default('Boarded'); // Boarded, Absent, De-boarded
                $table->string('marked_by')->default('Driver');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_boarding_logs');
        Schema::dropIfExists('transport_fee_settings');
        Schema::dropIfExists('transport_student_assignments');
        Schema::dropIfExists('transport_route_stops');
        Schema::dropIfExists('transport_route_schedules');
        Schema::dropIfExists('transport_drivers');
    }
};

