<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Accounts: Income Heads & Incomes
        Schema::create('income_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('income_head_id')->constrained('income_heads')->onDelete('cascade');
            $table->string('name');
            $table->string('invoice_no')->nullable();
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('Cash');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('ifsc_code')->nullable();
            $table->string('branch')->nullable();
            $table->decimal('current_balance', 14, 2)->default(0);
            $table->timestamps();
        });

        // 2. Front Office & Lead Pipeline
        Schema::create('admission_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('class_applying_for')->nullable();
            $table->string('source')->default('Walk-in'); // Website, Referral, Walk-in, Social Media
            $table->string('status')->default('New'); // New, Contacted, Visit Scheduled, Admitted, Closed
            $table->date('next_follow_up')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('purpose');
            $table->string('person_to_meet')->nullable();
            $table->dateTime('in_time');
            $table->dateTime('out_time')->nullable();
            $table->string('id_card_number')->nullable();
            $table->string('status')->default('inside'); // inside, checked_out
            $table->timestamps();
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('complaint_by'); // Parent, Student, Teacher
            $table->string('complaint_type'); // Academic, Transport, Infrastructure, Fees
            $table->string('phone')->nullable();
            $table->date('date');
            $table->text('description');
            $table->text('action_taken')->nullable();
            $table->string('status')->default('Pending'); // Pending, In Progress, Resolved
            $table->timestamps();
        });

        // 3. Classrooms & Infrastructure
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name'); // Block A (Main), Science Block, Junior Wing
            $table->string('code')->nullable();
            $table->integer('floors')->default(3);
            $table->timestamps();
        });

        Schema::create('classroom_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->string('room_number'); // Room 101, Lab A, Auditorium
            $table->string('room_type')->default('Classroom'); // Classroom, Science Lab, Computer Lab, Hall
            $table->integer('capacity')->default(45);
            $table->string('status')->default('Available'); // Available, Occupied, Maintenance
            $table->timestamps();
        });

        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('classroom_rooms')->onDelete('cascade');
            $table->string('booked_by');
            $table->string('title');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status')->default('Approved'); // Pending, Approved, Cancelled
            $table->timestamps();
        });

        // 4. PTM & Lesson Planner
        Schema::create('ptm_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->string('title');
            $table->date('meeting_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('mode')->default('In-Person'); // In-Person, Video Conference
            $table->text('agenda')->nullable();
            $table->string('status')->default('Scheduled');
            $table->timestamps();
        });

        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->integer('week_number')->default(1);
            $table->string('topic');
            $table->text('subtopics')->nullable();
            $table->string('teaching_method')->nullable(); // Lecture, Lab Practical, Interactive Smartboard
            $table->string('status')->default('Approved'); // Draft, In-Review, Approved, Completed
            $table->timestamps();
        });

        // 5. Compliance, Governance & Audits
        Schema::create('compliance_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title');
            $table->string('category'); // CBSE Affiliation, Fire Safety, Building Fitness, Health & Sanitation
            $table->string('issuing_authority')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('Compliant'); // Compliant, Pending Renewal, In Review
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compliance_items');
        Schema::dropIfExists('lesson_plans');
        Schema::dropIfExists('ptm_meetings');
        Schema::dropIfExists('room_bookings');
        Schema::dropIfExists('classroom_rooms');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('admission_enquiries');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('incomes');
        Schema::dropIfExists('expense_heads');
        Schema::dropIfExists('income_heads');
    }
};
