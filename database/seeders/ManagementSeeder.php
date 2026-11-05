<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagementSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        if (!$school) return;

        // 1. Income & Expense Heads
        $ih1 = DB::table('income_heads')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Academic Fees & Tuition',
            'description' => 'Direct student quarterly collections',
            'created_at' => now(), 'updated_at' => now()
        ]);
        $ih2 = DB::table('income_heads')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Transport Facility Charges',
            'description' => 'Bus and van commute fees',
            'created_at' => now(), 'updated_at' => now()
        ]);
        $ih3 = DB::table('income_heads')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Bookstore & Uniform Sales',
            'description' => 'Counter sales of textbooks and kits',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $eh1 = DB::table('expense_heads')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Staff Salaries & Wages',
            'description' => 'Monthly payroll of teaching and support staff',
            'created_at' => now(), 'updated_at' => now()
        ]);
        $eh2 = DB::table('expense_heads')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Electricity & Utilities',
            'description' => 'Power, water supply, and municipal bills',
            'created_at' => now(), 'updated_at' => now()
        ]);
        $eh3 = DB::table('expense_heads')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Laboratory Equipment & Consumables',
            'description' => 'Physics, chemistry, and biology supplies',
            'created_at' => now(), 'updated_at' => now()
        ]);

        // Incomes
        DB::table('incomes')->insert([
            [
                'school_id' => $school->id,
                'income_head_id' => $ih1,
                'name' => 'Quarter 1 Consolidated Tuition Receipts',
                'invoice_no' => 'INC-2026-001',
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'amount' => 185000.00,
                'payment_method' => 'Bank Transfer',
                'note' => 'Quarterly academic collection via portal',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'income_head_id' => $ih2,
                'name' => 'Transport Route 4 & 7 Route Passes',
                'invoice_no' => 'INC-2026-002',
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'amount' => 42000.00,
                'payment_method' => 'UPI',
                'note' => 'Bus routes term 1 collection',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // Bank Accounts
        DB::table('bank_accounts')->insert([
            [
                'school_id' => $school->id,
                'bank_name' => 'HDFC Bank',
                'account_name' => 'YUG INTERNATIONAL SCHOOL - OPERATION A/C',
                'account_number' => '50100234589123',
                'ifsc_code' => 'HDFC0001245',
                'branch' => 'Sector 18 Knowledge Park',
                'current_balance' => 2485000.00,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'bank_name' => 'State Bank of India',
                'account_name' => 'YUG INTERNATIONAL SCHOOL - FEE COLLECTION',
                'account_number' => '384591239845',
                'ifsc_code' => 'SBIN0004512',
                'branch' => 'Institutional Area',
                'current_balance' => 1892400.00,
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // 2. Admission Enquiries (Lead Pipeline)
        DB::table('admission_enquiries')->insert([
            [
                'school_id' => $school->id,
                'name' => 'Rajeev Malhotra (Parent of Shlok)',
                'phone' => '+91 98101 23456',
                'email' => 'rajeev.m@outlook.com',
                'class_applying_for' => 'Class 1',
                'source' => 'Website Inquiry',
                'status' => 'Visit Scheduled',
                'next_follow_up' => Carbon::tomorrow()->format('Y-m-d'),
                'remarks' => 'Parent interested in CBSE English medium curriculum and bus facility',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'name' => 'Pooja Aggarwal (Parent of Aarohi)',
                'phone' => '+91 98202 34567',
                'email' => 'pooja.agg@gmail.com',
                'class_applying_for' => 'Nursery',
                'source' => 'Walk-in',
                'status' => 'New',
                'next_follow_up' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'remarks' => 'Inquired regarding admission fees and teacher to student ratio',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'name' => 'Sunil Deshmukh (Parent of Siddharth)',
                'phone' => '+91 98303 45678',
                'email' => 'sunil.d@tcs.com',
                'class_applying_for' => 'Class 9',
                'source' => 'Referral',
                'status' => 'Contacted',
                'next_follow_up' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'remarks' => 'Transfer case from Mumbai. Requested science stream syllabus',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // Visitors
        DB::table('visitors')->insert([
            [
                'school_id' => $school->id,
                'name' => 'Anil Kapoor',
                'phone' => '+91 98404 56789',
                'purpose' => 'Annual Day Stage Audio Setup Inspection',
                'person_to_meet' => 'Dr. Arvind Mehta (Principal)',
                'in_time' => Carbon::now()->subHours(2),
                'out_time' => Carbon::now()->subMinutes(30),
                'id_card_number' => 'AADHAAR-8902',
                'status' => 'checked_out',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'name' => 'Suresh Bhatt (Book Publisher)',
                'phone' => '+91 98505 67890',
                'purpose' => 'Library Catalog & Textbook Delivery',
                'person_to_meet' => 'Mrs. Meenakshi Sundaram (Librarian)',
                'in_time' => Carbon::now()->subMinutes(45),
                'out_time' => null,
                'id_card_number' => 'DL-9012',
                'status' => 'inside',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // Complaints
        DB::table('complaints')->insert([
            [
                'school_id' => $school->id,
                'complaint_by' => 'Mr. Manoj Verma (Parent)',
                'complaint_type' => 'Transport',
                'phone' => '+91 99100 11003',
                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'description' => 'Route 3 morning bus arrived 15 minutes late at Orchid Enclave stop.',
                'action_taken' => 'Transport coordinator instructed driver to adjust departure timing for traffic.',
                'status' => 'Resolved',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'complaint_by' => 'Grade 8 Parents Association',
                'complaint_type' => 'Infrastructure',
                'phone' => '+91 99100 11005',
                'date' => Carbon::now()->format('Y-m-d'),
                'description' => 'Projector smartboard in Room 204 has display flickering.',
                'action_taken' => 'Assigned to IT hardware technician for cable check.',
                'status' => 'In Progress',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // 3. Buildings & Classrooms
        $bMain = DB::table('buildings')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Aryabhata Academic Block (Main)',
            'code' => 'BLK-A',
            'floors' => 4,
            'created_at' => now(), 'updated_at' => now()
        ]);

        $bSci = DB::table('buildings')->insertGetId([
            'school_id' => $school->id,
            'name' => 'C.V. Raman Science & Tech Wing',
            'code' => 'BLK-B',
            'floors' => 3,
            'created_at' => now(), 'updated_at' => now()
        ]);

        $bJun = DB::table('buildings')->insertGetId([
            'school_id' => $school->id,
            'name' => 'Tagore Primary & Kindergarten Wing',
            'code' => 'BLK-C',
            'floors' => 2,
            'created_at' => now(), 'updated_at' => now()
        ]);

        DB::table('classroom_rooms')->insert([
            ['school_id' => $school->id, 'building_id' => $bMain, 'room_number' => 'Room 101 (Class 10 A)', 'room_type' => 'Classroom', 'capacity' => 45, 'status' => 'Occupied', 'created_at' => now(), 'updated_at' => now()],
            ['school_id' => $school->id, 'building_id' => $bMain, 'room_number' => 'Room 102 (Class 10 B)', 'room_type' => 'Classroom', 'capacity' => 45, 'status' => 'Occupied', 'created_at' => now(), 'updated_at' => now()],
            ['school_id' => $school->id, 'building_id' => $bMain, 'room_number' => 'Room 201 (Class 9 A)', 'room_type' => 'Classroom', 'capacity' => 45, 'status' => 'Occupied', 'created_at' => now(), 'updated_at' => now()],
            ['school_id' => $school->id, 'building_id' => $bMain, 'room_number' => 'Room 202 (Class 9 B)', 'room_type' => 'Classroom', 'capacity' => 45, 'status' => 'Available', 'created_at' => now(), 'updated_at' => now()],
            ['school_id' => $school->id, 'building_id' => $bSci, 'room_number' => 'Senior Physics Laboratory', 'room_type' => 'Science Lab', 'capacity' => 40, 'status' => 'Occupied', 'created_at' => now(), 'updated_at' => now()],
            ['school_id' => $school->id, 'building_id' => $bSci, 'room_number' => 'Chemistry & Biology Lab', 'room_type' => 'Science Lab', 'capacity' => 40, 'status' => 'Available', 'created_at' => now(), 'updated_at' => now()],
            ['school_id' => $school->id, 'building_id' => $bSci, 'room_number' => 'Advanced AI & Computer Center', 'room_type' => 'Computer Lab', 'capacity' => 50, 'status' => 'Occupied', 'created_at' => now(), 'updated_at' => now()],
            ['school_id' => $school->id, 'building_id' => $bMain, 'room_number' => 'Main Academic Auditorium', 'room_type' => 'Hall', 'capacity' => 600, 'status' => 'Available', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. PTM Meetings
        $c10 = SchoolClass::where('name', 'Class 10')->first();
        DB::table('ptm_meetings')->insert([
            [
                'school_id' => $school->id,
                'class_id' => $c10 ? $c10->id : null,
                'title' => 'Term 1 Scholastic Assessment PTM',
                'meeting_date' => Carbon::now()->addDays(9)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'mode' => 'In-Person',
                'agenda' => 'Individual discussion on mid-term marksheet, board exam readiness, and career counseling',
                'status' => 'Scheduled',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'class_id' => null,
                'title' => 'Kindergarten & Primary Progress Review',
                'meeting_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'mode' => 'In-Person',
                'agenda' => 'Holistic development review, motor skills assessment, and co-curricular feedback',
                'status' => 'Scheduled',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // 5. Lesson Plans
        $mathSub = Subject::where('name', 'like', '%Math%')->first();
        $sciSub = Subject::where('name', 'like', '%Sci%')->first();
        $teacher = Staff::where('designation', 'like', '%Teacher%')->first();

        if ($c10 && $mathSub) {
            DB::table('lesson_plans')->insert([
                [
                    'school_id' => $school->id,
                    'class_id' => $c10->id,
                    'subject_id' => $mathSub->id,
                    'teacher_id' => $teacher ? $teacher->id : null,
                    'week_number' => 8,
                    'topic' => 'Quadratic Equations & Polynomials',
                    'subtopics' => 'Nature of roots, quadratic formula derivations, word problems',
                    'teaching_method' => 'Smartboard derivation and problem practice sets',
                    'status' => 'Approved',
                    'created_at' => now(), 'updated_at' => now()
                ],
                [
                    'school_id' => $school->id,
                    'class_id' => $c10->id,
                    'subject_id' => $sciSub ? $sciSub->id : $mathSub->id,
                    'teacher_id' => $teacher ? $teacher->id : null,
                    'week_number' => 8,
                    'topic' => 'Light: Reflection and Refraction',
                    'subtopics' => 'Spherical mirrors, ray diagrams, Snell\'s law of refraction',
                    'teaching_method' => 'Laboratory optics bench demonstration and numericals',
                    'status' => 'Approved',
                    'created_at' => now(), 'updated_at' => now()
                ]
            ]);
        }

        // 6. Compliance Items
        DB::table('compliance_items')->insert([
            [
                'school_id' => $school->id,
                'title' => 'CBSE Affiliation Extension Certificate (Senior Secondary)',
                'category' => 'CBSE Affiliation',
                'issuing_authority' => 'Central Board of Secondary Education (New Delhi)',
                'issue_date' => '2024-04-01',
                'expiry_date' => '2029-03-31',
                'status' => 'Compliant',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'title' => 'Fire Safety & Firefighting NOC Certificate',
                'category' => 'Fire Safety',
                'issuing_authority' => 'Delhi Fire Service Department',
                'issue_date' => '2026-01-15',
                'expiry_date' => '2027-01-14',
                'status' => 'Compliant',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'title' => 'Safe Drinking Water & Sanitary Condition Certificate',
                'category' => 'Health & Sanitation',
                'issuing_authority' => 'Municipal Health & Hygiene Laboratory',
                'issue_date' => '2026-05-10',
                'expiry_date' => '2027-05-09',
                'status' => 'Compliant',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'school_id' => $school->id,
                'title' => 'Building Structural Stability Fitness Certificate',
                'category' => 'Building Fitness',
                'issuing_authority' => 'Chartered Structural Engineers Board',
                'issue_date' => '2025-08-20',
                'expiry_date' => '2028-08-19',
                'status' => 'Compliant',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);
    }
}

