@extends('layouts.app')

@section('title', 'Staff Directory - Human Resource')

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

    <!-- Header & Action Row -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Human Resource • Staff Directory</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Manage institutional faculty, administrative personnel, compensation, and service records</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('staff.create') }}" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Add New Staff Member</span>
            </a>
        </div>
    </div>

    <!-- 4 Overview Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Employees</span>
            <h4 class="text-2xl font-black text-slate-800 mt-1">{{ $totalStaff }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Active On-Duty</span>
            <h4 class="text-2xl font-black text-emerald-600 mt-1">{{ $activeStaff }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Departments</span>
            <h4 class="text-2xl font-black text-blue-600 mt-1">{{ $departments->count() }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Designations</span>
            <h4 class="text-2xl font-black text-purple-600 mt-1">{{ $designations->count() }}</h4>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('staff.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Search Staff</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, ID, designation..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Department</label>
                <select name="department" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                    <option value="{{ $d->name }}" {{ request('department') == $d->name ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive / On Leave</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter Staff</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Staff Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Staff Directory Roster ({{ $staff->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Staff Member</th>
                        <th class="py-3 px-4">Staff ID</th>
                        <th class="py-3 px-4">Department</th>
                        <th class="py-3 px-4">Designation</th>
                        <th class="py-3 px-4">Phone / Email</th>
                        <th class="py-3 px-4">Basic Pay</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($staff as $index => $s)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-700 font-bold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr($s->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $s->first_name }} {{ $s->last_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $s->qualification ?? 'Faculty' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">
                            {{ $s->employee_id }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700">
                                {{ $s->department }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">
                            {{ $s->designation }}
                        </td>
                        <td class="py-3 px-4 text-[11px] text-slate-500">
                            <div>{{ $s->phone ?? 'N/A' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $s->email }}</div>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            ₹{{ number_format($s->basic_salary, 2) }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $s->status == 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('staff.salary') }}" class="p-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition" title="Salary Config">
                                    <i class="fa-solid fa-wallet text-xs"></i>
                                </a>
                                <a href="{{ route('staff.id-card') }}" class="p-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-lg transition" title="ID Card">
                                    <i class="fa-solid fa-id-card text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-users text-3xl text-slate-300 mb-2"></i>
                            <p>No staff members found matching criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staff->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $staff->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
