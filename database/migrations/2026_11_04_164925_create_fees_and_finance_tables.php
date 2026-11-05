<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fee_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name'); // Tuition Fee, Annual Charges, Transport
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_group_id')->constrained('fee_groups')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();
        });

        Schema::create('fee_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->foreignId('fee_type_id')->constrained('fee_types')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('due_date')->nullable();
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('student_fee_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('fee_master_id')->constrained('fee_masters')->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('status')->default('unpaid'); // unpaid, partial, paid
            $table->timestamps();
        });

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('allocation_id')->nullable()->constrained('student_fee_allocations')->onDelete('set null');
            $table->string('invoice_no')->unique();
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_method')->default('Cash'); // Cash, UPI, Card, NetBanking
            $table->string('transaction_id')->nullable();
            $table->date('payment_date');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title');
            $table->string('category')->default('General'); // Utilities, Infrastructure, Salaries, Events
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('invoice_no')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('student_fee_allocations');
        Schema::dropIfExists('fee_masters');
        Schema::dropIfExists('fee_types');
        Schema::dropIfExists('fee_groups');
    }
};
