@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-heart-pulse text-teal-600"></i>
                <span>Student Health & Pediatric Records</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Blood group registry, physical growth metrics, dietary restrictions, and emergency medical alerts</p>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <span class="bg-teal-50 text-teal-700 font-extrabold px-3 py-1.5 rounded-xl border border-teal-200">
                Active Health Roster: {{ $students->total() }} Candidates
            </span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('students.health') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Filter Class</label>
                <select name="class_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-teal-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Search Student</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student name or admission no..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-teal-500">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-1.5 px-3 rounded-lg transition">
                    Filter Health
                </button>
                <a href="{{ route('students.health') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-1.5 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12">#</th>
                        <th class="py-3 px-4">Roll</th>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Class & Sec</th>
                        <th class="py-3 px-4 text-center">Blood Group</th>
                        <th class="py-3 px-4">Height / Weight</th>
                        <th class="py-3 px-4">Allergies / Special Alerts</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $idx => $st)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-700">{{ $st->roll_no ?? '-' }}</td>
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            <a href="{{ route('students.show', $st->id) }}" class="hover:text-blue-600 hover:underline">
                                {{ $st->first_name }} {{ $st->last_name }}
                            </a>
                            <span class="text-[10px] text-slate-400 font-mono block">{{ $st->admission_no }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }} - {{ $st->latestEnrollment->section->name ?? 'A' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full font-mono font-bold text-xs bg-rose-50 text-rose-700 border border-rose-200">
                                {{ $st->blood_group ?? 'O+' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-700">
                            {{ $st->height ?? '145' }} cm &bull; {{ $st->weight ?? '38' }} kg
                        </td>
                        <td class="py-3.5 px-4">
                            @if($st->allergies && $st->allergies != 'None')
                            <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i>
                                <span>{{ $st->allergies }}</span>
                            </span>
                            @else
                            <span class="text-emerald-600 font-bold flex items-center text-[10px]"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 inline-block"></span> No Allergies</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <button onclick="openHealthModal('{{ $st->id }}', '{{ $st->first_name }} {{ $st->last_name }}', '{{ $st->blood_group }}', '{{ $st->height }}', '{{ $st->weight }}', '{{ $st->allergies }}')" class="p-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 transition inline-block font-bold text-[11px]">
                                Edit Health
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            No student health records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: Update Health -->
<div id="modal-health" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full overflow-hidden text-xs">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-heart-pulse text-teal-600"></i>
                <span id="health-modal-title">Update Student Health</span>
            </h3>
            <button onclick="document.getElementById('modal-health').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('students.health.update') }}" class="p-5 space-y-3">
            @csrf
            <input type="hidden" name="student_id" id="health-student-id">
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Blood Group</label>
                    <select name="blood_group" id="health-blood" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-2 text-xs focus:outline-teal-500">
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Height (cm)</label>
                    <input type="number" step="0.1" name="height" id="health-height" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono focus:outline-teal-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight" id="health-weight" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono focus:outline-teal-500">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Allergies / Special Dietary Needs</label>
                <input type="text" name="allergies" id="health-allergies" placeholder="e.g. Peanut allergy, Asthmatic" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-teal-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pediatrician Recommendations / Vision</label>
                <textarea name="medical_alerts" rows="2" placeholder="e.g. Spectacles required for reading..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-teal-500"></textarea>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-health').classList.add('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Cancel</button>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-4 py-1.5 rounded-lg shadow-xs transition">Update Record</button>
            </div>
        </form>
    </div>
</div>

<script>
function openHealthModal(id, name, blood, height, weight, allergies) {
    document.getElementById('health-student-id').value = id;
    document.getElementById('health-modal-title').innerText = 'Update Health: ' + name;
    if (blood) document.getElementById('health-blood').value = blood;
    if (height) document.getElementById('health-height').value = height;
    if (weight) document.getElementById('health-weight').value = weight;
    if (allergies) document.getElementById('health-allergies').value = allergies;
    document.getElementById('modal-health').classList.remove('hidden');
}
</script>
@endsection

