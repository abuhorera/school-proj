@extends('layouts.app')

@section('title', 'Payroll Management - Human Resource')

@section('content')
<div class="space-y-6">
    @include('staff.partials.subnav')

    <!-- Flash message alerts -->
    @if(session('success'))
    <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Header bar with Generate Action -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-money-check-dollar"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Monthly Payroll & Salary Disbursement</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Generate monthly payroll runs, compute allowances & statutory deductions, and manage employee disbursements</p>
        </div>
        <div>
            <button onclick="document.getElementById('generatePayrollModal').classList.remove('hidden')" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Generate Monthly Payroll</span>
            </button>
        </div>
    </div>

    <!-- Payroll Runs Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Payroll Cycles & History ({{ $payrolls->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Month & Year</th>
                        <th class="py-3 px-4">Payslips Generated</th>
                        <th class="py-3 px-4">Gross Total</th>
                        <th class="py-3 px-4">Deductions</th>
                        <th class="py-3 px-4">Net Total</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Generated On</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($payrolls as $index => $p)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                            <i class="fa-solid fa-calendar-day text-pink-500 mr-1.5"></i>
                            {{ $p->month_year }}
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $p->payslips_count }} <span class="text-slate-400 font-normal">slips</span>
                        </td>
                        <td class="py-3 px-4 text-slate-600 font-mono">
                            ₹{{ number_format($p->gross_amount, 2) }}
                        </td>
                        <td class="py-3 px-4 text-rose-600 font-mono font-semibold">
                            -₹{{ number_format($p->total_deductions, 2) }}
                        </td>
                        <td class="py-3 px-4 font-bold text-emerald-700 font-mono text-sm">
                            ₹{{ number_format($p->net_total, 2) }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $p->status == 'Disbursed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-400">
                            {{ $p->created_at ? $p->created_at->format('d M, Y') : 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <form action="{{ route('staff.payroll.toggle', $p->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold transition">
                                    {{ $p->status == 'Generated' ? 'Disburse' : 'Revert' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-money-check-dollar text-3xl text-slate-300 mb-2"></i>
                            <p>No payroll runs generated yet.</p>
                            <button onclick="document.getElementById('generatePayrollModal').classList.remove('hidden')" class="mt-2 text-pink-600 font-bold text-xs hover:underline">Generate First Payroll</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Generate Monthly Payroll -->
<div id="generatePayrollModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Generate Monthly Payroll</h3>
            <button onclick="document.getElementById('generatePayrollModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('staff.payroll.generate') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Select Month <span class="text-rose-500">*</span></label>
                <select name="month" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                    <option value="{{ $m }}" {{ $m == date('F') ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Select Year <span class="text-rose-500">*</span></label>
                <input type="number" name="year" value="{{ date('Y') }}" min="2020" max="2035" required class="w-full text-xs font-bold rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div class="p-3 bg-pink-50 rounded-xl border border-pink-100 text-xs text-pink-900">
                <i class="fa-solid fa-circle-info mr-1 text-pink-600"></i>
                This will automatically compute gross allowances, deductions, and net salary for all <strong>{{ $staffCount }} active employees</strong>.
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('generatePayrollModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">Generate Payroll</button>
            </div>
        </form>
    </div>
</div>
@endsection

