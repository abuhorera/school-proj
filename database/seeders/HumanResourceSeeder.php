<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Department;
use App\Models\Designation;
use App\Models\LeaveType;
use App\Models\StaffLeave;
use App\Models\SalaryTemplate;
use App\Models\StaffSalary;
use App\Models\Payroll;
use App\Models\StaffPayslip;
use App\Models\StaffAttendance;
use App\Models\StaffLoan;
use App\Models\Staff;
use Carbon\Carbon;

class HumanResourceSeeder extends Seeder
{
    public function run()
    {
        $schoolId = 1;

        // 1. Departments
        $deptNames = [
            'Academics' => 'Teaching faculty, subject coordinators and academic planning',
            'Administration' => 'School administration, registry, HR and admissions desk',
            'Finance & Accounts' => 'Tuition billing, ledger accounting, audits and payroll',
            'Facilities & Transport' => 'Campus maintenance, bus fleet logistics and campus security',
            'Science & Computer Labs' => 'Laboratory instructors and IT systems administration',
        ];

        $depts = [];
        foreach ($deptNames as $name => $desc) {
            $depts[$name] = Department::firstOrCreate(
                ['school_id' => $schoolId, 'name' => $name],
                ['description' => $desc]
            );
        }

        // 2. Designations
        $desigNames = [
            'Principal' => 'Executive head of institution and academic governance',
            'Vice Principal' => 'Senior administrative and curriculum supervisor',
            'Senior PGT Teacher' => 'Post-graduate teacher for senior secondary grades',
            'TGT Teacher' => 'Trained graduate teacher for middle & secondary school',
            'Chief Accountant' => 'Senior accountant in-charge of institutional ledger',
            'Front Office Receptionist' => 'Campus enquiry receptionist and gate pass officer',
        ];

        $desigs = [];
        foreach ($desigNames as $name => $desc) {
            $desigs[$name] = Designation::firstOrCreate(
                ['school_id' => $schoolId, 'name' => $name],
                ['description' => $desc]
            );
        }

        // 3. Leave Types Master
        $leaveData = [
            ['name' => 'Casual Leave (CL)', 'type' => 'Paid', 'yearly_quota' => 12, 'accrual_frequency' => 'Yearly', 'carry_forward' => false, 'description' => 'Personal emergency and casual leaves'],
            ['name' => 'Sick / Medical Leave (ML)', 'type' => 'Paid', 'yearly_quota' => 10, 'accrual_frequency' => 'Yearly', 'carry_forward' => true, 'description' => 'Medical indisposition backed by certificate'],
            ['name' => 'Maternity / Paternity Leave', 'type' => 'Paid', 'yearly_quota' => 90, 'accrual_frequency' => 'Yearly', 'carry_forward' => false, 'description' => 'Parental leave as per statutory guidelines'],
            ['name' => 'Leave Without Pay (LWP)', 'type' => 'Unpaid', 'yearly_quota' => 30, 'accrual_frequency' => 'Yearly', 'carry_forward' => false, 'description' => 'Unpaid leaves with salary proration'],
        ];

        $leaveTypes = [];
        foreach ($leaveData as $l) {
            $leaveTypes[$l['name']] = LeaveType::firstOrCreate(
                ['school_id' => $schoolId, 'name' => $l['name']],
                $l
            );
        }

        // 4. Salary Templates
        $tpl1 = SalaryTemplate::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Senior Faculty Grade A'],
            [
                'basic_salary' => 50000.00,
                'hra' => 10000.00,
                'da' => 5000.00,
                'conveyance' => 3000.00,
                'medical_allowance' => 2000.00,
                'epf_deduction' => 2500.00,
                'esi_deduction' => 800.00,
                'professional_tax' => 200.00,
                'net_salary' => 66500.00,
            ]
        );

