<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Staff;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * 1. Staff Directory
     */
    public function index(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $query = Staff::where('school_id', $schoolId);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('employee_id', 'like', "%{$s}%")
                  ->orWhere('designation', 'like', "%{$s}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staff = $query->with('salary')->orderBy('employee_id')->paginate(15);
        $departments = Department::where('school_id', $schoolId)->orderBy('name')->get();
        $designations = Designation::where('school_id', $schoolId)->orderBy('name')->get();

        $totalStaff = Staff::where('school_id', $schoolId)->count();
        $activeStaff = Staff::where('school_id', $schoolId)->where('status', 'active')->count();

        return view('staff.index', compact('school', 'staff', 'departments', 'designations', 'totalStaff', 'activeStaff'));
    }

    /**
     * 2. Add New Staff Member Form
     */
    public function create()
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $departments = Department::where('school_id', $schoolId)->orderBy('name')->get();
        $designations = Designation::where('school_id', $schoolId)->orderBy('name')->get();
        $templates = SalaryTemplate::where('school_id', $schoolId)->orderBy('name')->get();

        $nextEmployeeId = 'EMP-' . str_pad(Staff::count() + 1, 3, '0', STR_PAD_LEFT);

        return view('staff.create', compact('school', 'departments', 'designations', 'templates', 'nextEmployeeId'));
    }

    /**
     * Store New Staff Member
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:staff,employee_id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'designation' => 'required|string',
            'department' => 'required|string',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:30',
            'basic_salary' => 'nullable|numeric',
        ]);

        $schoolId = 1;

        $staff = Staff::create([
            'school_id' => $schoolId,
            'employee_id' => $request->employee_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'designation' => $request->designation,
            'department' => $request->department,
            'qualification' => $request->qualification ?? 'B.Ed / Masters Degree',
            'joining_date' => $request->joining_date ?? Carbon::now()->toDateString(),
            'basic_salary' => $request->basic_salary ?? 30000.00,
            'status' => 'active',
        ]);

        // Create default salary record
        $basic = $request->basic_salary ?? 30000.00;
        $allw = $basic * 0.35;
        $ded = $basic * 0.08;
        $net = $basic + $allw - $ded;

        StaffSalary::create([
            'school_id' => $schoolId,
            'staff_id' => $staff->id,
            'salary_template_id' => $request->salary_template_id ?? null,
            'basic_salary' => $basic,
            'total_allowances' => $allw,
            'total_deductions' => $ded,
            'net_salary' => $net,
            'payment_method' => 'Bank Transfer',
            'bank_name' => 'State Bank of India',
            'bank_account_no' => 'SBI-' . rand(100000000, 999999999),
        ]);

        return redirect()->route('staff.index')
            ->with('success', "Staff member '{$staff->first_name} {$staff->last_name}' (ID: {$staff->employee_id}) onboarded successfully!");
    }

    /**
     * 3. Staff Attendance Register
     */
    public function attendance(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $date = $request->get('date', Carbon::now()->toDateString());
        $dept = $request->get('department');

        $departments = Department::where('school_id', $schoolId)->orderBy('name')->get();

        $staffList = Staff::where('school_id', $schoolId)
            ->where('status', 'active')
            ->when($dept, function ($q) use ($dept) {
                $q->where('department', $dept);
            })
            ->orderBy('employee_id')
            ->get();

        // Load attendances for this date
        $attendances = StaffAttendance::where('school_id', $schoolId)
            ->where('date', $date)
            ->get()
            ->keyBy('staff_id');

        return view('staff.attendance', compact('school', 'date', 'dept', 'departments', 'staffList', 'attendances'));
    }

    /**
     * Store Staff Daily Attendance
     */
    public function storeAttendance(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());
        $attendanceData = $request->input('attendance', []);
        $schoolId = 1;

        foreach ($attendanceData as $staffId => $val) {
            if (is_array($val)) {
                $status = $val['status'] ?? 'P';
                $inTime = $val['in_time'] ?? (in_array($status, ['P', 'L']) ? '08:30:00' : null);
                $outTime = $val['out_time'] ?? (in_array($status, ['P', 'L']) ? '15:30:00' : null);
                $remarks = $val['remarks'] ?? ($status == 'L' ? 'Late arrival' : null);
            } else {
                $status = (string)$val;
                $inTime = in_array($status, ['P', 'L']) ? '08:30:00' : null;
                $outTime = in_array($status, ['P', 'L']) ? '15:30:00' : null;
                $remarks = $status == 'L' ? 'Late arrival' : null;
            }

            StaffAttendance::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'staff_id' => $staffId,
                    'date' => $date,
                ],
                [
                    'status' => $status,
                    'in_time' => $inTime,
                    'out_time' => $outTime,
                    'remarks' => $remarks,
                ]
            );
        }

        return redirect()->back()->with('success', "Staff attendance for {$date} saved successfully!");
    }

    /**
     * 4. Monthly Payroll Runs
     */
    public function payroll(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $payrolls = Payroll::where('school_id', $schoolId)
            ->withCount('payslips')
            ->latest()
            ->get();

        $staffCount = Staff::where('school_id', $schoolId)->where('status', 'active')->count();

        return view('staff.payroll', compact('school', 'payrolls', 'staffCount'));
    }

    /**
     * Generate Monthly Payroll
     */
    public function generatePayroll(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $monthYear = "{$validated['month']} {$validated['year']}";
        $schoolId = 1;

        $staffMembers = Staff::where('school_id', $schoolId)
            ->where('status', 'active')
            ->with('salary')
            ->get();

        $totalBasic = 0;
        $totalAllowances = 0;
        $totalDeductions = 0;
        $netTotal = 0;

        foreach ($staffMembers as $st) {
            $sal = $st->salary;
            $basic = $sal ? $sal->basic_salary : 35000;
            $allw = $sal ? $sal->total_allowances : 12000;
            $ded = $sal ? $sal->total_deductions : 2500;

            $totalBasic += $basic;
            $totalAllowances += $allw;
            $totalDeductions += $ded;
            $netTotal += ($basic + $allw - $ded);
        }

        $payroll = Payroll::create([
            'school_id' => $schoolId,
            'month_year' => $monthYear,
            'total_staff' => $staffMembers->count(),
            'gross_amount' => $totalBasic + $totalAllowances,
            'total_deductions' => $totalDeductions,
            'net_total' => $netTotal,
            'status' => 'Generated',
            'payment_date' => Carbon::now()->toDateString(),
        ]);

        foreach ($staffMembers as $st) {
            $sal = $st->salary;
            $basic = $sal ? $sal->basic_salary : 35000;
            $allw = $sal ? $sal->total_allowances : 12000;
            $ded = $sal ? $sal->total_deductions : 2500;

            StaffPayslip::create([
                'school_id' => $schoolId,
                'payroll_id' => $payroll->id,
                'staff_id' => $st->id,
                'basic_salary' => $basic,
                'allowances' => $allw,
                'deductions' => $ded,
                'net_salary' => $basic + $allw - $ded,
                'status' => 'Generated',
            ]);
        }

        return redirect()->back()->with('success', "Monthly payroll for {$monthYear} generated! {$staffMembers->count()} payslips created.");
    }

    /**
     * Mark Payroll Disbursed
     */
    public function togglePayrollStatus($id)
    {
        $payroll = Payroll::findOrFail($id);
        $newStatus = $payroll->status == 'Generated' ? 'Disbursed' : 'Generated';
        $payroll->update([
            'status' => $newStatus,
            'payment_date' => Carbon::now()->toDateString(),
        ]);

        $payroll->payslips()->update(['status' => $newStatus == 'Disbursed' ? 'Paid' : 'Generated']);

        return redirect()->back()->with('success', "Payroll status updated to '{$newStatus}'.");
    }

    /**
     * 5. Set Salary (Staff Individual Compensation)
     */
    public function staffSalary(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $dept = $request->get('department');
        $departments = Department::where('school_id', $schoolId)->orderBy('name')->get();
        $templates = SalaryTemplate::where('school_id', $schoolId)->orderBy('name')->get();

        $staff = Staff::where('school_id', $schoolId)
            ->when($dept, function ($q) use ($dept) { $q->where('department', $dept); })
            ->with(['salary.template'])
            ->orderBy('employee_id')
            ->get();

        return view('staff.salary', compact('school', 'staff', 'departments', 'templates', 'dept'));
    }

    /**
     * Update Staff Salary
     */
    public function updateStaffSalary(Request $request, $id)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'total_allowances' => 'nullable|numeric|min:0',
            'total_deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'bank_account_no' => 'nullable|string',
            'bank_name' => 'nullable|string',
        ]);

        $basic = $validated['basic_salary'];
        $allw = $validated['total_allowances'] ?? 0;
        $ded = $validated['total_deductions'] ?? 0;
        $net = $basic + $allw - $ded;

        StaffSalary::updateOrCreate(
            ['staff_id' => $id],
            [
                'school_id' => 1,
                'basic_salary' => $basic,
                'total_allowances' => $allw,
                'total_deductions' => $ded,
                'net_salary' => $net,
                'payment_method' => $validated['payment_method'] ?? 'Bank Transfer',
                'bank_account_no' => $validated['bank_account_no'] ?? null,
                'bank_name' => $validated['bank_name'] ?? 'State Bank of India',
            ]
        );

        return redirect()->back()->with('success', "Compensation configuration updated successfully!");
    }

    /**
     * 6. Salary Templates Master
     */
    public function salaryTemplates(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $templates = SalaryTemplate::where('school_id', $schoolId)
            ->withCount('staffSalaries')
            ->latest()
            ->get();

        return view('staff.salary_templates', compact('school', 'templates'));
    }

    public function storeSalaryTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'basic_salary' => 'required|numeric|min:1',
            'hra' => 'nullable|numeric|min:0',
            'da' => 'nullable|numeric|min:0',
            'conveyance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'epf_deduction' => 'nullable|numeric|min:0',
            'esi_deduction' => 'nullable|numeric|min:0',
            'professional_tax' => 'nullable|numeric|min:0',
        ]);

        $basic = $validated['basic_salary'];
        $allw = ($validated['hra'] ?? 0) + ($validated['da'] ?? 0) + ($validated['conveyance'] ?? 0) + ($validated['medical_allowance'] ?? 0);
        $ded = ($validated['epf_deduction'] ?? 0) + ($validated['esi_deduction'] ?? 0) + ($validated['professional_tax'] ?? 0);
        $net = $basic + $allw - $ded;

        SalaryTemplate::create(array_merge($validated, [
            'school_id' => 1,
            'net_salary' => $net,
        ]));

        return redirect()->back()->with('success', "Salary structure template '{$validated['name']}' created!");
    }

    public function destroySalaryTemplate($id)
    {
        $tpl = SalaryTemplate::findOrFail($id);
        $name = $tpl->name;
        $tpl->delete();

        return redirect()->back()->with('success', "Salary template '{$name}' deleted.");
    }

    /**
     * 7. Approve Leave Workflow
     */
    public function approveLeave(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $status = $request->get('status');
        $leaves = StaffLeave::where('school_id', $schoolId)
            ->when($status, function ($q) use ($status) { $q->where('status', $status); })
            ->with(['staff', 'leaveType'])
            ->latest()
            ->get();

        return view('staff.approve_leave', compact('school', 'leaves', 'status'));
    }

    public function updateLeaveStatus(Request $request, $id)
    {
        $leave = StaffLeave::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected,Pending',
            'admin_remarks' => 'nullable|string',
        ]);

        $leave->update($validated);

        return redirect()->back()->with('success', "Leave application status updated to '{$validated['status']}'.");
    }

    /**
     * 8. Leave Types Master
     */
    public function leaveTypes(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $leaveTypes = LeaveType::where('school_id', $schoolId)
            ->withCount('leaves')
            ->latest()
            ->get();

        return view('staff.leave_types', compact('school', 'leaveTypes'));
    }

    public function storeLeaveType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|string|in:Paid,Unpaid',
            'yearly_quota' => 'required|integer|min:1',
            'accrual_frequency' => 'nullable|string',
            'carry_forward' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        LeaveType::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'yearly_quota' => $validated['yearly_quota'],
            'accrual_frequency' => $validated['accrual_frequency'] ?? 'Yearly',
            'carry_forward' => $request->has('carry_forward'),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', "Leave type '{$validated['name']}' saved successfully!");
    }

    public function destroyLeaveType($id)
    {
        $lt = LeaveType::findOrFail($id);
        $name = $lt->name;
        $lt->delete();

        return redirect()->back()->with('success', "Leave type '{$name}' deleted.");
    }

    /**
     * 9. Departments Catalog
     */
    public function departments(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $departments = Department::where('school_id', $schoolId)
            ->withCount('staff')
            ->latest()
            ->get();

        return view('staff.departments', compact('school', 'departments'));
    }

    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
        ]);

        Department::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', "Department '{$validated['name']}' added successfully!");
    }

    public function destroyDepartment($id)
    {
        $dept = Department::findOrFail($id);
        $name = $dept->name;
        $dept->delete();

        return redirect()->back()->with('success', "Department '{$name}' removed.");
    }

    /**
     * 10. Designations Catalog
     */
    public function designations(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $designations = Designation::where('school_id', $schoolId)
            ->withCount('staff')
            ->latest()
            ->get();

        return view('staff.designations', compact('school', 'designations'));
    }

    public function storeDesignation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
        ]);

        Designation::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', "Designation '{$validated['name']}' added successfully!");
    }

    public function destroyDesignation($id)
    {
        $desig = Designation::findOrFail($id);
        $name = $desig->name;
        $desig->delete();

        return redirect()->back()->with('success', "Designation '{$name}' removed.");
    }

    /**
     * 11. Manage Staff Loans
     */
    public function loans(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $staffList = Staff::where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();

        $loans = StaffLoan::where('school_id', $schoolId)
            ->with('staff')
            ->latest()
            ->get();

        return view('staff.loans', compact('school', 'staffList', 'loans'));
    }

    public function storeLoan(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'loan_amount' => 'required|numeric|min:1000',
            'term_months' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $amount = $validated['loan_amount'];
        $months = $validated['term_months'];
        $emi = round($amount / $months, 2);

        StaffLoan::create([
            'school_id' => 1,
            'staff_id' => $validated['staff_id'],
            'loan_amount' => $amount,
            'term_months' => $months,
            'monthly_emi' => $emi,
            'amount_repaid' => 0.00,
            'remaining_balance' => $amount,
            'disbursed_date' => Carbon::now()->toDateString(),
            'status' => 'Approved',
            'reason' => $validated['reason'] ?? 'Staff personal assistance advance',
        ]);

        return redirect()->back()->with('success', "Staff loan of ₹{$amount} approved with monthly EMI of ₹{$emi}!");
    }

    public function repayLoan(Request $request, $id)
    {
        $loan = StaffLoan::findOrFail($id);
        $repayAmount = $request->input('amount', $loan->monthly_emi);

        $newRepaid = min($loan->loan_amount, $loan->amount_repaid + $repayAmount);
        $newBalance = max(0, $loan->loan_amount - $newRepaid);
        $newStatus = $newBalance == 0 ? 'Repaid' : 'Disbursed';

        $loan->update([
            'amount_repaid' => $newRepaid,
            'remaining_balance' => $newBalance,
            'status' => $newStatus,
        ]);

        return redirect()->back()->with('success', "Loan installment of ₹{$repayAmount} recorded! Remaining balance: ₹{$newBalance}");
    }

    /**
     * 12. Staff ID Cards
     */
    public function idCards(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $dept = $request->get('department');
        $departments = Department::where('school_id', $schoolId)->orderBy('name')->get();

        $staff = Staff::where('school_id', $schoolId)
            ->where('status', 'active')
            ->when($dept, function ($q) use ($dept) { $q->where('department', $dept); })
            ->orderBy('employee_id')
            ->get();

        return view('staff.id_cards', compact('school', 'staff', 'departments', 'dept'));
    }
}
