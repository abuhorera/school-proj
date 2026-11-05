<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Income;
use App\Models\IncomeHead;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\BankAccount;
use App\Models\FeePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    // =========================================================================
    // 1. ACCOUNTS & BOOKKEEPING DASHBOARD (/school/accounting/dashboard)
    // =========================================================================
    public function dashboard()
    {
        $school = School::first();

        // 1. Stats
        $today = Carbon::today()->format('Y-m-d');
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        // Today collection (Fee payments + Incomes today)
        $todayFees = FeePayment::whereDate('payment_date', $today)->sum('amount_paid');
        $todayIncomes = Income::whereDate('date', $today)->sum('amount');
        $todayCollection = $todayFees + $todayIncomes;

        // This Month Income (Fees + other income)
        $monthFees = FeePayment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount_paid');
        $monthOtherIncome = Income::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthIncome = $monthFees + $monthOtherIncome;

        // This Month Expense
        $monthExpense = Expense::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');

        // Total Cash & Bank live ledger balance
        $totalBank = BankAccount::sum('current_balance');
        $pettyCash = 45000.00;
        $totalCashAndBank = $totalBank + $pettyCash;

        // 2. Recent Vouchers (Combines Fee receipts, Other Incomes, and Expenses)
        $recentIncomes = Income::with('incomeHead')->orderBy('date', 'desc')->limit(6)->get()->map(function ($item) {
            return (object)[
                'type' => 'Income',
                'voucher' => $item->invoice_number ?? ('RCT-INC-' . $item->id),
                'particular' => $item->name,
                'head' => $item->incomeHead->name ?? 'General Income',
                'date' => Carbon::parse($item->date)->format('d M Y'),
                'amount' => $item->amount,
                'is_debit' => false,
            ];
        });

        $recentExpenses = Expense::with('expenseHead')->orderBy('date', 'desc')->limit(6)->get()->map(function ($item) {
            return (object)[
                'type' => 'Expense',
                'voucher' => $item->invoice_number ?? $item->invoice_no ?? ('PMT-EXP-' . $item->id),
                'particular' => $item->name ?? $item->title,
                'head' => $item->expenseHead->name ?? $item->category ?? 'General Expense',
                'date' => Carbon::parse($item->date)->format('d M Y'),
                'amount' => $item->amount,
                'is_debit' => true,
            ];
        });

        $recentFees = FeePayment::with('student')->orderBy('payment_date', 'desc')->limit(6)->get()->map(function ($item) {
            return (object)[
                'type' => 'Fee Receipt',
                'voucher' => $item->invoice_no,
                'particular' => 'Tuition & Academic Fees - ' . ($item->student ? ($item->student->first_name . ' ' . $item->student->last_name) : 'Student'),
                'head' => 'Tuition Fee Head',
                'date' => Carbon::parse($item->payment_date)->format('d M Y'),
                'amount' => $item->amount_paid,
                'is_debit' => false,
            ];
        });

        $recentVouchers = $recentIncomes->concat($recentExpenses)->concat($recentFees)
            ->sortByDesc(function ($v) {
                return strtotime($v->date);
            })->values()->take(10);

        // Bank Accounts Summary
        $bankAccounts = BankAccount::all();

        return view('accounts.dashboard', compact(
            'school', 'todayCollection', 'monthIncome', 'monthExpense', 'totalCashAndBank', 
            'recentVouchers', 'bankAccounts', 'monthFees', 'monthOtherIncome'
        ));
    }

    // =========================================================================
    // 2. INCOMES (/school/accounts/incomes)
    // =========================================================================
    public function incomes(Request $request)
    {
        $school = School::first();
        $heads = IncomeHead::all();
        $bankAccounts = BankAccount::all();

        $query = Income::with(['incomeHead', 'bankAccount']);

        if ($request->filled('income_head_id')) {
            $query->where('income_head_id', $request->income_head_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('invoice_number', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $incomes = $query->orderBy('date', 'desc')->paginate(15);
        $totalAmount = $query->sum('amount');

        return view('accounts.incomes.index', compact('school', 'incomes', 'heads', 'bankAccounts', 'totalAmount'));
    }

    public function createIncome()
    {
        $school = School::first();
        $heads = IncomeHead::all();
        $bankAccounts = BankAccount::all();

        return view('accounts.incomes.create', compact('school', 'heads', 'bankAccounts'));
    }

    public function storeIncome(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'income_head_id' => 'required|exists:income_heads,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'invoice_number' => 'nullable|string',
            'school_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'description' => 'nullable|string',
        ]);

        $school = School::first();
        $inv = $request->invoice_number ?: ('INC-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5)));

        $income = Income::create([
            'school_id' => $school->id ?? 1,
            'name' => $request->name,
            'income_head_id' => $request->income_head_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'invoice_no' => $inv,
            'invoice_number' => $inv,
            'school_bank_account_id' => $request->school_bank_account_id,
            'description' => $request->description,
            'note' => $request->description,
            'payment_method' => $request->school_bank_account_id ? 'Bank Transfer' : 'Cash',
        ]);

        if ($request->filled('school_bank_account_id')) {
            BankAccount::where('id', $request->school_bank_account_id)->increment('current_balance', $request->amount);
        }

        return redirect()->route('accounts.incomes.index')->with('success', 'Income entry #' . $inv . ' recorded successfully!');
    }

    public function destroyIncome($id)
    {
        $income = Income::findOrFail($id);
        if ($income->school_bank_account_id) {
            BankAccount::where('id', $income->school_bank_account_id)->decrement('current_balance', $income->amount);
        }
        $income->delete();

        return redirect()->back()->with('success', 'Income record deleted successfully.');
    }

    // =========================================================================
    // 3. EXPENSES (/school/accounts/expenses)
    // =========================================================================
    public function expenses(Request $request)
    {
        $school = School::first();
        $heads = ExpenseHead::all();
        $bankAccounts = BankAccount::all();

        $query = Expense::with(['expenseHead', 'bankAccount']);

        if ($request->filled('expense_head_id')) {
            $query->where('expense_head_id', $request->expense_head_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('title', 'like', "%{$s}%")
                  ->orWhere('invoice_number', 'like', "%{$s}%")
                  ->orWhere('invoice_no', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(15);
        $totalAmount = $query->sum('amount');

        return view('accounts.expenses.index', compact('school', 'expenses', 'heads', 'bankAccounts', 'totalAmount'));
    }

    public function createExpense()
    {
        $school = School::first();
        $heads = ExpenseHead::all();
        $bankAccounts = BankAccount::all();

        return view('accounts.expenses.create', compact('school', 'heads', 'bankAccounts'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expense_head_id' => 'required|exists:expense_heads,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'invoice_number' => 'nullable|string',
            'school_bank_account_id' => 'nullable|exists:bank_accounts,id',
            'description' => 'nullable|string',
        ]);

        $school = School::first();
        $inv = $request->invoice_number ?: ('EXP-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5)));

        $head = ExpenseHead::find($request->expense_head_id);

        $expense = Expense::create([
            'school_id' => $school->id ?? 1,
            'name' => $request->name,
            'title' => $request->name,
            'expense_head_id' => $request->expense_head_id,
            'category' => $head ? $head->name : 'General',
            'date' => $request->date,
            'amount' => $request->amount,
            'invoice_no' => $inv,
            'invoice_number' => $inv,
            'school_bank_account_id' => $request->school_bank_account_id,
            'description' => $request->description,
            'remarks' => $request->description,
        ]);

        if ($request->filled('school_bank_account_id')) {
            BankAccount::where('id', $request->school_bank_account_id)->decrement('current_balance', $request->amount);
        }

        return redirect()->route('accounts.expenses.index')->with('success', 'Expense voucher #' . $inv . ' posted successfully!');
    }

    public function destroyExpense($id)
    {
        $expense = Expense::findOrFail($id);
        if ($expense->school_bank_account_id) {
            BankAccount::where('id', $expense->school_bank_account_id)->increment('current_balance', $expense->amount);
        }
        $expense->delete();

        return redirect()->back()->with('success', 'Expense voucher deleted successfully.');
    }

    // =========================================================================
    // 4. INCOME HEADS (/school/accounts/income-heads)
    // =========================================================================
    public function incomeHeads()
    {
        $school = School::first();
        $heads = IncomeHead::withCount('incomes')->orderBy('id', 'desc')->get();

        return view('accounts.income_heads.index', compact('school', 'heads'));
    }

    public function createIncomeHead()
    {
        $school = School::first();
        return view('accounts.income_heads.create', compact('school'));
    }

    public function storeIncomeHead(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $school = School::first();
        IncomeHead::create([
            'school_id' => $school->id ?? 1,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('accounts.income-heads.index')->with('success', 'Income head created successfully!');
    }

    public function destroyIncomeHead($id)
    {
        $head = IncomeHead::findOrFail($id);
        $head->delete();

        return redirect()->back()->with('success', 'Income head deleted.');
    }

    // =========================================================================
    // 5. EXPENSE HEADS (/school/accounts/expense-heads)
    // =========================================================================
    public function expenseHeads()
    {
        $school = School::first();
        $heads = ExpenseHead::withCount('expenses')->orderBy('id', 'desc')->get();

        return view('accounts.expense_heads.index', compact('school', 'heads'));
    }

    public function createExpenseHead()
    {
        $school = School::first();
        return view('accounts.expense_heads.create', compact('school'));
    }

    public function storeExpenseHead(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $school = School::first();
        ExpenseHead::create([
            'school_id' => $school->id ?? 1,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('accounts.expense-heads.index')->with('success', 'Expense head created successfully!');
    }

    public function destroyExpenseHead($id)
    {
        $head = ExpenseHead::findOrFail($id);
        $head->delete();

        return redirect()->back()->with('success', 'Expense head deleted.');
    }

    // =========================================================================
    // 6. BANK ACCOUNTS (/school/bank-accounts)
    // =========================================================================
    public function bankAccounts()
    {
        $school = School::first();
        $accounts = BankAccount::orderBy('id', 'desc')->get();
        $totalBalance = BankAccount::sum('current_balance');

        return view('accounts.bank_accounts.index', compact('school', 'accounts', 'totalBalance'));
    }

    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_type' => 'nullable|string',
            'branch' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        $school = School::first();
        $opening = $request->opening_balance ?: 0;

        BankAccount::create([
            'school_id' => $school->id ?? 1,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_type' => $request->account_type ?? 'Current',
            'account_number' => $request->account_number,
            'branch' => $request->branch ?? $request->branch_name,
            'ifsc_code' => $request->ifsc_code,
            'opening_balance' => $opening,
            'current_balance' => $opening,
        ]);

        return redirect()->route('accounts.bank-accounts.index')->with('success', 'Bank account registered successfully!');
    }

    public function destroyBankAccount($id)
    {
        $acc = BankAccount::findOrFail($id);
        $acc->delete();

        return redirect()->back()->with('success', 'Bank account removed.');
    }

    // =========================================================================
    // 7. DAY BOOK (/school/accounting/day-book)
    // =========================================================================
    public function dayBook(Request $request)
    {
        $school = School::first();
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        // Incomes for that date (Credit)
        $incomes = Income::whereDate('date', $date)->get()->map(function ($inc) {
            return (object)[
                'date' => $inc->date,
                'voucher' => $inc->invoice_number ?: ('INC-' . $inc->id),
                'type' => 'Income Receipt',
                'ledger' => $inc->incomeHead->name ?? 'Income',
                'narration' => $inc->name . ($inc->description ? (' - ' . $inc->description) : ''),
                'debit' => 0,
                'credit' => $inc->amount,
            ];
        });

        // Fee payments for that date (Credit)
        $fees = FeePayment::with('student')->whereDate('payment_date', $date)->get()->map(function ($fee) {
            return (object)[
                'date' => $fee->payment_date,
                'voucher' => $fee->invoice_no,
                'type' => 'Fee Receipt',
                'ledger' => 'Student Fees Collection',
                'narration' => 'Fee collected from ' . ($fee->student ? ($fee->student->first_name . ' ' . $fee->student->last_name) : 'Student'),
                'debit' => 0,
                'credit' => $fee->amount_paid,
            ];
        });

        // Expenses for that date (Debit)
        $expenses = Expense::whereDate('date', $date)->get()->map(function ($exp) {
            return (object)[
                'date' => $exp->date,
                'voucher' => $exp->invoice_number ?: $exp->invoice_no ?: ('EXP-' . $exp->id),
                'type' => 'Payment Voucher',
                'ledger' => $exp->expenseHead->name ?? $exp->category ?? 'Operational Expense',
                'narration' => ($exp->name ?: $exp->title) . ($exp->description ? (' - ' . $exp->description) : ''),
                'debit' => $exp->amount,
                'credit' => 0,
            ];
        });

        $entries = $incomes->concat($fees)->concat($expenses);
        $totalDebit = $entries->sum('debit');
        $totalCredit = $entries->sum('credit');

        return view('accounts.day_book', compact('school', 'date', 'entries', 'totalDebit', 'totalCredit'));
    }

    // =========================================================================
    // 8. CHART OF ACCOUNTS (/school/accounting/chart-of-accounts)
    // =========================================================================
    public function chartOfAccounts()
    {
        $school = School::first();
        $bankAccounts = BankAccount::all();
        $incomeHeads = IncomeHead::withCount('incomes')->get();
        $expenseHeads = ExpenseHead::withCount('expenses')->get();

        return view('accounts.chart_of_accounts', compact('school', 'bankAccounts', 'incomeHeads', 'expenseHeads'));
    }
}

