@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <!-- Accounting Subnav -->
    @include('accounts.partials.subnav')

    <!-- Header -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-plus-circle text-emerald-600"></i>
                <span>Add Income Head</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Define a revenue categorization head for audit classification</p>
        </div>
        <a href="{{ route('accounts.income-heads.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-xs transition">
            &larr; Back to Heads
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="POST" action="{{ route('accounts.income-heads.store') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Name -->
            <div class="space-y-1">
                <label class="font-bold text-slate-700">Head Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Science Exhibition Stall Fees, Uniform Counter Royalty" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-emerald-500">
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label class="font-bold text-slate-700">Description</label>
                <textarea name="description" rows="3" placeholder="Provide audit details on what types of payments qualify under this head..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-emerald-500"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <a href="{{ route('accounts.income-heads.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition">Cancel</a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Save Income Head</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

