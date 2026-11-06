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
        // 1. Create hostels table
        if (!Schema::hasTable('hostels')) {
            Schema::create('hostels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('Boys'); // Boys, Girls, Mixed
                $table->string('address')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }

        // 2. Create hostel_room_types table
        if (!Schema::hasTable('hostel_room_types')) {
            Schema::create('hostel_room_types', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // AC, Non AC, Dormitory, etc.
                $table->decimal('fare', 10, 2)->default(0.00);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Drop old hostel_rooms and recreate with new schema
        Schema::dropIfExists('hostel_allocations');
        Schema::dropIfExists('hostel_rooms');

        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('cascade');
            $table->unsignedBigInteger('block_id')->nullable(); // compatibility alias
            $table->foreignId('room_type_id')->constrained('hostel_room_types')->onDelete('cascade');
            $table->string('room_number');
            $table->integer('capacity')->default(1);
            $table->timestamps();
        });

        // 4. Create hostel_allocations table
        Schema::create('hostel_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_room_id')->constrained('hostel_rooms')->onDelete('cascade');
            $table->unsignedBigInteger('room_id')->nullable(); // compatibility alias
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->dateTime('allocated_at')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_allocations');
        Schema::dropIfExists('hostel_rooms');
        Schema::dropIfExists('hostel_room_types');
        Schema::dropIfExists('hostels');
    }
};

