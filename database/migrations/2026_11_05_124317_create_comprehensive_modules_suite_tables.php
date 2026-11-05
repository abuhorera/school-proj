<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Library Tables
        if (!Schema::hasTable('book_categories')) {
            Schema::create('book_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('library_books')) {
            Schema::create('library_books', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('book_no')->nullable();
                $table->string('isbn_no')->nullable();
                $table->string('author')->nullable();
                $table->string('publisher')->nullable();
                $table->foreignId('category_id')->nullable();
                $table->string('rack_no')->nullable();
                $table->integer('quantity')->default(1);
                $table->integer('available_quantity')->default(1);
                $table->decimal('price', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('book_issues')) {
            Schema::create('book_issues', function (Blueprint $table) {
                $table->id();
                $table->foreignId('book_id');
                $table->string('member_type')->default('Student'); // Student, Staff
                $table->unsignedBigInteger('member_id');
                $table->string('member_name')->nullable();
                $table->date('issue_date');
                $table->date('due_date');
                $table->date('return_date')->nullable();
                $table->decimal('fine_amount', 10, 2)->default(0.00);
                $table->string('status')->default('Issued'); // Issued, Returned, Overdue
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Inventory & Stock Tables
        if (!Schema::hasTable('inventory_categories')) {
            Schema::create('inventory_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('inventory_suppliers')) {
            Schema::create('inventory_suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('address')->nullable();
                $table->string('contact_person')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('inventory_items')) {
            Schema::create('inventory_items', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('category_id')->nullable();
                $table->string('unit')->default('pcs'); // pcs, kg, set, box
                $table->decimal('unit_price', 10, 2)->default(0.00);
                $table->integer('stock_quantity')->default(0);
                $table->integer('reorder_level')->default(5);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('inventory_sales')) {
            Schema::create('inventory_sales', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_no')->unique();
                $table->string('customer_name');
                $table->string('customer_type')->default('Student');
                $table->date('sale_date');
                $table->decimal('total_amount', 10, 2)->default(0.00);
                $table->string('payment_method')->default('Cash');
                $table->string('status')->default('Completed');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Transport Tables
        if (!Schema::hasTable('transport_vehicles')) {
            Schema::create('transport_vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('vehicle_no');
                $table->string('vehicle_model')->nullable();
                $table->integer('seating_capacity')->default(30);
                $table->string('driver_name')->nullable();
                $table->string('driver_phone')->nullable();
                $table->string('driver_license')->nullable();
                $table->string('status')->default('Active'); // Active, Maintenance
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('transport_routes')) {
            Schema::create('transport_routes', function (Blueprint $table) {
                $table->id();
                $table->string('route_title');
                $table->foreignId('vehicle_id')->nullable();
                $table->decimal('fare', 10, 2)->default(0.00);
                $table->string('start_point')->nullable();
                $table->string('end_point')->nullable();
                $table->text('stops_json')->nullable();
                $table->timestamps();
            });
        }

        // 4. Hostel Tables
        if (!Schema::hasTable('hostel_blocks')) {
            Schema::create('hostel_blocks', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('Boys'); // Boys, Girls, Mixed
                $table->string('warden_name')->nullable();
                $table->string('warden_phone')->nullable();
                $table->string('address')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hostel_rooms')) {
            Schema::create('hostel_rooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('block_id');
                $table->string('room_number');
                $table->string('room_type')->default('Standard');
                $table->integer('bed_count')->default(2);
                $table->decimal('cost_per_bed', 10, 2)->default(0.00);
                $table->string('status')->default('Available');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hostel_allocations')) {
            Schema::create('hostel_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->nullable();
                $table->string('student_name')->nullable();
                $table->foreignId('room_id');
                $table->integer('bed_no')->default(1);
                $table->date('allocation_date');
                $table->date('vacate_date')->nullable();
                $table->string('status')->default('Active');
                $table->timestamps();
            });
        }

        // 5. Certificates & ID Cards Templates
        if (!Schema::hasTable('certificate_templates')) {
            Schema::create('certificate_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('certificate_type')->default('Transfer Certificate');
                $table->string('layout')->default('Landscape');
                $table->text('header_text')->nullable();
                $table->text('body_content')->nullable();
                $table->text('footer_text')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('id_card_templates')) {
            Schema::create('id_card_templates', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('card_type')->default('Student'); // Student, Staff
                $table->string('layout')->default('Vertical');
                $table->string('bg_color')->default('#1e3c72');
                $table->text('fields_json')->nullable();
                $table->timestamps();
            });
        }

        // 6. CCTV Cameras
        if (!Schema::hasTable('cctv_cameras')) {
            Schema::create('cctv_cameras', function (Blueprint $table) {
                $table->id();
                $table->string('camera_name');
                $table->string('location');
                $table->string('ip_address')->nullable();
                $table->string('stream_url')->nullable();
                $table->string('status')->default('Online');
                $table->string('resolution')->default('1080p');
                $table->timestamps();
            });
        }

        // 7. Alumni Tables
        if (!Schema::hasTable('alumni_members')) {
            Schema::create('alumni_members', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('batch_year');
                $table->string('current_job')->nullable();
                $table->string('company')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('city')->nullable();
                $table->string('avatar')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('alumni_events')) {
            Schema::create('alumni_events', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->date('event_date');
                $table->string('location')->nullable();
                $table->text('description')->nullable();
                $table->integer('attendees_count')->default(0);
                $table->timestamps();
            });
        }

        // 8. Labs Tables
        if (!Schema::hasTable('lab_records')) {
            Schema::create('lab_records', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('lab_type')->default('Computer'); // Physics, Chemistry, Biology, Computer
                $table->string('incharge_name')->nullable();
                $table->integer('capacity')->default(30);
                $table->integer('equipment_count')->default(25);
                $table->string('status')->default('Operational');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lab_sessions')) {
            Schema::create('lab_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lab_id');
                $table->string('class_name');
                $table->string('subject_name');
                $table->string('topic');
                $table->date('session_date');
                $table->string('batch_name')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lab_incidents')) {
            Schema::create('lab_incidents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lab_id');
                $table->date('incident_date');
                $table->string('reported_by')->nullable();
                $table->string('severity')->default('Low'); // Low, Medium, High, Critical
                $table->text('description');
                $table->text('action_taken')->nullable();
                $table->string('status')->default('Resolved');
                $table->timestamps();
            });
        }

        // 9. Canteen & Campus Wallets
        if (!Schema::hasTable('canteen_products')) {
            Schema::create('canteen_products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category')->default('Snacks'); // Meals, Snacks, Beverages
                $table->decimal('price', 10, 2)->default(0.00);
                $table->decimal('cost_price', 10, 2)->default(0.00);
                $table->integer('stock_qty')->default(50);
                $table->boolean('is_available')->default(true);
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('canteen_wallets')) {
            Schema::create('canteen_wallets', function (Blueprint $table) {
                $table->id();
                $table->string('user_type')->default('Student'); // Student, Staff
                $table->unsignedBigInteger('user_id');
                $table->string('user_name');
                $table->decimal('balance', 10, 2)->default(0.00);
                $table->decimal('daily_limit', 10, 2)->default(200.00);
                $table->string('status')->default('Active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('canteen_transactions')) {
            Schema::create('canteen_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id');
                $table->string('type')->default('Debit'); // Credit, Debit
                $table->decimal('amount', 10, 2);
                $table->decimal('balance_after', 10, 2);
                $table->string('description')->nullable();
                $table->string('reference_no')->nullable();
                $table->timestamps();
            });
        }

        // 10. Online Courses LMS
        if (!Schema::hasTable('online_courses')) {
            Schema::create('online_courses', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->nullable();
                $table->string('category')->default('Academics');
                $table->string('instructor_name')->nullable();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->default(0.00);
                $table->integer('lessons_count')->default(5);
                $table->integer('duration_hours')->default(10);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        // 11. Asset Management
        if (!Schema::hasTable('asset_items')) {
            Schema::create('asset_items', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('asset_code')->unique();
                $table->string('category')->default('IT & Electronics'); // Furniture, IT & Electronics, Vehicles, Lab Equipment
                $table->date('purchase_date');
                $table->decimal('cost_price', 10, 2)->default(0.00);
                $table->decimal('current_value', 10, 2)->default(0.00);
                $table->string('location')->nullable();
                $table->string('condition')->default('Good'); // New, Good, Needs Repair, Scrap
                $table->string('assigned_to')->nullable();
                $table->timestamps();
            });
        }

        // 12. Live Classes Sessions
        if (!Schema::hasTable('live_class_sessions')) {
            Schema::create('live_class_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('platform')->default('Zoom'); // Zoom, Google Meet, BigBlueButton
                $table->string('meeting_id')->nullable();
                $table->string('join_url')->nullable();
                $table->string('host_name')->nullable();
                $table->string('class_name')->nullable();
                $table->string('subject_name')->nullable();
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->string('status')->default('Scheduled'); // Scheduled, Live, Finished
                $table->timestamps();
            });
        }

        // 13. Knowledge Base
        if (!Schema::hasTable('kb_categories')) {
            Schema::create('kb_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->default('fa-folder');
                $table->integer('order_index')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kb_articles')) {
            Schema::create('kb_articles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id');
                $table->string('title');
                $table->string('slug')->nullable();
                $table->text('content');
                $table->integer('views_count')->default(0);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kb_articles');
        Schema::dropIfExists('kb_categories');
        Schema::dropIfExists('live_class_sessions');
        Schema::dropIfExists('asset_items');
        Schema::dropIfExists('online_courses');
        Schema::dropIfExists('canteen_transactions');
        Schema::dropIfExists('canteen_wallets');
        Schema::dropIfExists('canteen_products');
        Schema::dropIfExists('lab_incidents');
        Schema::dropIfExists('lab_sessions');
        Schema::dropIfExists('lab_records');
        Schema::dropIfExists('alumni_events');
        Schema::dropIfExists('alumni_members');
        Schema::dropIfExists('cctv_cameras');
        Schema::dropIfExists('id_card_templates');
        Schema::dropIfExists('certificate_templates');
        Schema::dropIfExists('hostel_allocations');
        Schema::dropIfExists('hostel_rooms');
        Schema::dropIfExists('hostel_blocks');
        Schema::dropIfExists('transport_routes');
        Schema::dropIfExists('transport_vehicles');
        Schema::dropIfExists('inventory_sales');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_suppliers');
        Schema::dropIfExists('inventory_categories');
        Schema::dropIfExists('book_issues');
        Schema::dropIfExists('library_books');
        Schema::dropIfExists('book_categories');
    }
};
