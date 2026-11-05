@extends('layouts.app')

@section('title', 'Staff Daily Attendance Register')

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
                    <i class="fa-solid fa-calendar-check"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Staff Daily Attendance Register</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Record and monitor daily faculty and staff check-ins: Present (P), Late (L), Absent (A), Half-Day (H)</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="markAllPresent()" class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-check-double text-xs"></i>
                <span>Mark All Present</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('staff.attendance') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Attendance Date</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 font-bold text-slate-800 focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Department</label>
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
                    <span>Load Attendance Sheet</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance Sheet Form -->
    <form action="{{ route('staff.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-xs">Attendance Sheet for: <span class="font-mono text-pink-600">{{ date('d M, Y', strtotime($date)) }}</span></h3>
                    <p class="text-[11px] text-slate-400">{{ $staffList->count() }} Employees Listed</p>
                </div>
                <div class="flex items-center space-x-2 text-[11px] text-slate-500">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Present (P)
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500"></span> Late (L)
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-rose-500"></span> Absent (A)
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span> Half Day (H)
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Staff Member</th>
                            <th class="py-3 px-4">Department & Role</th>
                            <th class="py-3 px-4">Attendance Status Marking</th>
                            <th class="py-3 px-4 text-right">In / Out Log</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($staffList as $index => $st)
                        @php
                            $attRecord = $attendances->get($st->id);
                            $curStatus = $attRecord ? $attRecord->status : 'P';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 text-xs">{{ $st->first_name }} {{ $st->last_name }}</div>
                                <span class="text-[10px] text-slate-400 font-mono">ID: {{ $st->employee_id }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-800">{{ $st->designation }}</div>
                                <span class="text-[10px] text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded font-semibold">{{ $st->department }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    @foreach(['P' => ['label' => 'Present', 'color' => 'emerald'], 'L' => ['label' => 'Late', 'color' => 'amber'], 'A' => ['label' => 'Absent', 'color' => 'rose'], 'H' => ['label' => 'Half Day', 'color' => 'blue']] as $code => $meta)
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="attendance[{{ $st->id }}]" 
                                               value="{{ $code }}" 
                                               {{ $curStatus == $code ? 'checked' : '' }} 
                                               class="peer sr-only att-radio-{{ $code }}">
                                        <span class="px-2.5 py-1 text-[10px] font-black rounded-lg border transition peer-checked:bg-{{ $meta['color'] }}-600 peer-checked:text-white peer-checked:border-{{ $meta['color'] }}-600 bg-white text-slate-600 border-slate-200 hover:bg-slate-100">
                                            {{ $code }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-[11px] text-slate-500">
                                {{ $attRecord && $attRecord->in_time ? $attRecord->in_time : '08:30' }} - {{ $attRecord && $attRecord->out_time ? $attRecord->out_time : '15:30' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">No staff members found for attendance.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($staffList->count() > 0)
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">Ensure all attendance markings are verified before saving.</span>
                <button type="submit" class="px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Daily Attendance</span>
                </button>
            </div>
            @endif
        </div>
    </form>
</div>

<script>
function markAllPresent() {
    const presentRadios = document.querySelectorAll('.att-radio-P');
    presentRadios.forEach(r => r.checked = true);
}
</script>
@endsection

