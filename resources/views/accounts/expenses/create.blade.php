@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Accounting Subnav -->
    @include('accounts.partials.subnav')

    <!-- Header -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-minus-circle text-rose-600"></i>
                <span>Add New Expense Voucher</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Post an operational expenditure or vendor payment voucher</p>
        </div>
        <a href="{{ route('accounts.expenses.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-xs transition">
            &larr; Back to Expenses
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="POST" action="{{ route('accounts.expenses.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-1">
                    <label class="font-bold text-slate-700">Expense Title / Vendor Bill <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Electricity Bill Substation Oct 2026" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-rose-500">
                </div>

                <!-- Expense Head -->
                <div class="space-y-1">
                    <label class="font-bold text-slate-700">Expense Head <span class="text-red-500">*</span></label>
                    <select name="expense_head_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-rose-500">
                        <option value="">Select Expense Head</option>
                        @foreach($heads as $h)
                        <option value="{{ $h->id }}">{{ $h->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Date -->
                <div class="space-y-1">
                    <label class="font-bold text-slate-700">Payment Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-rose-500">
                </div>

                <!-- Amount -->
                <div class="space-y-1">
                    <label class="font-bold text-slate-700">Amount (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="1" name="amount" required placeholder="12500.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold text-rose-600 focus:outline-rose-500">
                </div>

                <!-- Invoice Number -->
                <div class="space-y-1">
                    <label class="font-bold text-slate-700">Bill / Voucher No</label>
                    <input type="text" name="invoice_number" placeholder="Leave empty for auto-generation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono focus:outline-rose-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Disbursed From Bank Account -->
                <div class="space-y-1">
                    <label class="font-bold text-slate-700">Paid From Account <span class="text-slate-400 font-normal">(Optional, defaults to Petty Cash)</span></label>
                    <select name="school_bank_account_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-rose-500">
                        <option value="">School Petty Cash (Cash In Hand)</option>
                        @foreach($bankAccounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->bank_name }} - {{ $acc->account_name }} (•••• {{ substr($acc->account_number, -4) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Attachment -->
                <div class="space-y-1">
                    <label class="font-bold text-slate-700">Bill Invoice Copy <span class="text-slate-400 font-normal">(PDF / JPG / Scan)</span></label>
                    <input type="file" name="attachment" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-slate-200 file:text-slate-700">
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label class="font-bold text-slate-700">Description / Memo</label>
                <textarea name="description" rows="3" placeholder="Enter vendor name, payment reference number, or purchase approval note..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-rose-500"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <a href="{{ route('accounts.expenses.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition">Cancel</a>
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-6 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Post Expense Voucher</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

