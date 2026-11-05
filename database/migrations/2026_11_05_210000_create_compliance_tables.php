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
        Schema::create('school_compliance_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('udise_code')->nullable();
            $table->string('recognition_no')->nullable();
            $table->date('recognition_date')->nullable();
            $table->string('management_type')->nullable(); // Government, Private Unaided, etc.
            $table->string('school_category')->nullable(); // Primary, Secondary, Higher Secondary
            $table->string('board')->nullable(); // CBSE, ICSE, State Board, etc.
            $table->string('minority_group')->nullable();
            $table->integer('established_year')->nullable();
            $table->string('affiliation_no')->nullable();
            $table->date('valid_upto')->nullable();
            $table->decimal('campus_area_sqm', 10, 2)->nullable();
            $table->decimal('built_up_area_sqm', 10, 2)->nullable();
            $table->boolean('playground_available')->default(true);
            $table->date('fire_safety_valid_upto')->nullable();
            $table->date('building_safety_valid_upto')->nullable();
            $table->timestamps();
        });

        Schema::create('compliance_packs', function (Blueprint $table) {
            $table->id();
            $table->string('pack_key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_installed')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('compliance_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('owner_type')->default('school'); // school, staff, student
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('category'); // Fire Safety NOC, Building Safety Certificate, etc.
            $table->string('title');
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->default('valid'); // valid, expiring, expired
            $table->boolean('is_verified')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('compliance_validation_findings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('owner_type')->default('student'); // student, staff
            $table->unsignedBigInteger('owner_id');
            $table->string('severity')->default('error'); // critical, error, warning
            $table->string('check_key');
            $table->string('check_name');
            $table->text('issue_description');
            $table->string('status')->default('open'); // open, waived, resolved
            $table->string('waive_reason')->nullable();
            $table->date('waived_until')->nullable();
            $table->timestamps();
        });

        Schema::create('compliance_inspection_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->string('checklist_type')->default('cbse_renewal'); // cbse_renewal, annual_inspection
            $table->string('item_key');
            $table->string('item_title');
            $table->string('status')->default('Pending'); // Pending, Done, N/A
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('compliance_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->date('event_date');
            $table->string('event_type')->default('Statutory'); // Statutory, Document Expiry, Reporting Window
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_calendar_events');
        Schema::dropIfExists('compliance_inspection_items');
        Schema::dropIfExists('compliance_validation_findings');
        Schema::dropIfExists('compliance_documents');
        Schema::dropIfExists('compliance_packs');
        Schema::dropIfExists('school_compliance_profiles');
    }
};

