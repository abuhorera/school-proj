@extends('layouts.app')

@section('title', 'Lesson Planner Settings - Curriculum & Syllabus')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    @include('lesson_plans.partials.subnav')

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
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-gear"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Lesson Planner Settings</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Configure approval routing workflows, submission deadlines, and HOD department assignments</p>
        </div>
    </div>

    <!-- Section 1: Workflow & Reminders -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <h3 class="font-bold text-slate-800 text-sm mb-1 flex items-center space-x-2">
            <i class="fa-solid fa-sliders text-emerald-600"></i>
            <span>Workflow & Automated Reminders</span>
        </h3>
        <p class="text-xs text-slate-400 mb-5">Define the multi-tier review process and faculty deadline rules</p>

        <form action="{{ route('lesson-plans.settings.update') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Checkbox 1: HOD Step -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex items-start space-x-3">
                <input type="checkbox" name="hod_step_enabled" id="hod_step_enabled" value="1" {{ $settings->hod_step_enabled ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                <label for="hod_step_enabled" class="text-xs text-slate-700 cursor-pointer">
                    <span class="font-bold text-slate-900 block">Enable HOD Review Step</span>
                    <span class="text-slate-500 text-[11px]">When on, a submitted plan goes to the teacher's department head first (if mapped) before proceeding to the Principal for final sign-off.</span>
                </label>
            </div>

            <!-- Checkbox 2: Allow Edit after submit -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex items-start space-x-3">
                <input type="checkbox" name="allow_edit_after_submit" id="allow_edit_after_submit" value="1" {{ $settings->allow_edit_after_submit ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                <label for="allow_edit_after_submit" class="text-xs text-slate-700 cursor-pointer">
                    <span class="font-bold text-slate-900 block">Allow Teacher Edit After Submission</span>
                    <span class="text-slate-500 text-[11px]">Allows teachers to modify lesson plans while they are pending review without recalling the submission.</span>
                </label>
            </div>

            <!-- Checkbox 3: Reminders -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex items-start space-x-3">
                <input type="checkbox" name="reminder_enabled" id="reminder_enabled" value="1" {{ $settings->reminder_enabled ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                <label for="reminder_enabled" class="text-xs text-slate-700 cursor-pointer">
                    <span class="font-bold text-slate-900 block">Enable Weekly Lesson Plan Reminders</span>
                    <span class="text-slate-500 text-[11px]">Broadcast automated notifications to teachers reminding them to draft and submit next week's lesson plans.</span>
                </label>
            </div>

            <!-- Deadlines & Timing -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Reminder Dispatch Day & Time</label>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="reminder_day" class="text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-white">
                            @foreach(['Thursday', 'Friday', 'Saturday'] as $d)
                                <option value="{{ $d }}" {{ $settings->reminder_day == $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                        <input type="time" name="reminder_time" value="{{ $settings->reminder_time ?? '15:00' }}" class="text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Weekly Submission Deadline</label>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="submission_deadline_day" class="text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-white">
                            @foreach(['Sunday', 'Monday', 'Tuesday'] as $d)
                                <option value="{{ $d }}" {{ $settings->submission_deadline_day == $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                        <input type="time" name="submission_deadline_time" value="{{ $settings->submission_deadline_time ?? '09:00' }}" class="text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Department Heads (HOD Routing) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-users-gear text-emerald-600"></i>
                    <span>Department Heads (HOD Routing)</span>
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Maps a department to the head faculty member who conducts tier-1 reviews</p>
            </div>
            <button onclick="document.getElementById('assignHeadModal').classList.remove('hidden')" class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Assign Head</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Department</th>
                        <th class="py-3 px-4">Department Head</th>
                        <th class="py-3 px-4 text-right">Designation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($departmentHeads as $dh)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 font-bold text-slate-900">
                            {{ $dh->department }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-slate-800">{{ $dh->headStaff->first_name ?? 'Faculty' }} {{ $dh->headStaff->last_name ?? '' }}</span>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $dh->headStaff->employee_id ?? '' }}</div>
                        </td>
                        <td class="py-3 px-4 text-right text-emerald-700 font-bold">
                            {{ $dh->headStaff->designation ?? 'HOD' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-slate-400">No department heads mapped yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Assign Department Head -->
<div id="assignHeadModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Assign Department Head</h3>
            <button onclick="document.getElementById('assignHeadModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('lesson-plans.settings.dept-heads') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Department Name <span class="text-rose-500">*</span></label>
                <select name="department" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                    <option value="Mathematics">Mathematics</option>
                    <option value="Science">Science</option>
                    <option value="Languages">Languages</option>
                    <option value="Social Studies">Social Studies</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Arts & Humanities">Arts & Humanities</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Select Head Faculty <span class="text-rose-500">*</span></label>
                <select name="head_staff_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                    @foreach($staffMembers as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->designation ?? 'Staff' }} - {{ $st->department }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('assignHeadModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs">Assign Head</button>
            </div>
        </form>
    </div>
</div>
@endsection

