@extends('layouts.app')

@section('title', 'Record — ' . $meeting->title)

@section('content')
<div class="space-y-6">
    @include('ptm.partials.subnav')

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
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-pen-nib"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Record — {{ $meeting->title }}</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1 flex flex-wrap items-center gap-2">
                <span><i class="fa-solid fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-clock mr-1"></i>{{ $meeting->start_time ?? '09:00' }} - {{ $meeting->end_time ?? '13:00' }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-location-dot mr-1"></i>{{ $meeting->venue ?? 'School Campus' }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('ptm.meetings.show', $meeting->id) }}" class="px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
                View Roster
            </a>
            <button type="button" onclick="document.getElementById('recordForm').submit()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Remarks</span>
            </button>
        </div>
    </div>

    <!-- Attendance & Remarks Form Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <form id="recordForm" action="{{ route('ptm.meetings.save-record', $meeting->id) }}" method="POST">
            @csrf

            <div class="p-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-xs">Conference Roster ({{ $attendances->count() }} Students)</h3>
                    <p class="text-[11px] text-slate-400">Mark parent check-in and input holistic progress remarks</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="markAll('Present')" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold transition border border-emerald-200">
                        Mark All Present
                    </button>
                    <button type="button" onclick="markAll('Pending')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold transition border border-slate-200">
                        Reset All
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4 w-56">Student</th>
                            <th class="py-3 px-4 w-32">Adm / Roll</th>
                            <th class="py-3 px-4 w-64">Attendance Status</th>
                            <th class="py-3 px-4">Performance, Behavior & Ratings</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($attendances as $att)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Student Info -->
                            <td class="py-3 px-4 align-top">
                                <div class="font-bold text-slate-900 text-xs">
                                    {{ $att->student->first_name ?? 'Student' }} {{ $att->student->last_name ?? '' }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                    Guardian: {{ $att->student->guardian->name ?? ($att->student->father_name ?? 'Parent') }}
                                </div>
                                <div class="text-[10px] text-amber-700 font-mono mt-1 font-semibold">
                                    Slot: {{ $att->slot_time ?? '09:00 AM' }}
                                </div>
                            </td>

                            <!-- Adm / Roll -->
                            <td class="py-3 px-4 align-top font-mono text-[11px] text-slate-500">
                                <div>{{ $att->student->admission_no ?? 'YIS-00' }}</div>
                                <div class="text-[10px] text-slate-400">Roll: {{ $att->student->roll_no ?? '-' }}</div>
                            </td>

                            <!-- Attendance Radio Status -->
                            <td class="py-3 px-4 align-top">
                                <div class="grid grid-cols-2 gap-1.5 w-60">
                                    @foreach([
                                        'Pending' => ['color' => 'slate', 'label' => 'Pending'],
                                        'Present' => ['color' => 'emerald', 'label' => 'Present'],
                                        'Absent' => ['color' => 'rose', 'label' => 'Absent'],
                                        'Rescheduled' => ['color' => 'purple', 'label' => 'Rescheduled'],
                                    ] as $code => $meta)
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="rows[{{ $att->id }}][attendance_status]" 
                                               value="{{ $code }}" 
                                               {{ $att->attendance_status == $code ? 'checked' : '' }} 
                                               class="peer sr-only att-radio-{{ $code }}">
                                        <span class="block px-2.5 py-1 text-[10px] font-bold text-center rounded-lg border transition peer-checked:bg-{{ $meta['color'] }}-600 peer-checked:text-white peer-checked:border-{{ $meta['color'] }}-600 bg-white text-slate-600 border-slate-200 hover:bg-slate-100">
                                            {{ $meta['label'] }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Detailed Remarks & Star Rating -->
                            <td class="py-3 px-4 align-top space-y-2">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Performance</label>
                                        <textarea name="rows[{{ $att->id }}][performance_remark]" rows="2" placeholder="Academic strengths, learning progress..." class="w-full text-[11px] rounded-lg border border-slate-200 px-2 py-1 focus:ring-1 focus:ring-amber-500">{{ $att->performance_remark }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Attendance / Punctuality</label>
                                        <textarea name="rows[{{ $att->id }}][attendance_remark]" rows="2" placeholder="Class attendance & punctuality notes..." class="w-full text-[11px] rounded-lg border border-slate-200 px-2 py-1 focus:ring-1 focus:ring-amber-500">{{ $att->attendance_remark }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Behaviour & Conduct</label>
                                        <textarea name="rows[{{ $att->id }}][behaviour_remark]" rows="2" placeholder="Classroom discipline, peer teamwork..." class="w-full text-[11px] rounded-lg border border-slate-200 px-2 py-1 focus:ring-1 focus:ring-amber-500">{{ $att->behaviour_remark }}</textarea>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center space-x-2">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase">Overall Rating:</label>
                                        <select name="rows[{{ $att->id }}][overall_rating]" class="text-[11px] font-bold rounded-lg border border-slate-200 px-2 py-0.5 focus:ring-1 focus:ring-amber-500 bg-white text-amber-700">
                                            <option value="">— Rating —</option>
                                            <option value="5" {{ $att->overall_rating == 5 ? 'selected' : '' }}>★ 5 / 5 (Outstanding)</option>
                                            <option value="4" {{ $att->overall_rating == 4 ? 'selected' : '' }}>★ 4 / 5 (Very Good)</option>
                                            <option value="3" {{ $att->overall_rating == 3 ? 'selected' : '' }}>★ 3 / 5 (Good)</option>
                                            <option value="2" {{ $att->overall_rating == 2 ? 'selected' : '' }}>★ 2 / 5 (Needs Support)</option>
                                            <option value="1" {{ $att->overall_rating == 1 ? 'selected' : '' }}>★ 1 / 5 (Critical)</option>
                                        </select>
                                    </div>

                                    <label class="flex items-center space-x-1.5 cursor-pointer text-[10px] text-slate-500">
                                        <input type="checkbox" name="rows[{{ $att->id }}][visible_to_parent]" value="1" {{ $att->visible_to_parent ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                        <span>Visible to parent in portal</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">No students found on this meeting roster.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400">Don't forget to save changes before leaving the page</span>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Attendance & Remarks</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function markAll(status) {
    const radios = document.querySelectorAll('.att-radio-' + status);
    radios.forEach(r => r.checked = true);
}
</script>
@endsection

