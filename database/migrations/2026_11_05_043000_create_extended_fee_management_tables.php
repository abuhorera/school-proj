<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Fee Discounts Master
        if (!Schema::hasTable('fee_discounts')) {
            Schema::create('fee_discounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->string('name');
                $table->string('code')->unique();
                $table->enum('type', ['percentage', 'fixed'])->default('fixed');
                $table->decimal('amount', 10, 2);
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Fee Challans (3-Part Bank Challans)
        if (!Schema::hasTable('fee_challans')) {
            Schema::create('fee_challans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('student_id');
                $table->string('challan_no')->unique();
                $table->decimal('amount', 10, 2);
                $table->date('due_date');
                $table->string('challan_type')->default('Tuition');
                $table->string('bank_name')->default('HDFC Bank');
                $table->string('account_no')->default('50200088991122');
                $table->enum('status', ['Generated', 'Paid', 'Expired', 'Cancelled'])->default('Generated');
                $table->date('paid_date')->nullable();
                $table->timestamps();
            });
        }

        // 3. Due Slips History
        if (!Schema::hasTable('fee_due_slips')) {
            Schema::create('fee_due_slips', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('student_id');
                $table->string('slip_code')->unique();
                $table->string('month');
                $table->string('basis')->default('Monthly Assessment');
                $table->decimal('previous_due', 10, 2)->default(0);
                $table->decimal('current_due', 10, 2)->default(0);
                $table->decimal('total_due', 10, 2)->default(0);
                $table->date('generated_on');
                $table->timestamps();
            });
        }

        // 4. Late Fine Rules
        if (!Schema::hasTable('fee_fine_rules')) {
            Schema::create('fee_fine_rules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->string('name');
                $table->enum('fine_type', ['Daily', 'Fixed', 'Percentage'])->default('Daily');
                $table->integer('grace_days')->default(5);
                $table->decimal('amount', 10, 2)->default(20.00);
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Online Payments Gateway Logs
        if (!Schema::hasTable('online_payments')) {
            Schema::create('online_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->default(1);
                $table->foreignId('student_id');
                $table->string('order_id')->unique();
                $table->string('transaction_ref')->nullable();
                $table->decimal('amount', 10, 2);
                $table->decimal('gateway_charge', 8, 2)->default(0.00);
                $table->string('gateway')->default('Razorpay');
                $table->string('payment_mode')->default('UPI');
                $table->enum('status', ['Success', 'Pending', 'Failed'])->default('Success');
                $table->dateTime('payment_date');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('online_payments');
        Schema::dropIfExists('fee_fine_rules');
        Schema::dropIfExists('fee_due_slips');
        Schema::dropIfExists('fee_challans');
        Schema::dropIfExists('fee_discounts');
    }
};

