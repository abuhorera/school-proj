@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Room Plan',
        'pageSubtitle' => 'Allocate permanent home classrooms to each academic section.'
    ])

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <!-- 4 KPI Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Sections With Home Room</span>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $assignedCount }} / {{ $totalSectionsCount }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Assigned classrooms</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Still Need a Room</span>
            <div class="text-2xl font-bold text-amber-600">{{ $unassignedCount }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Floating sections</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Capacity Deficit</span>
            <div class="text-2xl font-bold text-emerald-600">0</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Over room's seats</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Total Students</span>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalStudents }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Enrolled on campus</p>
        </div>
    </div>

    <!-- Home Room Plan Matrix Form -->
    <form method="POST" action="{{ route('classrooms.plan.save') }}">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Academic Sections & Home Classrooms</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Assign base rooms where students assemble for roll call and standard lectures.</p>
                </div>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition shadow-sm">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Save Room Plan
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5">#</th>
                            <th class="px-4 py-3.5">Section</th>
                            <th class="px-4 py-3.5">Students</th>
                            <th class="px-4 py-3.5">Home Room</th>
                            <th class="px-4 py-3.5">Capacity Fit</th>
                            <th class="px-4 py-3.5 text-right">Temporary Move</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($sections as $index => $sec)
                            @php
                                $assignedRoom = $rooms->where('home_section_id', $sec->id)->first();
                                $studentCount = $sec->students->count() ?: 25;
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                                <td class="px-4 py-3.5 text-slate-400 font-medium">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <strong class="text-slate-900 dark:text-white text-sm block">
                                        {{ $sec->schoolClass->name ?? 'Class' }} — {{ $sec->name }}
                                    </strong>
                                </td>
                                <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ $studentCount }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <select name="rooms[{{ $sec->id }}]" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-1.5 px-3 focus:ring-orange-500">
                                        <option value="">No home room (Floating)</option>
                                        @foreach($rooms as $r)
                                            <option value="{{ $r->id }}" {{ $assignedRoom && $assignedRoom->id == $r->id ? 'selected' : '' }}>
                                                {{ $r->room_number }} · {{ $r->name }} ({{ $r->capacity }} seats)
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($assignedRoom)
                                        @if($studentCount <= $assignedRoom->capacity)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Fit ({{ $studentCount }}/{{ $assignedRoom->capacity }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-red-600">
                                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Over Capacity ({{ $studentCount }}/{{ $assignedRoom->capacity }})
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 text-[11px]">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <button type="button" onclick="openTempMoveModal({{ $sec->id }}, '{{ addslashes($sec->schoolClass->name ?? 'Class') }} - {{ addslashes($sec->name) }}')" class="px-2.5 py-1 text-xs text-orange-600 hover:bg-orange-50 dark:hover:bg-slate-700 rounded-lg transition font-medium">
                                        <i class="fa-solid fa-arrow-right-arrow-left mr-1"></i> Move
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<!-- Modal: Temporary Room Move -->
<div id="tempMoveModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Temporary Room Move</h3>
        <p id="tempMoveSectionName" class="text-xs text-slate-500 mb-4"></p>

        <form method="POST" action="{{ route('classrooms.plan.temporary') }}" class="space-y-4">
            @csrf
            <input type="hidden" id="temp_section_id" name="section_id" value="">

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Temporary Room *</label>
                <select name="room_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}">{{ $r->room_number }} · {{ $r->name }} ({{ $r->capacity }} seats)</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">From Date *</label>
                    <input type="date" name="from" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">To Date *</label>
                    <input type="date" name="to" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Reason for Relocation</label>
                <input type="text" name="reason" placeholder="e.g. Painting work, AC repair, Science exhibition" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('tempMoveModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition">Schedule Move</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTempMoveModal(secId, secName) {
    document.getElementById('temp_section_id').value = secId;
    document.getElementById('tempMoveSectionName').innerText = 'Relocate ' + secName;
    document.getElementById('tempMoveModal').classList.remove('hidden');
}
</script>
@endsection

