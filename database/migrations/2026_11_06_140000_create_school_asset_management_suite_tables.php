<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Asset Categories
        if (!Schema::hasTable('asset_categories')) {
            Schema::create('asset_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('default_depreciation_method')->default('Straight Line');
                $table->integer('default_useful_life_months')->nullable();
                $table->decimal('default_salvage_value_pct', 5, 2)->default(0);
                $table->decimal('default_depreciation_rate', 5, 2)->default(0);
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. School Assets
        if (!Schema::hasTable('school_assets')) {
            Schema::create('school_assets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('asset_tag')->unique();
                $table->unsignedBigInteger('asset_category_id')->nullable();
                $table->string('brand')->nullable();
                $table->string('model_number')->nullable();
                $table->string('serial_number')->nullable();
                $table->integer('quantity')->default(1);
                $table->text('description')->nullable();
                $table->string('status')->default('in_store'); // in_store, in_use, under_maintenance, retired, disposed, lost
                $table->string('condition')->default('Good'); // New, Good, Fair, Poor, Damaged
                $table->string('location')->nullable();
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->string('supplier_name')->nullable();
                $table->string('funding_source')->nullable();
                $table->date('purchase_date')->nullable();
                $table->decimal('purchase_cost', 12, 2)->default(0);
                $table->string('invoice_number')->nullable();
                $table->string('depreciation_method')->default('Straight Line');
                $table->date('depreciation_start_date')->nullable();
                $table->integer('useful_life_months')->nullable();
                $table->decimal('salvage_value', 12, 2)->default(0);
                $table->decimal('depreciation_rate', 5, 2)->default(0);
                $table->decimal('opening_book_value', 12, 2)->default(0);
                $table->decimal('current_book_value', 12, 2)->default(0);
                $table->string('warranty_provider')->nullable();
                $table->date('warranty_expiry_date')->nullable();
                $table->string('insurance_provider')->nullable();
                $table->string('insurance_policy_number')->nullable();
                $table->date('insurance_expiry_date')->nullable();
                $table->string('image_path')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('asset_category_id')->references('id')->on('asset_categories')->onDelete('set null');
            });
        }

        // 3. Asset Assignments / Allocations
        if (!Schema::hasTable('asset_assignments')) {
            Schema::create('asset_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id');
                $table->string('assignable_type')->default('Staff'); // Staff, Student, Class / Room, Department
                $table->unsignedBigInteger('assignable_id')->nullable();
                $table->string('holder_name');
                $table->date('assigned_date');
                $table->date('expected_return_date')->nullable();
                $table->date('returned_date')->nullable();
                $table->string('condition_on_assign')->default('Good');
                $table->string('condition_on_return')->nullable();
                $table->string('status')->default('active'); // active, returned, transferred, lost
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('asset_id')->references('id')->on('school_assets')->onDelete('cascade');
            });
        }

        // 4. Asset Depreciations Ledger
        if (!Schema::hasTable('asset_depreciations')) {
            Schema::create('asset_depreciations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id');
                $table->string('period_name'); // e.g. Oct 2026
                $table->string('fiscal_year'); // e.g. 2026-2027
                $table->date('depreciation_date');
                $table->string('method');
                $table->decimal('opening_value', 12, 2);
                $table->decimal('depreciation_amount', 12, 2);
                $table->decimal('accumulated_depreciation', 12, 2);
                $table->decimal('closing_value', 12, 2);
                $table->string('posted_by')->default('school admin');
                $table->timestamps();

                $table->foreign('asset_id')->references('id')->on('school_assets')->onDelete('cascade');
            });
        }

        // 5. Maintenance Schedules
        if (!Schema::hasTable('asset_maintenance_schedules')) {
            Schema::create('asset_maintenance_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id');
                $table->string('title');
                $table->string('maintenance_type')->default('Routine Service');
                $table->string('frequency')->default('Monthly');
                $table->integer('interval_days')->default(30);
                $table->date('next_due_date');
                $table->string('assigned_to')->nullable();
                $table->unsignedBigInteger('vendor_id')->nullable();
                $table->string('vendor_name')->nullable();
                $table->decimal('estimated_cost', 10, 2)->default(0);
                $table->integer('reminder_days_before')->default(7);
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('asset_id')->references('id')->on('school_assets')->onDelete('cascade');
            });
        }

        // 6. Maintenance Logs
        if (!Schema::hasTable('asset_maintenance_logs')) {
            Schema::create('asset_maintenance_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id');
                $table->unsignedBigInteger('schedule_id')->nullable();
                $table->string('maintenance_type');
                $table->string('status')->default('reported'); // reported, in_progress, completed, cancelled
                $table->text('problem_description')->nullable();
                $table->date('reported_date');
                $table->date('completed_date')->nullable();
                $table->decimal('cost', 10, 2)->default(0);
                $table->string('performed_by')->nullable();
                $table->string('vendor_name')->nullable();
                $table->text('action_taken')->nullable();
                $table->string('external_provider')->nullable();
                $table->string('document_path')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('asset_id')->references('id')->on('school_assets')->onDelete('cascade');
            });
        }

        // 7. Asset Disposals
        if (!Schema::hasTable('asset_disposals')) {
            Schema::create('asset_disposals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id');
                $table->string('disposal_method')->default('Sold'); // Sold, Scrapped, Donated, Written Off, Lost
                $table->date('disposal_date');
                $table->decimal('realised_value', 12, 2)->default(0);
                $table->decimal('book_value_at_disposal', 12, 2)->default(0);
                $table->decimal('gain_loss', 12, 2)->default(0);
                $table->string('recipient')->nullable();
                $table->text('reason');
                $table->string('status')->default('Completed'); // Pending, Approved, Completed, Rejected
                $table->string('requested_by')->default('school admin');
                $table->string('document_path')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('asset_id')->references('id')->on('school_assets')->onDelete('cascade');
            });
        }

        // 8. Physical Verification Audits
        if (!Schema::hasTable('asset_audits')) {
            Schema::create('asset_audits', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('scope')->default('All'); // All, Location · ..., Category · ...
                $table->integer('total_assets')->default(0);
                $table->integer('verified_count')->default(0);
                $table->integer('present_count')->default(0);
                $table->integer('missing_count')->default(0);
                $table->integer('damaged_count')->default(0);
                $table->integer('moved_count')->default(0);
                $table->string('status')->default('Open'); // Open, Completed
                $table->string('conducted_by')->default('school admin');
                $table->dateTime('started_at');
                $table->dateTime('completed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 9. Audit Items
        if (!Schema::hasTable('asset_audit_items')) {
            Schema::create('asset_audit_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_audit_id');
                $table->unsignedBigInteger('asset_id');
                $table->string('expected_location')->nullable();
                $table->string('expected_holder')->nullable();
                $table->string('result')->default('Pending'); // Pending, Present, Missing, Damaged, Moved
                $table->string('found_location')->nullable();
                $table->text('remarks')->nullable();
                $table->string('verified_by')->nullable();
                $table->dateTime('verified_at')->nullable();
                $table->timestamps();

                $table->foreign('asset_audit_id')->references('id')->on('asset_audits')->onDelete('cascade');
                $table->foreign('asset_id')->references('id')->on('school_assets')->onDelete('cascade');
            });
        }

        // 10. Lifecycle Movements / History
        if (!Schema::hasTable('asset_movements')) {
            Schema::create('asset_movements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id');
                $table->string('event_type'); // Registered, Allocated, Returned, Transferred, Maintained, Disposed, Audited, Depreciated
                $table->date('event_date');
                $table->text('details');
                $table->string('actor')->default('school admin');
                $table->timestamps();

                $table->foreign('asset_id')->references('id')->on('school_assets')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_movements');
        Schema::dropIfExists('asset_audit_items');
        Schema::dropIfExists('asset_audits');
        Schema::dropIfExists('asset_disposals');
        Schema::dropIfExists('asset_maintenance_logs');
        Schema::dropIfExists('asset_maintenance_schedules');
        Schema::dropIfExists('asset_depreciations');
        Schema::dropIfExists('asset_assignments');
        Schema::dropIfExists('school_assets');
        Schema::dropIfExists('asset_categories');
    }
};

