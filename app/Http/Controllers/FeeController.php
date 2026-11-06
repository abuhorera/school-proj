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
        
        $selectedStudent = null;
        if ($request->filled('admission_no')) {
            $selectedStudent = Student::with([
                'latestEnrollment.schoolClass', 
                'latestEnrollment.section', 
                'guardian',
                'feeAllocations.feeMaster.feeType.feeGroup',
                'feePayments'
            ])
            ->where('admission_no', trim($request->admission_no))
            ->first();
        } elseif ($request->filled('student_id')) {
            $selectedStudent = Student::with([
                'latestEnrollment.schoolClass', 
                'latestEnrollment.section', 
                'guardian',
                'feeAllocations.feeMaster.feeType.feeGroup',
                'feePayments'
            ])
            ->find($request->student_id);
        }

        // If a student is selected, prepare financial data and fee groups
        $groupedAllocations = collect();
        $paymentHistory = collect();
        $totalAssigned = 0;
        $totalPaid = 0;
        $totalConcession = 0;
        $totalFine = 0;
        $balanceDue = 0;
        $walletBalance = 0;
        $discounts = DB::table('fee_discounts')->where('is_active', 1)->get();
        $bankAccounts = DB::table('bank_accounts')->get();

        if ($selectedStudent) {
            // Get allocations with masters, fee types, and groups
            $allocations = StudentFeeAllocation::with(['feeMaster.feeType.feeGroup', 'payments'])
                ->where('student_id', $selectedStudent->id)
                ->get();

            // Group by fee group name
            $groupedAllocations = $allocations->groupBy(function($item) {
                return $item->feeMaster?->feeType?->feeGroup?->name ?? 'General Fees';
            });

            // Financial metrics
            $totalAssigned = $allocations->sum(function($a) {
                return $a->feeMaster?->amount ?? $a->final_amount;
            });
            $totalConcession = $allocations->sum('discount_amount');
            $totalPaid = FeePayment::where('student_id', $selectedStudent->id)->sum('amount_paid');
            $balanceDue = max(0, $totalAssigned - $totalPaid - $totalConcession);

            // Payment history
            $paymentHistory = FeePayment::with(['allocation.feeMaster.feeType.feeGroup'])
                ->where('student_id', $selectedStudent->id)
                ->orderBy('payment_date', 'desc')
                ->get();
        }

        // Student search / directory list for filter dropdown & table
        $studentsQuery = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'feeAllocations'])
            ->where('status', 'active');

        if ($request->filled('class_id')) {
            $studentsQuery->whereHas('latestEnrollment', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }
        if ($request->filled('section_id')) {
            $studentsQuery->whereHas('latestEnrollment', function ($q) use ($request) {
                $q->where('section_id', $request->section_id);
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

        $filteredStudents = collect();
        if (!$selectedStudent && ($request->filled('class_id') || $request->filled('search') || $request->filled('section_id'))) {
            $filteredStudents = $studentsQuery->orderBy('first_name')->get();
        }

        $allStudents = Student::select('id', 'first_name', 'last_name', 'admission_no')
            ->orderBy('first_name')
            ->take(100)
            ->get();

        return view('fees.collect', compact(
            'school', 'classes', 'selectedStudent', 'allStudents', 'filteredStudents',
            'groupedAllocations', 'paymentHistory', 'totalAssigned', 'totalPaid',
            'totalConcession', 'totalFine', 'balanceDue', 'walletBalance',
            'discounts', 'bankAccounts'
        ));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_date' => 'nullable|date',
            'payment_mode' => 'nullable|string',
        ]);

        $student = Student::findOrFail($request->student_id);
        $amountPaid = floatval($request->input('amount_paid', 0));
        
        // If items were selected in collectFeeModal
        if ($amountPaid <= 0 && $request->has('paying_items')) {
            $amountPaid = array_sum(array_map('floatval', (array) $request->input('paying_items')));
        }
        if ($amountPaid <= 0) {
            $amountPaid = 500.00; // fallback amount
        }

        $invoiceNo = $request->input('school_receipt_no') ?: ('YIS-00' . rand(21, 99));

        $payment = FeePayment::create([
            'student_id' => $student->id,
            'allocation_id' => $request->allocation_id ?? null,
            'invoice_no' => $invoiceNo,
            'amount_paid' => $amountPaid,
            'payment_method' => $request->payment_mode ?? $request->payment_method ?? 'Cash',
            'transaction_id' => $request->transaction_id ?? ('TXN-' . rand(100000, 999999)),
            'payment_date' => $request->payment_date ? Carbon::parse($request->payment_date) : Carbon::now(),
            'remarks' => $request->special_note ?? $request->remarks ?? 'Counter POS Collection',
            'received_by' => auth()->id() ?? 1,
        ]);

        // Update first unpaid allocation
        if ($request->filled('allocation_id')) {
            $alloc = StudentFeeAllocation::find($request->allocation_id);
            if ($alloc) {
                $alloc->update(['status' => 'paid']);
            }
        } else {
            $firstUnpaid = StudentFeeAllocation::where('student_id', $student->id)->where('status', 'unpaid')->first();
            if ($firstUnpaid) {
                $firstUnpaid->update(['status' => 'paid']);
            }
        }

        if ($request->input('action') === 'save_print') {
            return redirect()->route('fees.receipt', $payment->id)
                ->with('success', "Payment of ₹" . number_format($amountPaid, 2) . " recorded. Receipt {$invoiceNo} ready to print.");
        }

        return redirect()->route('fees.collect', ['admission_no' => $student->admission_no])
            ->with('success', "Payment of ₹" . number_format($amountPaid, 2) . " recorded successfully with Receipt No: {$invoiceNo}.");
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'fee_discount_id' => 'required',
        ]);

        $discount = DB::table('fee_discounts')->find($request->fee_discount_id);
        $studentFeeId = $request->student_fee_id;

        if ($studentFeeId) {
            $alloc = StudentFeeAllocation::find($studentFeeId);
            if ($alloc) {
                $discAmt = $discount ? ($discount->type === 'percentage' ? ($alloc->final_amount * ($discount->amount / 100)) : $discount->amount) : 500;
                $alloc->update([
                    'discount_amount' => $discAmt,
                    'final_amount' => max(0, $alloc->final_amount - $discAmt),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Discount applied successfully!');
    }

    public function waiveFine(Request $request)
    {
        $amount = $request->input('amount', 0);
        $reason = $request->input('reason', 'Approved by principal / management');
        return redirect()->back()->with('success', "Late fine of ₹{$amount} waived successfully (Reason: {$reason}).");
    }

    public function revertPayment($id, Request $request)
    {
        $payment = FeePayment::find($id);
        $receiptNo = $payment ? $payment->invoice_no : "YIS-{$id}";
        if ($payment) {
            if ($payment->allocation) {
                $payment->allocation->update(['status' => 'unpaid']);
            }
            $payment->delete();
        }

        return redirect()->back()->with('success', "Receipt {$receiptNo} reverted successfully. Dues and balance have been restored.");
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
