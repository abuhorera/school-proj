<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('target_role')->default('all'); // all, students, staff, parents
            $table->date('published_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->string('location')->nullable();
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed
            $table->timestamps();
        });

        Schema::create('gate_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('pass_number')->unique();
            $table->string('visitor_name');
            $table->string('phone')->nullable();
            $table->string('purpose');
            $table->string('person_to_meet')->nullable();
            $table->dateTime('in_time');
            $table->dateTime('out_time')->nullable();
            $table->string('status')->default('inside'); // inside, exited
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gate_passes');
        Schema::dropIfExists('events');
        Schema::dropIfExists('notices');
    }
};
