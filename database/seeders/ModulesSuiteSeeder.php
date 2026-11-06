<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSuiteSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Library Seeding
        $catSciId = DB::table('book_categories')->insertGetId([
            'name' => 'Science & Mathematics',
            'code' => 'SCI-MATH',
            'description' => 'Textbooks and reference material for STEM',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $catLitId = DB::table('book_categories')->insertGetId([
            'name' => 'Literature & Languages',
            'code' => 'LIT-LANG',
            'description' => 'English, Hindi, and Regional literature classics',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $book1 = DB::table('library_books')->insertGetId([
            'title' => 'Concepts of Physics (Vol 1 & 2)',
            'book_no' => 'BK-1001',
            'isbn_no' => '978-8177091878',
            'author' => 'Dr. H.C. Verma',
            'publisher' => 'Bharati Bhawan',
            'category_id' => $catSciId,
            'rack_no' => 'Rack A-2',
            'quantity' => 10,
            'available_quantity' => 8,
            'price' => 450.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $book2 = DB::table('library_books')->insertGetId([
            'title' => 'To Kill a Mockingbird',
            'book_no' => 'BK-1002',
            'isbn_no' => '978-0446310789',
            'author' => 'Harper Lee',
            'publisher' => 'Grand Central Publishing',
            'category_id' => $catLitId,
            'rack_no' => 'Rack L-4',
            'quantity' => 5,
            'available_quantity' => 4,
            'price' => 320.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('book_issues')->insert([
            [
                'book_id' => $book1,
                'member_type' => 'Student',
                'member_id' => 1,
                'member_name' => 'Aarav Sharma',
                'issue_date' => now()->subDays(5)->toDateString(),
                'due_date' => now()->addDays(9)->toDateString(),
                'return_date' => null,
                'status' => 'Issued',
                'notes' => 'Issued for board exam preparation',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'book_id' => $book2,
                'member_type' => 'Staff',
                'member_id' => 1,
                'member_name' => 'Mrs. Sunita Rao',
                'issue_date' => now()->subDays(12)->toDateString(),
                'due_date' => now()->subDays(2)->toDateString(),
                'return_date' => now()->toDateString(),
                'status' => 'Returned',
                'notes' => 'Returned on time without damage',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 2. Inventory Seeding
        $invCat1 = DB::table('inventory_categories')->insertGetId([
            'name' => 'School Uniforms & Kits',
            'description' => 'Blazers, shirts, skirts, ties, sportswear',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $invCat2 = DB::table('inventory_categories')->insertGetId([
            'name' => 'Stationery & Notebooks',
            'description' => 'Graph books, ruled notebooks, school diaries',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $supp1 = DB::table('inventory_suppliers')->insertGetId([
            'name' => 'Apex Educational Apparels',
            'phone' => '+91 98765 43210',
            'email' => 'sales@apexapparels.com',
            'address' => 'Industrial Area Phase 2, New Delhi',
            'contact_person' => 'Rajesh Gupta',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('inventory_items')->insert([
            [
                'name' => 'School Blazer (Navy Blue, Size 34)',
                'category_id' => $invCat1,
                'unit' => 'pcs',
                'unit_price' => 1250.00,
                'stock_quantity' => 45,
                'reorder_level' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'School Diary & Planner 2026-27',
                'category_id' => $invCat2,
                'unit' => 'pcs',
                'unit_price' => 150.00,
                'stock_quantity' => 120,
                'reorder_level' => 25,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 3. Transport Seeding
        $vehId1 = DB::table('transport_vehicles')->insertGetId([
            'vehicle_no' => 'DL-01-AB-1234',
            'vehicle_model' => 'Tata Starbus 40-Seater',
            'seating_capacity' => 40,
            'driver_name' => 'Ramesh Kumar',
            'driver_phone' => '+91 98111 22334',
            'driver_license' => 'DL-COMM-2019-9876',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $vehId2 = DB::table('transport_vehicles')->insertGetId([
            'vehicle_no' => 'DL-01-CD-5678',
            'vehicle_model' => 'Eicher Skyline Pro 32-Seater',
            'seating_capacity' => 32,
            'driver_name' => 'Mohinder Singh',
            'driver_phone' => '+91 98222 33445',
            'driver_license' => 'DL-COMM-2021-4321',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('transport_routes')->insert([
            [
                'route_title' => 'Route 1: City Center to Campus via Main Market',
                'vehicle_id' => $vehId1,
                'fare' => 1800.00,
                'start_point' => 'Clock Tower Circle',
                'end_point' => 'School Main Gate',
                'stops_json' => json_encode(['Clock Tower', 'Green Park Metro', 'Civic Hospital', 'Civil Lines', 'Campus Gate']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'route_title' => 'Route 2: South Extension to Campus via Ring Road',
                'vehicle_id' => $vehId2,
                'fare' => 2200.00,
                'start_point' => 'South Extension Market',
                'end_point' => 'School Main Gate',
                'stops_json' => json_encode(['South Ext Part 1', 'Defence Colony Flyover', 'Lajpat Nagar', 'Campus Gate']),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 4. Hostel Seeding
        $hostelBlock = DB::table('hostel_blocks')->insertGetId([
            'name' => 'Tagore Boys Senior Hostel',
            'type' => 'Boys',
            'warden_name' => 'Dr. K.P. Verma',
            'warden_phone' => '+91 97111 44556',
            'address' => 'North Residential Enclave, Campus Wing C',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $hostelId = DB::table('hostels')->value('id') ?? 1;
        $roomTypeId = DB::table('hostel_room_types')->value('id') ?? 1;

        $hostelRoom = DB::table('hostel_rooms')->insertGetId([
            'hostel_id' => $hostelId,
            'block_id' => $hostelBlock,
            'room_type_id' => $roomTypeId,
            'room_number' => 'TB-201',
            'capacity' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('hostel_allocations')->insert([
            'hostel_room_id' => $hostelRoom,
            'room_id' => $hostelRoom,
            'student_id' => 1,
            'allocated_at' => now(),
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5. CCTV Cameras Seeding
        DB::table('cctv_cameras')->insert([
            ['camera_name' => 'CAM-01 (Main Entry Gate)', 'location' => 'Main Gate Barrier', 'ip_address' => '192.168.1.101', 'status' => 'Online', 'resolution' => '4K UltraHD', 'created_at' => now(), 'updated_at' => now()],
            ['camera_name' => 'CAM-02 (Central Corridor)', 'location' => 'Academic Block 1st Floor', 'ip_address' => '192.168.1.102', 'status' => 'Online', 'resolution' => '1080p Full HD', 'created_at' => now(), 'updated_at' => now()],
            ['camera_name' => 'CAM-03 (Senior Science Lab)', 'location' => 'Science Block Room 104', 'ip_address' => '192.168.1.103', 'status' => 'Online', 'resolution' => '1080p Full HD', 'created_at' => now(), 'updated_at' => now()],
            ['camera_name' => 'CAM-04 (Sports Ground & Pavilion)', 'location' => 'Athletic Track East Stand', 'ip_address' => '192.168.1.104', 'status' => 'Online', 'resolution' => '4K PTZ Camera', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // 6. Alumni Seeding
        DB::table('alumni_members')->insert([
            ['name' => 'Priya Nair', 'batch_year' => '2019', 'current_job' => 'Senior Software Engineer', 'company' => 'Google Bangalore', 'email' => 'priya.nair@alumni.org', 'phone' => '+91 99887 66554', 'city' => 'Bengaluru', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rohan Sen', 'batch_year' => '2018', 'current_job' => 'Data Scientist', 'company' => 'Microsoft AI Lab', 'email' => 'rohan.sen@alumni.org', 'phone' => '+91 98776 55443', 'city' => 'Hyderabad', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ananya Bose', 'batch_year' => '2021', 'current_job' => 'Medical Researcher', 'company' => 'AIIMS New Delhi', 'email' => 'ananya.bose@alumni.org', 'phone' => '+91 97665 44332', 'city' => 'New Delhi', 'created_at' => now(), 'updated_at' => now()]
        ]);

        DB::table('alumni_events')->insert([
            ['title' => 'Decennial Grand Reunion 2026', 'event_date' => now()->addDays(45)->toDateString(), 'location' => 'School Main Auditorium', 'description' => 'Annual gathering for alumni batches of 2015-2025 with keynote and awards.', 'attendees_count' => 180, 'created_at' => now(), 'updated_at' => now()]
        ]);

        // 7. Labs Seeding
        $lab1 = DB::table('lab_records')->insertGetId([
            'name' => 'Advanced Robotics & AI Studio',
            'lab_type' => 'Computer',
            'incharge_name' => 'Er. Vikram Mehta',
            'capacity' => 35,
            'equipment_count' => 32,
            'status' => 'Operational',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $lab2 = DB::table('lab_records')->insertGetId([
            'name' => 'Chemistry & Material Science Lab',
            'lab_type' => 'Chemistry',
            'incharge_name' => 'Dr. Meenakshi Soni',
            'capacity' => 40,
            'equipment_count' => 50,
            'status' => 'Operational',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('lab_sessions')->insert([
            ['lab_id' => $lab1, 'class_name' => 'Class 10-A', 'subject_name' => 'Robotics & Coding', 'topic' => 'Arduino Sensor Interfacing & PWM Motors', 'session_date' => now()->toDateString(), 'batch_name' => 'Batch 1 (Roll 1-20)', 'created_at' => now(), 'updated_at' => now()],
            ['lab_id' => $lab2, 'class_name' => 'Class 12-Science', 'subject_name' => 'Chemistry Practical', 'topic' => 'Titration & Salt Analysis (Oxalic Acid vs KMnO4)', 'session_date' => now()->subDay()->toDateString(), 'batch_name' => 'Batch A', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // 8. Canteen Products & Wallets
        DB::table('canteen_products')->insert([
            ['name' => 'Grilled Cheese Vegetable Sandwich', 'category' => 'Snacks', 'price' => 45.00, 'cost_price' => 25.00, 'stock_qty' => 50, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fresh Pressed Orange Juice (300ml)', 'category' => 'Beverages', 'price' => 40.00, 'cost_price' => 22.00, 'stock_qty' => 35, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wholesome Thali (Roti, Dal, Rice, Sabzi)', 'category' => 'Meals', 'price' => 85.00, 'cost_price' => 50.00, 'stock_qty' => 60, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()]
        ]);

        $walletId = DB::table('canteen_wallets')->insertGetId([
            'user_type' => 'Student',
            'user_id' => 1,
            'user_name' => 'Aarav Sharma (10-A)',
            'balance' => 650.00,
            'daily_limit' => 200.00,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('canteen_transactions')->insert([
            ['wallet_id' => $walletId, 'type' => 'Credit', 'amount' => 500.00, 'balance_after' => 735.00, 'description' => 'Online Parent UPI Wallet Top-up', 'reference_no' => 'TXN-982347', 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)],
            ['wallet_id' => $walletId, 'type' => 'Debit', 'amount' => 85.00, 'balance_after' => 650.00, 'description' => 'POS Purchase: 1x Wholesome Thali', 'reference_no' => 'POS-INV-1092', 'created_at' => now()->subHours(4), 'updated_at' => now()->subHours(4)]
        ]);

        // 9. Online Courses LMS
        DB::table('online_courses')->insert([
            ['title' => 'Python Programming & Problem Solving for High School', 'slug' => 'python-programming-highschool', 'category' => 'Computer Science', 'instructor_name' => 'Er. Vikram Mehta', 'description' => 'Learn programming fundamentals, loops, functions, lists, and build small games.', 'price' => 0.00, 'lessons_count' => 12, 'duration_hours' => 18, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'CBSE Class 10 Board Mathematics Crash Course', 'slug' => 'cbse-10-maths-crash-course', 'category' => 'Mathematics', 'instructor_name' => 'Mrs. Sunita Rao', 'description' => 'Comprehensive revision of Real Numbers, Trigonometry, and Surface Areas.', 'price' => 499.00, 'lessons_count' => 15, 'duration_hours' => 24, 'is_published' => true, 'created_at' => now(), 'updated_at' => now()]
        ]);

        // 10. Assets Seeding
        DB::table('asset_items')->insert([
            ['name' => 'Interactive Digital Smartboard 75-inch 4K', 'asset_code' => 'AST-IT-001', 'category' => 'IT & Electronics', 'purchase_date' => now()->subMonths(6)->toDateString(), 'cost_price' => 145000.00, 'current_value' => 125000.00, 'location' => 'Room 101 - Multimedia Classroom', 'condition' => 'Good', 'assigned_to' => 'Academic Dept', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'High-Performance Physics Spectrometer Kit', 'asset_code' => 'AST-LAB-042', 'category' => 'Lab Equipment', 'purchase_date' => now()->subMonths(14)->toDateString(), 'cost_price' => 38000.00, 'current_value' => 32000.00, 'location' => 'Science Block Room 102', 'condition' => 'Good', 'assigned_to' => 'Dr. H. Verma', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // 11. Live Classes Seeding
        DB::table('live_class_sessions')->insert([
            ['title' => 'Physics Doubt Clearing Session: Optics & Reflection', 'platform' => 'Zoom', 'meeting_id' => '892 4819 1928', 'join_url' => 'https://zoom.us/j/89248191928', 'host_name' => 'Dr. H. Verma', 'class_name' => 'Class 10-A', 'subject_name' => 'Physics', 'start_time' => now()->addHours(2), 'end_time' => now()->addHours(3), 'status' => 'Scheduled', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'English Debate & Speech Workshop', 'platform' => 'Google Meet', 'meeting_id' => 'abc-defg-hij', 'join_url' => 'https://meet.google.com/abc-defg-hij', 'host_name' => 'Mrs. Ananya Sen', 'class_name' => 'Class 9-B', 'subject_name' => 'English', 'start_time' => now()->addDay()->setHour(11)->setMinute(0), 'end_time' => now()->addDay()->setHour(12)->setMinute(30), 'status' => 'Scheduled', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // 12. Knowledge Base Seeding
        $kbCat = DB::table('kb_categories')->insertGetId([
            'name' => 'Admissions & Fee Payments',
            'icon' => 'fa-credit-card',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('kb_articles')->insert([
            [
                'category_id' => $kbCat,
                'title' => 'How to Pay Term Dues via Online Parent Portal (UPI/Cards)',
                'slug' => 'how-to-pay-term-dues-online',
                'content' => 'Parents can navigate to the Fees tab, click Pay Now, select UPI or NetBanking, and immediately download the GST-compliant digital receipt.',
                'views_count' => 142,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 13. Certificate & ID Card Templates
        DB::table('certificate_templates')->insert([
            ['name' => 'Official School Transfer Certificate (TC)', 'certificate_type' => 'Transfer Certificate', 'layout' => 'Portrait', 'header_text' => 'DELHI PUBLIC ACADEMY - AFFILIATION NO. 213009', 'body_content' => 'This is to certify that {student_name}, son/daughter of {father_name}, was a student of Class {class}.', 'footer_text' => 'Verified by Principal & Examination Controller', 'created_at' => now(), 'updated_at' => now()]
        ]);

        DB::table('id_card_templates')->insert([
            ['title' => 'Standard Student Smart RFID Card 2026-27', 'card_type' => 'Student', 'layout' => 'Vertical', 'bg_color' => '#1e3c72', 'fields_json' => json_encode(['Photo', 'Student Name', 'Admission No', 'Class & Section', 'Blood Group', 'Emergency Contact', 'Barcode']), 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
