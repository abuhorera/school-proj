@extends('layouts.app')

@section('title', 'Leave Types Master - Human Resource')

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
                    <i class="fa-solid fa-calendar-xmark"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Leave Types & Annual Quota Master</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Configure statutory leave entitlements, paid vs unpaid categories, and carry-forward rules</p>
        </div>
        <div>
            <button onclick="document.getElementById('addLeaveTypeModal').classList.remove('hidden')" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add Leave Type</span>
            </button>
        </div>
    </div>

    <!-- Leave Types Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Configured Leave Policies ({{ $leaveTypes->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Yearly Quota</th>
                        <th class="py-3 px-4">Accrual Frequency</th>
                        <th class="py-3 px-4">Carry Forward</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($leaveTypes as $index => $lt)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                            {{ $lt->name }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $lt->type == 'Paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $lt->type }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $lt->yearly_quota }} days
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-[11px]">
                            {{ $lt->accrual_frequency }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $lt->carry_forward ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $lt->carry_forward ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-[11px] text-slate-500 max-w-xs">
                            {{ $lt->description ?? 'Statutory leave' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <form action="{{ route('staff.leave-types.destroy', $lt->id) }}" method="POST" onsubmit="return confirm('Delete this leave type?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Leave Type">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">No leave types configured.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Leave Type -->
<div id="addLeaveTypeModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add New Leave Policy</h3>
            <button onclick="document.getElementById('addLeaveTypeModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('staff.leave-types.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Leave Policy Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Compensatory Off (Comp-Off)" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category <span class="text-rose-500">*</span></label>
                    <select name="type" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                        <option value="Paid">Paid Leave</option>
                        <option value="Unpaid">Unpaid Leave</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Yearly Quota (Days) <span class="text-rose-500">*</span></label>
                    <input type="number" name="yearly_quota" value="12" min="1" required class="w-full text-xs font-bold rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
            </div>
            <div>
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="carry_forward" value="1" class="rounded text-pink-600 focus:ring-pink-500">
                    <span>Allow Unused Balance to Carry Forward</span>
                </label>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description / Rules</label>
                <textarea name="description" rows="2" placeholder="Eligibility and documentation requirements..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addLeaveTypeModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Policy</button>
            </div>
        </form>
    </div>
</div>
@endsection

