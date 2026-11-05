@extends('layouts.app')

@section('title', 'Set Staff Salary - Human Resource')

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

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-wallet"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Set Staff Compensation & Bank Particulars</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Configure individual employee basic salary, custom allowances, EPF deductions, and banking disbursement details</p>
        </div>
        <div>
            <a href="{{ route('staff.salary-templates') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200">
                <i class="fa-solid fa-file-invoice text-slate-500"></i>
                <span>Manage Salary Templates</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('staff.salary') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Filter by Department</label>
                <select name="department" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                    <option value="{{ $d->name }}" {{ $dept == $d->name ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-filter"></i>
                    <span>Apply Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Staff Salary Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Employee Salary Configuration Ledger ({{ $staff->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Employee</th>
                        <th class="py-3 px-4">Department & Role</th>
                        <th class="py-3 px-4">Basic Pay</th>
                        <th class="py-3 px-4">Allowances</th>
                        <th class="py-3 px-4">Deductions</th>
                        <th class="py-3 px-4">Net Take-Home</th>
                        <th class="py-3 px-4">Payment Method</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($staff as $index => $s)
                    @php
                        $sal = $s->salary;
                        $basic = $sal ? $sal->basic_salary : $s->basic_salary;
                        $allw = $sal ? $sal->total_allowances : 0;
                        $ded = $sal ? $sal->total_deductions : 0;
                        $net = $sal ? $sal->net_salary : $basic;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">{{ $s->first_name }} {{ $s->last_name }}</div>
                            <span class="text-[10px] text-slate-400 font-mono">ID: {{ $s->employee_id }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-800">{{ $s->designation }}</div>
                            <span class="text-[10px] text-pink-600 bg-pink-50 px-1.5 py-0.5 rounded font-semibold">{{ $s->department }}</span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            ₹{{ number_format($basic, 2) }}
                        </td>
                        <td class="py-3 px-4 text-emerald-700 font-semibold font-mono">
                            +₹{{ number_format($allw, 2) }}
                        </td>
                        <td class="py-3 px-4 text-rose-600 font-semibold font-mono">
                            -₹{{ number_format($ded, 2) }}
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900 font-mono text-sm">
                            ₹{{ number_format($net, 2) }}
                        </td>
                        <td class="py-3 px-4 text-[11px] text-slate-500">
                            <div>{{ $sal->payment_method ?? 'Bank Transfer' }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $sal->bank_account_no ?? 'A/C Not Set' }}</div>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="openSalaryModal('{{ $s->id }}', '{{ addslashes($s->first_name . ' ' . $s->last_name) }}', '{{ $basic }}', '{{ $allw }}', '{{ $ded }}', '{{ $sal->bank_account_no ?? '' }}', '{{ $sal->bank_name ?? '' }}')" class="px-3 py-1 bg-pink-50 hover:bg-pink-100 text-pink-700 font-bold rounded-lg text-[11px] transition inline-flex items-center space-x-1">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                <span>Configure</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">No staff members found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Configure Staff Salary -->
<div id="salaryConfigModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Configure Staff Compensation</h3>
                <p id="modalStaffName" class="text-[11px] text-pink-600 font-semibold"></p>
            </div>
            <button onclick="document.getElementById('salaryConfigModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="salaryConfigForm" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Basic Pay (₹) <span class="text-rose-500">*</span></label>
                <input type="number" id="modalBasic" name="basic_salary" required class="w-full text-xs font-bold rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Total Allowances (₹)</label>
                    <input type="number" id="modalAllw" name="total_allowances" value="0" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Total Deductions (₹)</label>
                    <input type="number" id="modalDed" name="total_deductions" value="0" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Disbursement Bank Name</label>
                <input type="text" id="modalBank" name="bank_name" placeholder="e.g. State Bank of India" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Bank Account Number</label>
                <input type="text" id="modalAcc" name="bank_account_no" placeholder="e.g. 30894729103" class="w-full text-xs font-mono rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('salaryConfigModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Compensation</button>
            </div>
        </form>
    </div>
</div>

<script>
function openSalaryModal(staffId, staffName, basic, allw, ded, acc, bank) {
    document.getElementById('salaryConfigForm').action = "{{ url('/school/staff-salary') }}/" + staffId;
    document.getElementById('modalStaffName').textContent = staffName;
    document.getElementById('modalBasic').value = basic;
    document.getElementById('modalAllw').value = allw;
    document.getElementById('modalDed').value = ded;
    document.getElementById('modalAcc').value = acc;
    document.getElementById('modalBank').value = bank || 'State Bank of India';
    document.getElementById('salaryConfigModal').classList.remove('hidden');
}
</script>
@endsection