        $tpl2 = SalaryTemplate::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'TGT Teacher Grade B'],
            [
                'basic_salary' => 35000.00,
                'hra' => 7000.00,
                'da' => 3500.00,
                'conveyance' => 2000.00,
                'medical_allowance' => 1500.00,
                'epf_deduction' => 1800.00,
                'esi_deduction' => 600.00,
                'professional_tax' => 200.00,
                'net_salary' => 46400.00,
            ]
        );

        $tpl3 = SalaryTemplate::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Administrative Staff Grade C'],
            [
                'basic_salary' => 25000.00,
                'hra' => 5000.00,
                'da' => 2500.00,
                'conveyance' => 1500.00,
                'medical_allowance' => 1000.00,
                'epf_deduction' => 1500.00,
                'esi_deduction' => 500.00,
                'professional_tax' => 200.00,
                'net_salary' => 32800.00,
            ]
        );

        // 5. Seed Staff Members
        $staffMembersData = [
            ['employee_id' => 'EMP-001', 'first_name' => 'Dr. Rajesh', 'last_name' => 'Sharma', 'email' => 'rajesh.sharma@multischoolerp.com', 'phone' => '+91 98765 11001', 'department' => 'Academics', 'designation' => 'Principal', 'basic_salary' => 75000, 'template' => $tpl1],
            ['employee_id' => 'EMP-002', 'first_name' => 'Anita', 'last_name' => 'Deshmukh', 'email' => 'anita.deshmukh@multischoolerp.com', 'phone' => '+91 98765 11002', 'department' => 'Academics', 'designation' => 'Vice Principal', 'basic_salary' => 55000, 'template' => $tpl1],
            ['employee_id' => 'EMP-003', 'first_name' => 'Vikram', 'last_name' => 'Malhotra', 'email' => 'vikram.malhotra@multischoolerp.com', 'phone' => '+91 98765 11003', 'department' => 'Academics', 'designation' => 'Senior PGT Teacher', 'basic_salary' => 48000, 'template' => $tpl1],
            ['employee_id' => 'EMP-004', 'first_name' => 'Pooja', 'last_name' => 'Iyer', 'email' => 'pooja.iyer@multischoolerp.com', 'phone' => '+91 98765 11004', 'department' => 'Academics', 'designation' => 'TGT Teacher', 'basic_salary' => 36000, 'template' => $tpl2],
            ['employee_id' => 'EMP-005', 'first_name' => 'Sanjay', 'last_name' => 'Gupta', 'email' => 'sanjay.gupta@multischoolerp.com', 'phone' => '+91 98765 11005', 'department' => 'Finance & Accounts', 'designation' => 'Chief Accountant', 'basic_salary' => 42000, 'template' => $tpl2],
            ['employee_id' => 'EMP-006', 'first_name' => 'Meera', 'last_name' => 'Patel', 'email' => 'meera.patel@multischoolerp.com', 'phone' => '+91 98765 11006', 'department' => 'Administration', 'designation' => 'Front Office Receptionist', 'basic_salary' => 28000, 'template' => $tpl3],
            ['employee_id' => 'EMP-007', 'first_name' => 'Arun', 'last_name' => 'Verma', 'email' => 'arun.verma@multischoolerp.com', 'phone' => '+91 98765 11007', 'department' => 'Science & Computer Labs', 'designation' => 'Senior PGT Teacher', 'basic_salary' => 45000, 'template' => $tpl1],
            ['employee_id' => 'EMP-008', 'first_name' => 'Kavita', 'last_name' => 'Rao', 'email' => 'kavita.rao@multischoolerp.com', 'phone' => '+91 98765 11008', 'department' => 'Academics', 'designation' => 'TGT Teacher', 'basic_salary' => 34000, 'template' => $tpl2],
        ];

        $staffList = [];
        foreach ($staffMembersData as $sData) {
            $tpl = $sData['template'];
            unset($sData['template']);

            $st = Staff::firstOrCreate(
                ['employee_id' => $sData['employee_id']],
                array_merge($sData, [
                    'school_id' => $schoolId,
                    'status' => 'active',
                    'joining_date' => Carbon::now()->subMonths(18)->toDateString(),
                    'qualification' => 'M.Sc., B.Ed., Ph.D.',
                ])
            );
            $staffList[] = $st;

            // Seed Staff Salary
            StaffSalary::firstOrCreate(
                ['staff_id' => $st->id],
                [
                    'school_id' => $schoolId,
                    'salary_template_id' => $tpl->id,
                    'basic_salary' => $tpl->basic_salary,
                    'total_allowances' => $tpl->hra + $tpl->da + $tpl->conveyance + $tpl->medical_allowance,
                    'total_deductions' => $tpl->epf_deduction + $tpl->esi_deduction + $tpl->professional_tax,
                    'net_salary' => $tpl->net_salary,
                    'payment_method' => 'Bank Transfer',
                    'bank_name' => 'State Bank of India',
                    'bank_account_no' => 'SBI-' . rand(100000000, 999999999),
                    'ifsc_code' => 'SBIN0001234',
                ]
            );

            // Seed Staff Attendance for today
            StaffAttendance::firstOrCreate(
                ['staff_id' => $st->id, 'date' => Carbon::now()->toDateString()],
                [
                    'school_id' => $schoolId,
                    'status' => 'P',
                    'in_time' => '08:30:00',
                    'out_time' => '15:30:00',
                    'remarks' => 'Regular biometric check-in',
                ]
            );
        }

        // 6. Seed Staff Leaves
        $cl = LeaveType::where('name', 'like', '%Casual%')->first();
        $ml = LeaveType::where('name', 'like', '%Medical%')->first();

        if (count($staffList) >= 3 && $cl && $ml) {
            StaffLeave::firstOrCreate(
                ['staff_id' => $staffList[2]->id, 'from_date' => Carbon::now()->addDays(2)->toDateString()],
                [
                    'school_id' => $schoolId,
                    'leave_type_id' => $cl->id,
                    'to_date' => Carbon::now()->addDays(3)->toDateString(),
                    'days_count' => 2,
                    'reason' => 'Attending family wedding ceremony out of station.',
                    'status' => 'Pending',
                    'admin_remarks' => null,
                ]
            );

            StaffLeave::firstOrCreate(
                ['staff_id' => $staffList[3]->id, 'from_date' => Carbon::now()->subDays(5)->toDateString()],
                [
                    'school_id' => $schoolId,
                    'leave_type_id' => $ml->id,
                    'to_date' => Carbon::now()->subDays(4)->toDateString(),
                    'days_count' => 2,
                    'reason' => 'Viral fever and prescribed medical rest.',
                    'status' => 'Approved',
                    'admin_remarks' => 'Approved with medical certificate.',
                ]
            );
        }

        // 7. Seed Staff Loan
        if (count($staffList) >= 4) {
            StaffLoan::firstOrCreate(
                ['staff_id' => $staffList[3]->id],
                [
                    'school_id' => $schoolId,
                    'loan_amount' => 50000.00,
                    'term_months' => 10,
                    'monthly_emi' => 5000.00,
                    'amount_repaid' => 15000.00,
                    'remaining_balance' => 35000.00,
                    'disbursed_date' => Carbon::now()->subMonths(3)->toDateString(),
                    'status' => 'Approved',
                    'reason' => 'Personal home renovation and child admission fee support.',
                ]
            );
        }

        // 8. Seed Payroll Runs
        $totalStaff = count($staffList);
        $grossTotal = $totalStaff * 52000.00;
        $dedTotal = $totalStaff * 2500.00;
        $netTotal = $grossTotal - $dedTotal;

        $payrollNov = Payroll::firstOrCreate(
            ['month_year' => 'November 2026'],
            [
                'school_id' => $schoolId,
                'total_staff' => $totalStaff,
                'gross_amount' => $grossTotal,
                'total_deductions' => $dedTotal,
                'net_total' => $netTotal,
                'status' => 'Generated',
                'payment_date' => Carbon::now()->addDays(1)->toDateString(),
            ]
        );

        $payrollOct = Payroll::firstOrCreate(
            ['month_year' => 'October 2026'],
            [
                'school_id' => $schoolId,
                'total_staff' => $totalStaff,
                'gross_amount' => $grossTotal,
                'total_deductions' => $dedTotal,
                'net_total' => $netTotal,
                'status' => 'Disbursed',
                'payment_date' => Carbon::now()->subDays(30)->toDateString(),
            ]
        );

        foreach ($staffList as $st) {
            $sal = $st->salary;
            $basic = $sal ? $sal->basic_salary : 35000.00;
            $allw = $sal ? $sal->total_allowances : 12000.00;
            $ded = $sal ? $sal->total_deductions : 2500.00;
            $net = $basic + $allw - $ded;

            StaffPayslip::firstOrCreate(
                ['payroll_id' => $payrollNov->id, 'staff_id' => $st->id],
                [
                    'school_id' => $schoolId,
                    'basic_salary' => $basic,
                    'allowances' => $allw,
                    'deductions' => $ded,
                    'net_salary' => $net,
                    'status' => 'Generated',
                ]
            );

            StaffPayslip::firstOrCreate(
                ['payroll_id' => $payrollOct->id, 'staff_id' => $st->id],
                [
                    'school_id' => $schoolId,
                    'basic_salary' => $basic,
                    'allowances' => $allw,
                    'deductions' => $ded,
                    'net_salary' => $net,
                    'status' => 'Paid',
                ]
            );
        }
    }
}

