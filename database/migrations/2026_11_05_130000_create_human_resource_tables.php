<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // 2. Designations
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // 3. Leave Types Master
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('name');
            $table->string('type')->default('Paid'); // Paid, Unpaid
            $table->integer('yearly_quota')->default(12); // days
            $table->string('accrual_frequency')->default('Yearly'); // Monthly, Yearly
            $table->boolean('carry_forward')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // 4. Staff Leave Applications & Approvals
        Schema::create('staff_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('days_count')->default(1);
            $table->text('reason');
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->text('admin_remarks')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
        });

        // 5. Salary Templates
        Schema::create('salary_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('name');
            $table->decimal('basic_salary', 10, 2)->default(30000.00);
            $table->decimal('hra', 10, 2)->default(6000.00);
            $table->decimal('da', 10, 2)->default(3000.00);
            $table->decimal('conveyance', 10, 2)->default(2000.00);
            $table->decimal('medical_allowance', 10, 2)->default(1500.00);
            $table->decimal('epf_deduction', 10, 2)->default(1800.00);
            $table->decimal('esi_deduction', 10, 2)->default(500.00);
            $table->decimal('professional_tax', 10, 2)->default(200.00);
            $table->decimal('net_salary', 10, 2)->default(40000.00);
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // 6. Staff Individual Salary Setup
        Schema::create('staff_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('salary_template_id')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(30000.00);
            $table->decimal('total_allowances', 10, 2)->default(12500.00);
            $table->decimal('total_deductions', 10, 2)->default(2500.00);
            $table->decimal('net_salary', 10, 2)->default(40000.00);
            $table->string('payment_method')->default('Bank Transfer'); // Bank Transfer, Cheque, Cash
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('salary_template_id')->references('id')->on('salary_templates')->onDelete('set null');
        });

        // 7. Monthly Payroll Runs
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('month_year'); // e.g. "November 2026"
            $table->integer('total_staff')->default(0);
            $table->decimal('gross_amount', 12, 2)->default(0.00);
            $table->decimal('total_deductions', 12, 2)->default(0.00);
            $table->decimal('net_total', 12, 2)->default(0.00);
            $table->string('status')->default('Generated'); // Generated, Disbursed
            $table->date('payment_date')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        // 8. Staff Monthly Payslips
        Schema::create('staff_payslips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('payroll_id');
            $table->unsignedBigInteger('staff_id');
            $table->decimal('basic_salary', 10, 2)->default(0.00);
            $table->decimal('allowances', 10, 2)->default(0.00);
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2)->default(0.00);
            $table->string('status')->default('Generated'); // Generated, Paid
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        });

        // 9. Staff Daily Attendance
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('staff_id');
            $table->date('date');
            $table->string('status')->default('P'); // P (Present), L (Late), A (Absent), H (Half Day)
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        });

        // 10. Staff Loans & Advances
        Schema::create('staff_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->unsignedBigInteger('staff_id');
            $table->decimal('loan_amount', 10, 2);
            $table->integer('term_months')->default(12);
            $table->decimal('monthly_emi', 10, 2);
            $table->decimal('amount_repaid', 10, 2)->default(0.00);
            $table->decimal('remaining_balance', 10, 2);
            $table->date('disbursed_date')->nullable();
            $table->string('status')->default('Pending'); // Pending, Approved, Disbursed, Repaid, Foreclosed
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_loans');
        Schema::dropIfExists('staff_attendances');
        Schema::dropIfExists('staff_payslips');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('staff_salaries');
        Schema::dropIfExists('salary_templates');
        Schema::dropIfExists('staff_leaves');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('designations');
        Schema::dropIfExists('departments');
    }
};

