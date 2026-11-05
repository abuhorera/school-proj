<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\FeeGroup;
use App\Models\FeeType;
use App\Models\FeeMaster;
use App\Models\StudentFeeAllocation;
use App\Models\FeePayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeController extends Controller
{
    // =========================================================================
    // 1. FEES DASHBOARD (/school/fees-dashboard)
    // =========================================================================
    public function dashboard()
    {
        $school = School::first();
        $totalCollected = FeePayment::sum('amount_paid');
        $totalAllocations = StudentFeeAllocation::sum('final_amount');
        $totalDue = max(0, $totalAllocations - $totalCollected);
        $totalDiscounts = DB::table('student_fee_allocations')->sum('discount_amount');
        $onlineCollected = DB::table('online_payments')->where('status', 'Success')->sum('amount');

        $today = Carbon::today()->format('Y-m-d');
        $collectedToday = FeePayment::whereDate('payment_date', $today)->sum('amount_paid');

        // Top Defaulters Table (Exact match to demo)
        $defaulters = Student::with(['latestEnrollment.schoolClass', 'feeAllocations'])
            ->whereHas('feeAllocations', function ($q) {
                $q->whereIn('status', ['unpaid', 'partial', 'Pending']);
            })
            ->take(10)
            ->get();

        // Recent 5 payments
        $recentPayments = FeePayment::with(['student.latestEnrollment.schoolClass'])
            ->latest('payment_date')
            ->take(5)
            ->get();

        return view('fees.dashboard', compact(
            'school', 'totalCollected', 'totalDue', 'collectedToday', 
            'totalAllocations', 'totalDiscounts', 'onlineCollected', 'defaulters', 'recentPayments'
        ));
    }

    // =========================================================================
    // 2. COLLECT FEES (POS Counter) (/school/collect-fees)
    // =========================================================================
    public function collect(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        
        $studentsQuery = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'feeAllocations.feeMaster.feeType', 'feePayments'])
            ->where('status', 'active');

        if ($request->filled('class_id')) {
            $studentsQuery->whereHas('latestEnrollment', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $studentsQuery->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('admission_no', 'like', "%{$s}%");
            });
        }

        $students = $studentsQuery->orderBy('first_name')->get();

        $selectedStudent = null;
        if ($request->filled('student_id')) {
            $selectedStudent = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'feeAllocations.feeMaster.feeType', 'feePayments'])
                ->find($request->student_id);
        }

        return view('fees.collect', compact('school', 'classes', 'students', 'selectedStudent'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required',
            'payment_date' => 'required|date',
        ]);

        $invoiceNo = 'INV-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));

        $payment = FeePayment::create([
            'student_id' => $request->student_id,
            'allocation_id' => $request->allocation_id ?? null,
            'invoice_no' => $invoiceNo,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id ?? ('TXN-' . rand(100000, 999999)),
            'payment_date' => $request->payment_date,
            'remarks' => $request->remarks ?? 'Counter POS Fee Payment',
            'received_by' => auth()->id() ?? 1,
        ]);

        if ($request->filled('allocation_id')) {
            $alloc = StudentFeeAllocation::find($request->allocation_id);
            if ($alloc) {
                $totalPaidForAlloc = FeePayment::where('allocation_id', $alloc->id)->sum('amount_paid');
                if ($totalPaidForAlloc >= $alloc->final_amount) {
                    $alloc->update(['status' => 'paid']);
                } else {
                    $alloc->update(['status' => 'partial']);
                }
            }
        }

        return redirect()->route('fees.receipt', $payment->id)->with('success', 'Fee payment recorded successfully!');
    }

    // =========================================================================
    // 3. SEARCH DUE FEES (/school/due-fees)
    // =========================================================================
    public function dueFees(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::all();
        $feeGroups = FeeGroup::all();

        $dueStudentsQuery = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'guardian', 'feeAllocations.feeMaster.feeType'])
            ->whereHas('feeAllocations', function ($q) {
                $q->whereIn('status', ['unpaid', 'partial', 'Pending']);
            });

        if ($request->filled('class_id')) {
            $dueStudentsQuery->whereHas('latestEnrollment', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $dueStudents = $dueStudentsQuery->get();

        return view('fees.due_fees', compact('school', 'classes', 'feeGroups', 'dueStudents'));
    }

    // =========================================================================
    // 4. ALL TRANSACTIONS (/school/transactions)
    // =========================================================================
    public function transactions(Request $request)
    {
        $school = School::first();
        $paymentsQuery = FeePayment::with(['student.latestEnrollment.schoolClass', 'allocation.feeMaster.feeType'])
            ->latest('payment_date');

        if ($request->filled('mode')) {
            $paymentsQuery->where('payment_method', $request->mode);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $paymentsQuery->where(function ($q) use ($s) {
                $q->whereHas('student', function ($sq) use ($s) {
                    $sq->where('first_name', 'like', "%{$s}%")
                       ->orWhere('admission_no', 'like', "%{$s}%");
                })->orWhere('invoice_no', 'like', "%{$s}%")
                  ->orWhere('transaction_id', 'like', "%{$s}%");
            });
        }

        $payments = $paymentsQuery->paginate(20);
        $totalAmount = FeePayment::sum('amount_paid');

        return view('fees.transactions', compact('school', 'payments', 'totalAmount'));
    }

    // =========================================================================
    // 5. ONLINE TRANSACTIONS (/school/online-payments)
    // =========================================================================
    public function onlinePayments(Request $request)
    {
        $school = School::first();
        $onlinePayments = DB::table('online_payments')
            ->join('students', 'online_payments.student_id', '=', 'students.id')
            ->select('online_payments.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('online_payments.payment_date', 'desc')
            ->get();

        $successCount = $onlinePayments->where('status', 'Success')->count();
        $failedCount = $onlinePayments->where('status', 'Failed')->count();
        $totalOnline = $onlinePayments->where('status', 'Success')->sum('amount');

        return view('fees.online_payments', compact('school', 'onlinePayments', 'successCount', 'failedCount', 'totalOnline'));
    }

    // =========================================================================
    // 6. FEE CHALLANS (/school/fee-challans)
    // =========================================================================
    public function challans(Request $request)
    {
        $school = School::first();
        $challans = DB::table('fee_challans')
            ->join('students', 'fee_challans.student_id', '=', 'students.id')
            ->select('fee_challans.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('fee_challans.id', 'desc')
            ->get();

        return view('fees.challans', compact('school', 'challans'));
    }

    // =========================================================================
    // 7. ASSIGN FEES (/school/assign-fees)
    // =========================================================================
    public function assignFees(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        $feeGroups = FeeGroup::with('feeTypes')->get();
        $students = Student::with('latestEnrollment.schoolClass')->where('status', 'active')->get();

        return view('fees.assign_fees', compact('school', 'classes', 'feeGroups', 'students'));
    }

    // =========================================================================
    // 8. FEES CARRY FORWARD (/school/fees-carry-forward)
    // =========================================================================
    public function carryForward(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::all();
        $students = Student::with('latestEnrollment.schoolClass')->take(10)->get();

        return view('fees.carry_forward', compact('school', 'classes', 'students'));
    }

    // =========================================================================
    // 9. FEE GROUPS (/school/fee-groups)
    // =========================================================================
    public function feeGroups()
    {
        $school = School::first();
        $groups = FeeGroup::with('feeTypes')->get();

        return view('fees.fee_groups', compact('school', 'groups'));
    }

    // =========================================================================
    // 10. FEES DISCOUNT (/school/fee-discounts)
    // =========================================================================
    public function feeDiscounts()
    {
        $school = School::first();
        $discounts = DB::table('fee_discounts')->get();

        return view('fees.discounts', compact('school', 'discounts'));
    }

    // =========================================================================
    // 11. FEE TYPES (/school/fee-types)
    // =========================================================================
    public function feeTypes()
    {
        $school = School::first();
        $types = FeeType::with('feeGroup')->get();

        return view('fees.fee_types', compact('school', 'types'));
    }

    // =========================================================================
    // 12. GENERATE DUE SLIP (/school/due-slips)
    // =========================================================================
    public function dueSlips(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        $students = Student::with(['latestEnrollment.schoolClass', 'feeAllocations'])->take(8)->get();

        return view('fees.due_slips', compact('school', 'classes', 'students'));
    }

    // =========================================================================
    // 13. DUE SLIP HISTORY (/school/due-slips/history)
    // =========================================================================
    public function dueSlipHistory()
    {
        $school = School::first();
        $history = DB::table('fee_due_slips')
            ->join('students', 'fee_due_slips.student_id', '=', 'students.id')
            ->select('fee_due_slips.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('fee_due_slips.id', 'desc')
            ->get();

        return view('fees.due_slip_history', compact('school', 'history'));
    }

    // =========================================================================
    // 14. FEE DATA AUDIT (/school/fee-audit)
    // =========================================================================
    public function feeAudit()
    {
        $school = School::first();
        $payments = FeePayment::with(['student'])->latest('payment_date')->take(12)->get();

        return view('fees.fee_audit', compact('school', 'payments'));
    }

    // =========================================================================
    // 15. IMPORT CENTER (/school/fee-imports)
    // =========================================================================
    public function feeImports()
    {
        $school = School::first();
        return view('fees.imports', compact('school'));
    }

    // =========================================================================
    // 16. LATE FINE RULES (/school/fee-fine-rules)
    // =========================================================================
    public function fineRules()
    {
        $school = School::first();
        $rules = DB::table('fee_fine_rules')->get();
        $groups = FeeGroup::all();

        return view('fees.fine_rules', compact('school', 'rules', 'groups'));
    }

    // =========================================================================
    // PRINTABLE RECEIPT
    // =========================================================================
    public function receipt($id)
    {
        $school = School::first();
        $payment = FeePayment::with(['student.latestEnrollment.schoolClass', 'student.latestEnrollment.section', 'student.guardian', 'allocation.feeMaster.feeType'])->findOrFail($id);

        return view('fees.receipt', compact('school', 'payment'));
    }
}
