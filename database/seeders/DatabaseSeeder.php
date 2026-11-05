<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\Branch;
use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\StudentEnrollment;
use App\Models\FeeGroup;
use App\Models\FeeType;
use App\Models\FeeMaster;
use App\Models\StudentFeeAllocation;
use App\Models\FeePayment;
use App\Models\Expense;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\ExamMark;
use App\Models\Notice;
use App\Models\Event;
use App\Models\GatePass;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create School
        $school = School::create([
            'name' => 'YUG INTERNATIONAL SCHOOL',
            'code' => 'YIS-2026',
            'email' => 'info@yugschool.com',
            'phone' => '+91 98765 43210',
            'address' => 'Plot 42, Institutional Area, Knowledge Park',
            'currency' => '₹',
            'status' => 'active',
        ]);

        // 2. Branches
        $branchMain = Branch::create([
            'school_id' => $school->id,
            'name' => 'Main Campus',
            'code' => 'YIS-MAIN',
            'city' => 'New Delhi',
            'phone' => '+91 98765 43210',
        ]);

        Branch::create([
            'school_id' => $school->id,
            'name' => 'North City Branch',
            'code' => 'YIS-NORTH',
            'city' => 'North Delhi',
            'phone' => '+91 98765 43211',
        ]);

        // 3. Academic Session
        $session = AcademicSession::create([
            'school_id' => $school->id,
            'name' => '2026-2027',
            'start_date' => '2026-04-01',
            'end_date' => '2027-03-31',
            'is_current' => true,
        ]);

        // 4. Classes & Sections
        $classList = [
            ['name' => 'Nursery', 'level' => 0],
            ['name' => 'LKG', 'level' => 0],
            ['name' => 'UKG', 'level' => 0],
            ['name' => 'Class 1', 'level' => 1],
            ['name' => 'Class 2', 'level' => 2],
            ['name' => 'Class 3', 'level' => 3],
            ['name' => 'Class 4', 'level' => 4],
            ['name' => 'Class 5', 'level' => 5],
            ['name' => 'Class 6', 'level' => 6],
            ['name' => 'Class 7', 'level' => 7],
            ['name' => 'Class 8', 'level' => 8],
            ['name' => 'Class 9', 'level' => 9],
            ['name' => 'Class 10', 'level' => 10],
        ];

        $classes = [];
        $sections = [];
        foreach ($classList as $cData) {
            $c = SchoolClass::create([
                'school_id' => $school->id,
                'name' => $cData['name'],
                'numeric_level' => $cData['level'],
            ]);
            $classes[$cData['name']] = $c;

            $secA = Section::create(['class_id' => $c->id, 'name' => 'Section A', 'capacity' => 40]);
            $secB = Section::create(['class_id' => $c->id, 'name' => 'Section B', 'capacity' => 40]);
            $sections[$cData['name']] = [$secA, $secB];
        }

        // 5. Subjects
        $subjectNames = [
            ['name' => 'Mathematics', 'code' => 'MATH-101', 'type' => 'THEORY'],
            ['name' => 'Science & Physics', 'code' => 'SCI-102', 'type' => 'THEORY'],
            ['name' => 'English Language & Lit', 'code' => 'ENG-103', 'type' => 'THEORY'],
            ['name' => 'Social Studies & Civics', 'code' => 'SST-104', 'type' => 'THEORY'],
            ['name' => 'Hindi Sahitya', 'code' => 'HIN-105', 'type' => 'THEORY'],
            ['name' => 'Computer Applications', 'code' => 'CS-106', 'type' => 'PRACTICAL'],
            ['name' => 'Physical & Sports Ed', 'code' => 'PE-107', 'type' => 'PRACTICAL'],
            ['name' => 'Art & Design', 'code' => 'ART-108', 'type' => 'PRACTICAL'],
        ];

        $subjects = [];
        foreach ($subjectNames as $sData) {
            $s = Subject::create([
                'school_id' => $school->id,
                'name' => $sData['name'],
                'code' => $sData['code'],
                'type' => $sData['type'],
            ]);
            $subjects[] = $s;
        }

        // Attach subjects to Class 10 & Class 9
        foreach ([$classes['Class 10'], $classes['Class 9'], $classes['Class 8']] as $cl) {
            foreach ($subjects as $sb) {
                $cl->subjects()->attach($sb->id);
            }
        }

        // 6. Users & Staff
        $adminUser = User::create([
            'name' => 'School Admin',
            'email' => 'admin@yugschool.com',
            'role' => 'SCHOOL_ADMIN',
            'school_id' => $school->id,
            'password' => Hash::make('password'),
        ]);

        $superUser = User::create([
            'name' => 'Platform Owner',
            'email' => 'superadmin@multischoolerp.com',
            'role' => 'SUPERADMIN',
            'school_id' => null,
            'password' => Hash::make('password'),
        ]);

        $teacherUser = User::create([
            'name' => 'Sunita Sharma',
            'email' => 'teacher@yugschool.com',
            'role' => 'TEACHER',
            'school_id' => $school->id,
            'password' => Hash::make('password'),
        ]);

        $accountantUser = User::create([
            'name' => 'Rajesh Verma',
            'email' => 'accountant@yugschool.com',
            'role' => 'ACCOUNTANT',
            'school_id' => $school->id,
            'password' => Hash::make('password'),
        ]);

        // Staff members (7 Staff members matching ERP demo)
        $staffMembers = [
            [
                'employee_id' => 'EMP-001',
                'first_name' => 'Dr. Arvind',
                'last_name' => 'Mehta',
                'email' => 'arvind.mehta@yugschool.com',
                'phone' => '+91 98111 22334',
                'designation' => 'Principal',
                'department' => 'Administration',
                'qualification' => 'Ph.D in Education Leadership',
                'joining_date' => '2020-06-01',
                'basic_salary' => 95000,
            ],
            [
                'employee_id' => 'EMP-002',
                'first_name' => 'Sunita',
                'last_name' => 'Sharma',
                'email' => 'teacher@yugschool.com',
                'phone' => '+91 98222 33445',
                'designation' => 'Senior PGT Teacher',
                'department' => 'Mathematics',
                'qualification' => 'M.Sc. Mathematics, B.Ed',
                'joining_date' => '2021-04-15',
                'basic_salary' => 62000,
                'user_id' => $teacherUser->id,
            ],
            [
                'employee_id' => 'EMP-003',
                'first_name' => 'Rajesh',
                'last_name' => 'Verma',
                'email' => 'accountant@yugschool.com',
                'phone' => '+91 98333 44556',
                'designation' => 'Chief Accountant',
                'department' => 'Finance & Accounts',
                'qualification' => 'M.Com, Chartered Accountant (Inter)',
                'joining_date' => '2021-08-01',
                'basic_salary' => 58000,
                'user_id' => $accountantUser->id,
            ],
            [
                'employee_id' => 'EMP-004',
                'first_name' => 'Amit',
                'last_name' => 'Choudhary',
                'email' => 'amit.science@yugschool.com',
                'phone' => '+91 98444 55667',
                'designation' => 'TGT Science Teacher',
                'department' => 'Science',
                'qualification' => 'M.Sc. Physics, B.Ed',
                'joining_date' => '2022-01-10',
                'basic_salary' => 52000,
            ],
            [
                'employee_id' => 'EMP-005',
                'first_name' => 'Priya',
                'last_name' => 'Nair',
                'email' => 'priya.english@yugschool.com',
                'phone' => '+91 98555 66778',
                'designation' => 'TGT English Teacher',
                'department' => 'Humanities',
                'qualification' => 'M.A. English Lit, B.Ed',
                'joining_date' => '2022-07-01',
                'basic_salary' => 50000,
            ],
            [
                'employee_id' => 'EMP-006',
                'first_name' => 'Vikram',
                'last_name' => 'Rathore',
                'email' => 'sports@yugschool.com',
                'phone' => '+91 98666 77889',
                'designation' => 'Physical Training Instructor',
                'department' => 'Sports & Athletics',
                'qualification' => 'M.P.Ed',
                'joining_date' => '2023-02-15',
                'basic_salary' => 45000,
            ],
            [
                'employee_id' => 'EMP-007',
                'first_name' => 'Meenakshi',
                'last_name' => 'Sundaram',
                'email' => 'library@yugschool.com',
                'phone' => '+91 98777 88990',
                'designation' => 'Chief Librarian',
                'department' => 'Library Services',
                'qualification' => 'M.Lib.Sc',
                'joining_date' => '2023-05-01',
                'basic_salary' => 42000,
            ],
        ];

        $staffModels = [];
        foreach ($staffMembers as $sData) {
            $sData['school_id'] = $school->id;
            $staffModels[] = Staff::create($sData);
        }

        // 7. Seed Students & Guardians
        $studentSeeds = [
            [
                'admission_no' => 'YISADM-004',
                'roll_no' => '04',
                'first_name' => 'Kabir',
                'last_name' => 'Singh',
                'gender' => 'Male',
                'dob' => Carbon::now()->subYears(4)->format('Y-m-d'), // Today is birthday!
                'blood_group' => 'O+',
                'class' => 'Nursery',
                'section_idx' => 0,
                'father' => 'Gurmukh Singh',
                'phone' => '+91 99100 11001',
                'address' => 'Flat 204, Royal Palms, City Center',
            ],
            [
                'admission_no' => 'YISADM-001',
                'roll_no' => '101',
                'first_name' => 'Aarav',
                'last_name' => 'Sharma',
                'gender' => 'Male',
                'dob' => '2010-05-14',
                'blood_group' => 'B+',
                'class' => 'Class 10',
                'section_idx' => 0,
                'father' => 'Sanjay Sharma',
                'phone' => '+91 99100 11002',
                'address' => 'House 14, Sector 15, Green Park',
            ],
            [
                'admission_no' => 'YISADM-002',
                'roll_no' => '102',
                'first_name' => 'Ishita',
                'last_name' => 'Verma',
                'gender' => 'Female',
                'dob' => '2010-09-22',
                'blood_group' => 'A+',
                'class' => 'Class 10',
                'section_idx' => 0,
                'father' => 'Manoj Verma',
                'phone' => '+91 99100 11003',
                'address' => 'Villa 7, Orchid Enclave',
            ],
            [
                'admission_no' => 'YISADM-003',
                'roll_no' => '901',
                'first_name' => 'Vihaan',
                'last_name' => 'Gupta',
                'gender' => 'Male',
                'dob' => '2011-03-18',
                'blood_group' => 'AB+',
                'class' => 'Class 9',
                'section_idx' => 1,
                'father' => 'Naveen Gupta',
                'phone' => '+91 99100 11004',
                'address' => 'B-302, Sunrise Towers',
            ],
            [
                'admission_no' => 'YISADM-005',
                'roll_no' => '801',
                'first_name' => 'Ananya',
                'last_name' => 'Patel',
                'gender' => 'Female',
                'dob' => '2012-11-12',
                'blood_group' => 'O+',
                'class' => 'Class 8',
                'section_idx' => 0,
                'father' => 'Ketan Patel',
                'phone' => '+91 99100 11005',
                'address' => 'Block C, Heritage Heights',
            ],
            [
                'admission_no' => 'YISADM-006',
                'roll_no' => '701',
                'first_name' => 'Rohan',
                'last_name' => 'Das',
                'gender' => 'Male',
                'dob' => '2013-01-25',
                'blood_group' => 'B+',
                'class' => 'Class 7',
                'section_idx' => 1,
                'father' => 'Subhash Das',
                'phone' => '+91 99100 11006',
                'address' => '12A, Metro Apartments',
            ],
            [
                'admission_no' => 'YISADM-007',
                'roll_no' => '601',
                'first_name' => 'Diya',
                'last_name' => 'Sen',
                'gender' => 'Female',
                'dob' => '2014-07-08',
                'blood_group' => 'A-',
                'class' => 'Class 6',
                'section_idx' => 0,
                'father' => 'Debashis Sen',
                'phone' => '+91 99100 11007',
                'address' => '45, Lake View Road',
            ],
            [
                'admission_no' => 'YISADM-008',
                'roll_no' => '501',
                'first_name' => 'Aryan',
                'last_name' => 'Roy',
                'gender' => 'Male',
                'dob' => '2015-04-30',
                'blood_group' => 'O-',
                'class' => 'Class 5',
                'section_idx' => 0,
                'father' => 'Sourav Roy',
                'phone' => '+91 99100 11008',
                'address' => '78, Civil Lines',
            ],
            [
                'admission_no' => 'YISADM-009',
                'roll_no' => '103',
                'first_name' => 'Tanvi',
                'last_name' => 'Deshmukh',
                'gender' => 'Female',
                'dob' => '2010-12-05',
                'blood_group' => 'B-',
                'class' => 'Class 10',
                'section_idx' => 1,
                'father' => 'Ashok Deshmukh',
                'phone' => '+91 99100 11009',
                'address' => '9, Mayflower Gardens',
            ],
            [
                'admission_no' => 'YISADM-010',
                'roll_no' => '401',
                'first_name' => 'Reyansh',
                'last_name' => 'Bhatia',
                'gender' => 'Male',
                'dob' => '2016-08-19',
                'blood_group' => 'O+',
                'class' => 'Class 4',
                'section_idx' => 0,
                'father' => 'Gaurav Bhatia',
                'phone' => '+91 99100 11010',
                'address' => '33, Defense Colony',
            ],
        ];

        $allStudents = [];
        foreach ($studentSeeds as $stData) {
            $student = Student::create([
                'school_id' => $school->id,
                'admission_no' => $stData['admission_no'],
                'roll_no' => $stData['roll_no'],
                'first_name' => $stData['first_name'],
                'last_name' => $stData['last_name'],
                'gender' => $stData['gender'],
                'dob' => $stData['dob'],
                'blood_group' => $stData['blood_group'],
                'admission_date' => '2026-04-05',
                'status' => 'active',
            ]);

            Guardian::create([
                'student_id' => $student->id,
                'father_name' => $stData['father'],
                'father_phone' => $stData['phone'],
                'mother_name' => 'Mrs. ' . $stData['last_name'],
                'emergency_contact' => $stData['phone'],
                'address' => $stData['address'],
            ]);

            $cl = $classes[$stData['class']];
            $sec = $sections[$stData['class']][$stData['section_idx']];

            StudentEnrollment::create([
                'student_id' => $student->id,
                'session_id' => $session->id,
                'class_id' => $cl->id,
                'section_id' => $sec->id,
                'enrolled_at' => '2026-04-05',
            ]);

            $allStudents[] = $student;
        }

        // 8. Fee Setup & Dues
        $tuitionGroup = FeeGroup::create([
            'school_id' => $school->id,
            'name' => 'Tuition Fees 2026-27',
            'description' => 'Standard quarterly academic tuition fee',
        ]);

        $annualGroup = FeeGroup::create([
            'school_id' => $school->id,
            'name' => 'Annual Charges',
            'description' => 'Development, Library & Laboratory charges',
        ]);

        $feeTypeQ1 = FeeType::create([
            'fee_group_id' => $tuitionGroup->id,
            'name' => 'Quarter 1 Tuition Fee',
            'code' => 'TUI-Q1',
        ]);

        $feeTypeQ2 = FeeType::create([
            'fee_group_id' => $tuitionGroup->id,
            'name' => 'Quarter 2 Tuition Fee',
            'code' => 'TUI-Q2',
        ]);

        $feeTypeAnnual = FeeType::create([
            'fee_group_id' => $annualGroup->id,
            'name' => 'Annual Development & IT Charges',
            'code' => 'ANN-DEV',
        ]);

        // Fee Masters
        $fmQ1 = FeeMaster::create([
            'school_id' => $school->id,
            'session_id' => $session->id,
            'fee_type_id' => $feeTypeQ1->id,
            'class_id' => null,
            'amount' => 18500,
            'due_date' => '2026-05-15',
            'fine_amount' => 500,
        ]);

        $fmQ2 = FeeMaster::create([
            'school_id' => $school->id,
            'session_id' => $session->id,
            'fee_type_id' => $feeTypeQ2->id,
            'class_id' => null,
            'amount' => 18500,
            'due_date' => '2026-09-15',
            'fine_amount' => 500,
        ]);

        $fmAnnual = FeeMaster::create([
            'school_id' => $school->id,
            'session_id' => $session->id,
            'fee_type_id' => $feeTypeAnnual->id,
            'class_id' => null,
            'amount' => 25000,
            'due_date' => '2026-04-30',
            'fine_amount' => 1000,
        ]);

        // Allocations & Payments for students
        foreach ($allStudents as $idx => $st) {
            // Allocate Q1
            $allocQ1 = StudentFeeAllocation::create([
                'student_id' => $st->id,
                'fee_master_id' => $fmQ1->id,
                'discount_amount' => ($idx % 3 == 0) ? 1500 : 0,
                'final_amount' => ($idx % 3 == 0) ? 17000 : 18500,
                'status' => ($idx < 6) ? 'paid' : 'unpaid',
            ]);

            if ($idx < 6) {
                FeePayment::create([
                    'student_id' => $st->id,
                    'allocation_id' => $allocQ1->id,
                    'invoice_no' => 'INV-2026-' . str_pad($st->id * 10 + 1, 5, '0', STR_PAD_LEFT),
                    'amount_paid' => $allocQ1->final_amount,
                    'payment_method' => ($idx % 2 == 0) ? 'UPI' : 'Cash',
                    'transaction_id' => ($idx % 2 == 0) ? 'UPI/' . rand(10000000, 99999999) : null,
                    'payment_date' => Carbon::now()->subDays(rand(1, 20))->format('Y-m-d'),
                    'received_by' => $accountantUser->id,
                ]);
            }

            // Allocate Q2 (mostly unpaid to show realistic pending dues)
            StudentFeeAllocation::create([
                'student_id' => $st->id,
                'fee_master_id' => $fmQ2->id,
                'discount_amount' => 0,
                'final_amount' => 18500,
                'status' => 'unpaid',
            ]);
        }

        // 9. Attendance (Today & past 7 days)
        $today = Carbon::today();
        for ($day = 0; $day <= 7; $day++) {
            $date = $today->copy()->subDays($day)->format('Y-m-d');
            foreach ($allStudents as $sIdx => $st) {
                // Today: keep status unfinalized or high attendance
                $status = ($sIdx == 3) ? 'absent' : (($sIdx == 7) ? 'late' : 'present');
                Attendance::create([
                    'school_id' => $school->id,
                    'student_id' => $st->id,
                    'type' => 'student',
                    'date' => $date,
                    'status' => $status,
                ]);
            }

            foreach ($staffModels as $sf) {
                Attendance::create([
                    'school_id' => $school->id,
                    'staff_id' => $sf->id,
                    'type' => 'staff',
                    'date' => $date,
                    'status' => 'present',
                ]);
            }
        }

        // 10. Exams & Schedules & Marks
        $midterm = Exam::create([
            'school_id' => $school->id,
            'session_id' => $session->id,
            'name' => 'Mid-Term Examinations 2026',
            'type' => 'Term Exam',
            'start_date' => '2026-09-15',
            'end_date' => '2026-09-28',
        ]);

        $c10 = $classes['Class 10'];
        $schedMath = ExamSchedule::create([
            'exam_id' => $midterm->id,
            'class_id' => $c10->id,
            'subject_id' => $subjects[0]->id, // Math
            'exam_date' => '2026-09-16',
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'room_no' => 'Exam Hall 1',
            'max_marks' => 100,
            'pass_marks' => 35,
        ]);

        $schedSci = ExamSchedule::create([
            'exam_id' => $midterm->id,
            'class_id' => $c10->id,
            'subject_id' => $subjects[1]->id, // Science
            'exam_date' => '2026-09-18',
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'room_no' => 'Exam Hall 1',
            'max_marks' => 100,
            'pass_marks' => 35,
        ]);

        // Enter Marks for Class 10 students
        $class10Students = [$allStudents[1], $allStudents[2], $allStudents[8]];
        $grades = ['A+', 'A', 'B+'];
        $marksMath = [94, 88, 76];
        $marksSci = [91, 85, 79];

        foreach ($class10Students as $i => $c10St) {
            ExamMark::create([
                'exam_schedule_id' => $schedMath->id,
                'student_id' => $c10St->id,
                'marks_obtained' => $marksMath[$i],
                'grade' => $grades[$i],
                'remarks' => 'Excellent problem solving ability',
            ]);

            ExamMark::create([
                'exam_schedule_id' => $schedSci->id,
                'student_id' => $c10St->id,
                'marks_obtained' => $marksSci[$i],
                'grade' => $grades[$i],
                'remarks' => 'Good conceptual understanding',
            ]);
        }

        // 11. Notices & Events
        Notice::create([
            'school_id' => $school->id,
            'title' => 'Annual Sports Day & Cultural Carnival 2026',
            'description' => 'The Annual Sports Day will be held on Nov 25th. All students are invited to register for athletic tracks.',
            'target_role' => 'all',
            'published_date' => Carbon::today()->subDays(2)->format('Y-m-d'),
            'is_active' => true,
        ]);

        Notice::create([
            'school_id' => $school->id,
            'title' => 'Quarter 2 Fee Submission Deadline',
            'description' => 'Parents are requested to clear all Q2 academic dues by November 15 to avoid late fine surcharges.',
            'target_role' => 'parents',
            'published_date' => Carbon::today()->subDays(5)->format('Y-m-d'),
            'is_active' => true,
        ]);

        Notice::create([
            'school_id' => $school->id,
            'title' => 'National Science Exhibition Nominations',
            'description' => 'Students from Class 6 to 10 with innovative robotics or science models may submit entries to Mr. Amit Choudhary.',
            'target_role' => 'students',
            'published_date' => Carbon::today()->subDays(7)->format('Y-m-d'),
            'is_active' => true,
        ]);

        Event::create([
            'school_id' => $school->id,
            'title' => 'Inter-School Football Championship',
            'description' => 'Tournament between 12 regional high schools on the school sports arena.',
            'event_date' => Carbon::today()->addDays(10)->format('Y-m-d'),
            'location' => 'School Main Sports Arena',
            'status' => 'upcoming',
        ]);

        Event::create([
            'school_id' => $school->id,
            'title' => 'Parent-Teacher Meeting (PTM Term 1)',
            'description' => 'Discussion on student mid-term progress cards and holistic development evaluation.',
            'event_date' => Carbon::today()->addDays(14)->format('Y-m-d'),
            'location' => 'Auditorium & Classrooms',
            'status' => 'upcoming',
        ]);

        // 12. Gate Passes
        GatePass::create([
            'school_id' => $school->id,
            'pass_number' => 'GP-2026-0081',
            'visitor_name' => 'Ramesh Kulkarni',
            'phone' => '+91 98990 01122',
            'purpose' => 'Admission Inquiry for KG & Class 1',
            'person_to_meet' => 'Front Office / Admission Desk',
            'in_time' => Carbon::now()->subHours(1),
            'status' => 'inside',
        ]);

        GatePass::create([
            'school_id' => $school->id,
            'pass_number' => 'GP-2026-0082',
            'visitor_name' => 'Deepak Verma',
            'phone' => '+91 98990 01133',
            'purpose' => 'Library Book Delivery (Oxford University Press)',
            'person_to_meet' => 'Mrs. Meenakshi Sundaram',
            'in_time' => Carbon::now()->subMinutes(30),
            'status' => 'inside',
        ]);

        $this->call(ModulesSuiteSeeder::class);
    }
}
