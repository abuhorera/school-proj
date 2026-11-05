@extends('layouts.app')

@section('title', 'Staff ID Cards - Human Resource')

@section('content')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableBadgeContainer, #printableBadgeContainer * {
        visibility: visible;
    }
    #printableBadgeContainer {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 10px;
    }
    .no-print {
        display: none !important;
    }
    .badge-card {
        page-break-inside: avoid;
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #cbd5e1 !important;
    }
}
</style>

<div class="space-y-6">
    <div class="no-print">
        @include('staff.partials.subnav')
    </div>

    <!-- Header bar -->
    <div class="no-print bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-address-card"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Staff Identity Cards</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Official employee identification badges with institutional crest, QR barcode, and verified staff credentials</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-print"></i>
                <span>Print Badges</span>
            </button>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="no-print bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form method="GET" action="{{ route('staff.id-card') }}" class="flex flex-wrap items-center gap-2">
            <label class="text-xs font-bold text-slate-600 mr-1"><i class="fa-solid fa-filter text-slate-400 mr-1"></i>Filter Department:</label>
            <select name="department" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 px-3 py-1.5 focus:ring-2 focus:ring-pink-500 bg-slate-50 font-medium">
                <option value="">All Departments ({{ $departments->count() }})</option>
                @foreach($departments as $d)
                    <option value="{{ $d->name }}" {{ $dept == $d->name ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
            @if($dept)
                <a href="{{ route('staff.id-card') }}" class="text-xs text-rose-600 hover:underline ml-2">Clear Filter</a>
            @endif
        </form>
        <span class="text-xs text-slate-400 font-mono">Showing {{ $staff->count() }} active staff members</span>
    </div>

    <!-- ID Cards Grid -->
    <div id="printableBadgeContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($staff as $st)
        <div class="badge-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between transition hover:shadow-md">
            <!-- Badge Header -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-pink-900 text-white p-3.5 text-center relative">
                <div class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-xs text-white mx-auto flex items-center justify-center mb-1 text-sm border border-white/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h4 class="font-black text-xs tracking-wider uppercase truncate">{{ $school->name ?? 'Greenwood High School' }}</h4>
                <p class="text-[9px] text-pink-200 uppercase font-bold tracking-widest mt-0.5">Staff Identity Card</p>
            </div>

            <!-- Employee Photo & Core Info -->
            <div class="p-4 text-center flex-1 flex flex-col items-center">
                <div class="w-20 h-20 rounded-xl bg-slate-100 border-2 border-pink-500/30 overflow-hidden shadow-inner flex items-center justify-center mb-3">
                    @if($st->photo)
                        <img src="{{ asset('storage/' . $st->photo) }}" alt="{{ $st->first_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50 text-slate-400">
                            <i class="fa-solid fa-user-tie text-2xl text-slate-300"></i>
                            <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">{{ substr($st->first_name, 0, 1) }}{{ substr($st->last_name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <h3 class="font-black text-slate-900 text-sm leading-tight">{{ $st->first_name }} {{ $st->last_name }}</h3>
                <p class="text-[11px] font-bold text-pink-600 mt-0.5">{{ $st->designation ?? 'Staff Member' }}</p>
                <span class="mt-1 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    {{ $st->department ?? 'General' }}
                </span>

                <!-- Employee Credentials Table -->
                <div class="w-full mt-4 pt-3 border-t border-slate-100 text-left space-y-1.5 text-[10px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold uppercase">Staff ID:</span>
                        <span class="font-mono font-bold text-slate-800">{{ $st->employee_id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold uppercase">Joined:</span>
                        <span class="font-mono text-slate-700">{{ $st->date_of_joining ? \Carbon\Carbon::parse($st->date_of_joining)->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold uppercase">Phone:</span>
                        <span class="font-mono text-slate-700">{{ $st->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold uppercase">Blood Group:</span>
                        <span class="font-bold text-rose-600">{{ $st->blood_group ?? 'O+' }}</span>
                    </div>
                </div>
            </div>

            <!-- Badge Footer & Barcode -->
            <div class="bg-slate-50 px-4 py-3 border-t border-slate-100 flex flex-col items-center">
                <!-- Barcode Simulation -->
                <div class="w-full flex items-center justify-center space-x-0.5 h-6 px-2 bg-white rounded border border-slate-200 overflow-hidden">
                    @for($i = 0; $i < 24; $i++)
                        <span class="h-full bg-slate-900 {{ $i % 3 == 0 ? 'w-1' : ($i % 2 == 0 ? 'w-0.5' : 'w-1.5') }}"></span>
                    @endfor
                </div>
                <span class="font-mono text-[9px] text-slate-500 tracking-widest mt-1">{{ $st->employee_id }}</span>

                <div class="w-full flex justify-between items-end mt-2 pt-2 border-t border-slate-200/60 text-[8px] text-slate-400">
                    <span>Authorized Signatory</span>
                    <span class="font-mono">VALID: 2026-2027</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-100 p-12 text-center text-slate-400">
            <i class="fa-solid fa-address-card text-4xl text-slate-200 mb-3"></i>
            <p class="font-bold">No staff records found for ID card generation.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

