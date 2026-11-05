<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\AdmissionEnquiry;
use App\Models\Visitor;
use App\Models\Complaint;
use App\Models\PostalRecord;
use App\Models\GatePass;
use App\Models\CampusWorker;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Staff;
use Carbon\Carbon;

class FrontOfficeSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $schoolId = $school ? $school->id : 1;
        $classes = SchoolClass::all();
        $students = Student::all();
        $staff = Staff::all();

        // 1. Seed Admission Enquiries
        AdmissionEnquiry::truncate();
        $enquiriesData = [
            ['name' => 'Aarav Sharma', 'parent_name' => 'Vikram Sharma', 'phone' => '9876543210', 'email' => 'vikram.sharma@example.com', 'class' => 'Nursery', 'source' => 'Walk In', 'status' => 'Open', 'days' => 1],
            ['name' => 'Ananya Verma', 'parent_name' => 'Rajeev Verma', 'phone' => '9812345678', 'email' => 'rajeev.v@example.com', 'class' => 'KG', 'source' => 'Website', 'status' => 'Contacted', 'days' => 2],
            ['name' => 'Kabir Mehra', 'parent_name' => 'Anil Mehra', 'phone' => '9765432109', 'email' => 'anil.m@example.com', 'class' => 'Class 1', 'source' => 'Social Media', 'status' => 'Converted', 'days' => 0],
            ['name' => 'Diya Singhania', 'parent_name' => 'Suresh Singhania', 'phone' => '9654321098', 'email' => 'suresh.s@example.com', 'class' => 'Class 6', 'source' => 'Referral', 'status' => 'Open', 'days' => 3],
            ['name' => 'Reyansh Gupta', 'parent_name' => 'Manish Gupta', 'phone' => '9543210987', 'email' => 'manish.g@example.com', 'class' => 'Class 9', 'source' => 'Newspaper Ad', 'status' => 'Contacted', 'days' => 4],
            ['name' => 'Isha Malhotra', 'parent_name' => 'Praveen Malhotra', 'phone' => '9432109876', 'email' => 'praveen.m@example.com', 'class' => 'Class 11 Science', 'source' => 'Walk In', 'status' => 'Open', 'days' => 1],
            ['name' => 'Vivaan Joshi', 'parent_name' => 'Rakesh Joshi', 'phone' => '9321098765', 'email' => 'rakesh.j@example.com', 'class' => 'Class 4', 'source' => 'Website', 'status' => 'Closed', 'days' => 7],
            ['name' => 'Myra Kapoor', 'parent_name' => 'Sameer Kapoor', 'phone' => '9210987654', 'email' => 'sameer.k@example.com', 'class' => 'Class 2', 'source' => 'Walk In', 'status' => 'Open', 'days' => 2],
        ];

        foreach ($enquiriesData as $item) {
            $cls = $classes->where('name', $item['class'])->first() ?? $classes->first();
            AdmissionEnquiry::create([
                'school_id' => $schoolId,
                'name' => $item['name'],
                'parent_name' => $item['parent_name'],
                'phone' => $item['phone'],
                'email' => $item['email'],
                'class_applying_for' => $item['class'],
                'school_class_id' => $cls ? $cls->id : null,
                'source' => $item['source'],
                'status' => $item['status'],
                'enquiry_date' => Carbon::now()->subDays($item['days'])->format('Y-m-d'),
                'next_follow_up' => Carbon::now()->addDays($item['days'] + 1)->format('Y-m-d'),
                'assigned_to' => $staff->isNotEmpty() ? $staff->first()->id : null,
                'remarks' => 'Parent visited campus and requested fee brochure & school tour.',
            ]);
        }

        // 2. Seed Visitor Logs
        Visitor::truncate();
        $visitorsData = [
            ['name' => 'Dr. Subhash Chandra', 'phone' => '9888877771', 'purpose' => 'Health Inspection', 'person' => 'Principal Office', 'card' => 'V-901', 'in' => '09:15 AM', 'out' => '10:45 AM', 'status' => 'checked_out'],
            ['name' => 'Ramesh Patel', 'phone' => '9888877772', 'purpose' => 'NCERT Book Delivery', 'person' => 'Library Incharge', 'card' => 'V-902', 'in' => '10:00 AM', 'out' => '10:30 AM', 'status' => 'checked_out'],
            ['name' => 'Sunita Aggarwal', 'phone' => '9888877773', 'purpose' => 'Parent Teacher Meet', 'person' => 'Coordinator Class 10', 'card' => 'V-903', 'in' => '11:00 AM', 'out' => null, 'status' => 'checked_in'],
            ['name' => 'Devendra Singh', 'phone' => '9888877774', 'purpose' => 'CCTV Maintenance', 'person' => 'Admin Manager', 'card' => 'V-904', 'in' => '11:30 AM', 'out' => null, 'status' => 'checked_in'],
            ['name' => 'Anita Desai', 'phone' => '9888877775', 'purpose' => 'Fee Receipt Clarification', 'person' => 'Accounts Counter 1', 'card' => 'V-905', 'in' => '12:00 PM', 'out' => '12:40 PM', 'status' => 'checked_out'],
            ['name' => 'Mahesh Verma', 'phone' => '9888877776', 'purpose' => 'Transport Contract Review', 'person' => 'Transport Officer', 'card' => 'V-906', 'in' => '01:15 PM', 'out' => null, 'status' => 'checked_in'],
        ];

        foreach ($visitorsData as $v) {
            Visitor::create([
                'school_id' => $schoolId,
                'name' => $v['name'],
                'phone' => $v['phone'],
                'purpose' => $v['purpose'],
                'person_to_meet' => $v['person'],
                'id_card_number' => $v['card'],
                'in_time' => Carbon::now()->format('Y-m-d') . ' ' . date('H:i:s', strtotime($v['in'])),
                'out_time' => $v['out'] ? Carbon::now()->format('Y-m-d') . ' ' . date('H:i:s', strtotime($v['out'])) : null,
                'status' => $v['status'],
            ]);
        }

        // 3. Seed Complaints
        Complaint::truncate();
        $complaintsData = [
            ['by' => 'Rajesh Kumar (Parent)', 'phone' => '9877712345', 'type' => 'Transport', 'desc' => 'Bus Route No 4 delayed by 25 minutes at Sector 14 stop.', 'status' => 'In Progress', 'days' => 1],
            ['by' => 'Kavita Menon (Parent)', 'phone' => '9877712346', 'type' => 'Cafeteria', 'desc' => 'Requested healthier fruit and juice choices in junior canteen.', 'status' => 'Resolved', 'days' => 3],
            ['by' => 'Arun Saxena (Parent)', 'phone' => '9877712347', 'type' => 'Academics', 'desc' => 'Maths extra-doubt classes needed before quarterly examination.', 'status' => 'Open', 'days' => 0],
            ['by' => 'Pooja Tiwari (Teacher)', 'phone' => '9877712348', 'type' => 'Facilities', 'desc' => 'Projector HDMI port in Physics Lab 2 loose connection.', 'status' => 'In Progress', 'days' => 2],
            ['by' => 'Gurmeet Singh (Parent)', 'phone' => '9877712349', 'type' => 'Fees & Accounts', 'desc' => 'Online payment receipt duplicate SMS confirmation received.', 'status' => 'Resolved', 'days' => 5],
        ];

        foreach ($complaintsData as $c) {
            Complaint::create([
                'school_id' => $schoolId,
                'complaint_by' => $c['by'],
                'complaint_type' => $c['type'],
                'phone' => $c['phone'],
                'date' => Carbon::now()->subDays($c['days'])->format('Y-m-d'),
                'description' => $c['desc'],
                'action_taken' => $c['status'] == 'Resolved' ? 'Verified and rectified by designated department coordinator.' : null,
                'assigned_to' => $staff->isNotEmpty() ? $staff->first()->id : null,
                'status' => $c['status'],
            ]);
        }

        // 4. Seed Postal Records
        PostalRecord::truncate();
        $postalData = [
            ['type' => 'Dispatch', 'from' => 'Yug International School', 'to' => 'CBSE Regional Office, Sector 5', 'addr' => 'Plot 12, Institutional Area, New Delhi', 'ref' => 'DISP-CBSE-2026-08', 'notes' => 'Class 10 & 12 LOC Registration forms'],
            ['type' => 'Receive', 'from' => 'Oxford University Press', 'to' => 'Academic Coordinator', 'addr' => 'Campus Main Gate Reception', 'ref' => 'RCV-OUP-9842', 'notes' => 'Sample NEP 2020 English textbooks consignment'],
            ['type' => 'Dispatch', 'from' => 'Principal Office', 'to' => 'District Education Officer (DEO)', 'addr' => 'Mini Secretariat, District Courts Road', 'ref' => 'DISP-DEO-2026-14', 'notes' => 'RTE 25% admission compliance quarterly audit statement'],
            ['type' => 'Receive', 'from' => 'Speed Post India', 'to' => 'Accounts Department', 'addr' => 'Campus Reception', 'ref' => 'SP-IN-8832049', 'notes' => 'Bank statement letters & cheque clearance confirmations'],
            ['type' => 'Dispatch', 'from' => 'Admissions Cell', 'to' => 'Aarav Gupta (Parent)', 'addr' => 'Flat 402, Green Valley Apts, City', 'ref' => 'DISP-TC-2026-03', 'notes' => 'Verified Transfer Certificate and Character Certificate'],
            ['type' => 'Receive', 'from' => 'Decathlon Sports India', 'to' => 'Sports Department (Mr. Verma)', 'addr' => 'Sports Complex Gate 2', 'ref' => 'RCV-SPT-7721', 'notes' => 'Badminton racquets and tournament footballs'],
        ];

        foreach ($postalData as $p) {
            PostalRecord::create([
                'school_id' => $schoolId,
                'type' => $p['type'],
                'date' => Carbon::now()->subDays(rand(0, 5))->format('Y-m-d'),
                'from_name' => $p['from'],
                'to_name' => $p['to'],
                'to_address' => $p['addr'],
                'reference_number' => $p['ref'],
                'notes' => $p['notes'],
            ]);
        }

        // 5. Seed Gate Passes
        GatePass::truncate();
        $stdList = $students->take(6);
        $passCount = 101;
        foreach ($stdList as $s) {
            $passNum = 'GP-2026-' . $passCount++;
            GatePass::create([
                'school_id' => $schoolId,
                'pass_number' => $passNum,
                'student_id' => $s->id,
                'visitor_name' => $s->first_name . ' ' . $s->last_name,
                'phone' => '987650000' . rand(1, 9),
                'purpose' => 'Early Medical Leave',
                'person_to_meet' => 'Infirmary / Parent Escort',
                'reason_text' => 'High fever reported at morning clinic. Doctor advised rest.',
                'escort_name' => 'Mrs. Sunita ' . $s->last_name,
                'escort_relation' => 'Mother',
                'escort_phone' => '9876512345',
                'escort_id_proof' => 'Aadhaar Card (Verified)',
                'gate_name' => 'Main Gate 1',
                'is_return_expected' => false,
                'in_time' => Carbon::now()->subHours(rand(1, 4)),
                'out_time' => Carbon::now()->subMinutes(rand(10, 45)),
                'status' => 'approved',
                'notes' => 'Class Teacher & Principal signature approved.',
            ]);
        }

        // 6. Seed Campus Workers
        CampusWorker::truncate();
        $workersData = [
            ['badge' => 'CW-101', 'name' => 'Ramphal Yadav', 'phone' => '9811122201', 'cat' => 'Security Guard', 'agency' => 'Eagle Security Services', 'valid_days' => 180, 'verified' => 1, 'on_campus' => 1],
            ['badge' => 'CW-102', 'name' => 'Satish Sharma', 'phone' => '9811122202', 'cat' => 'Chief Electrician', 'agency' => 'Volts Tech Solutions', 'valid_days' => 90, 'verified' => 1, 'on_campus' => 1],
            ['badge' => 'CW-103', 'name' => 'Pappu Lal', 'phone' => '9811122203', 'cat' => 'Head Gardener', 'agency' => 'Green India Landscaping', 'valid_days' => 120, 'verified' => 1, 'on_campus' => 1],
            ['badge' => 'CW-104', 'name' => 'Manish Saini', 'phone' => '9811122204', 'cat' => 'Plumber & Water Works', 'agency' => 'Reliable Maintenance Co', 'valid_days' => 60, 'verified' => 1, 'on_campus' => 0],
            ['badge' => 'CW-105', 'name' => 'Geeta Devi', 'phone' => '9811122205', 'cat' => 'Housekeeping Supervisor', 'agency' => 'Spotless Facility Mgt', 'valid_days' => 180, 'verified' => 1, 'on_campus' => 1],
            ['badge' => 'CW-106', 'name' => 'Bhanu Pratap', 'phone' => '9811122206', 'cat' => 'Bus Driver', 'agency' => 'School Transport Fleet', 'valid_days' => 365, 'verified' => 1, 'on_campus' => 1],
            ['badge' => 'CW-107', 'name' => 'Mukesh Kumar', 'phone' => '9811122207', 'cat' => 'Painter & Mason', 'agency' => 'Apex Construction Works', 'valid_days' => 30, 'verified' => 0, 'on_campus' => 0],
            ['badge' => 'CW-108', 'name' => 'Kailash Chand', 'phone' => '9811122208', 'cat' => 'Canteen Chef', 'agency' => 'Tasty Bites Catering', 'valid_days' => 150, 'verified' => 1, 'on_campus' => 1],
        ];

        foreach ($workersData as $w) {
            CampusWorker::create([
                'school_id' => $schoolId,
                'badge_number' => $w['badge'],
                'name' => $w['name'],
                'phone' => $w['phone'],
                'category' => $w['cat'],
                'agency_name' => $w['agency'],
                'agency_contact' => '9800011122',
                'id_proof' => 'Aadhaar Card',
                'valid_from' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'valid_to' => Carbon::now()->addDays($w['valid_days'])->format('Y-m-d'),
                'police_verified' => $w['verified'],
                'police_verified_on' => $w['verified'] ? Carbon::now()->subMonths(1)->format('Y-m-d') : null,
                'is_on_campus' => $w['on_campus'],
                'notes' => 'RFID campus access badge issued.',
            ]);
        }
    }
}

