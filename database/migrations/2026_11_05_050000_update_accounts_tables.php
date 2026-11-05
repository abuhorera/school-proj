<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update expenses table if missing columns
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'name')) {
                $table->string('name')->nullable()->after('school_id');
            }
            if (!Schema::hasColumn('expenses', 'expense_head_id')) {
                $table->foreignId('expense_head_id')->nullable()->after('name')->constrained('expense_heads')->onDelete('set null');
            }
            if (!Schema::hasColumn('expenses', 'school_bank_account_id')) {
                $table->foreignId('school_bank_account_id')->nullable()->after('expense_head_id')->constrained('bank_accounts')->onDelete('set null');
            }
            if (!Schema::hasColumn('expenses', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('expenses', 'description')) {
                $table->text('description')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('expenses', 'attachment')) {
                $table->string('attachment')->nullable()->after('description');
            }
        });

        // Update incomes table if missing columns
        Schema::table('incomes', function (Blueprint $table) {
            if (!Schema::hasColumn('incomes', 'school_bank_account_id')) {
                $table->foreignId('school_bank_account_id')->nullable()->after('income_head_id')->constrained('bank_accounts')->onDelete('set null');
            }
            if (!Schema::hasColumn('incomes', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('incomes', 'description')) {
                $table->text('description')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('incomes', 'attachment')) {
                $table->string('attachment')->nullable()->after('description');
            }
        });

        // Update bank_accounts table if missing columns
        Schema::table('bank_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_accounts', 'account_type')) {
                $table->string('account_type')->default('Current')->after('account_name');
            }
            if (!Schema::hasColumn('bank_accounts', 'opening_balance')) {
                $table->decimal('opening_balance', 14, 2)->default(0)->after('current_balance');
            }
        });
    }

    public function down()
    {
        // Down migration
    }
};

