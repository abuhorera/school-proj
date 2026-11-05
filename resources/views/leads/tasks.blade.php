@extends('layouts.app')

@section('title', 'Follow-up Tasks & Call Tracker')

@section('content')
<div class="space-y-6">
    @include('leads.partials.subnav')

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

    <!-- Task KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Overdue -->
        <div class="bg-white p-4 rounded-2xl border border-rose-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-rose-500 uppercase tracking-wider">Overdue Tasks</span>
                <h3 class="text-2xl font-black text-rose-600 mt-0.5">{{ $overdueTasks->count() }}</h3>
                <span class="text-[10px] text-slate-400">Needs immediate call</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <!-- Today's Due -->
        <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Today's Due</span>
                <h3 class="text-2xl font-black text-amber-600 mt-0.5">{{ $todayTasks->count() }}</h3>
                <span class="text-[10px] text-slate-400">Scheduled for today</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>

        <!-- Upcoming -->
        <div class="bg-white p-4 rounded-2xl border border-indigo-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider">Upcoming Tasks</span>
                <h3 class="text-2xl font-black text-indigo-600 mt-0.5">{{ $upcomingTasks->count() }}</h3>
                <span class="text-[10px] text-slate-400">Next 7-14 days</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-calendar-week"></i>
            </div>
        </div>

        <!-- Completed Logs -->
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Logged Follow-ups</span>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $recentLogs->count() }}</h3>
                <span class="text-[10px] text-slate-400">Recent touchpoints</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
    </div>

    <!-- Active Call Queues (Overdue & Today) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 1. Overdue Calls Queue -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-rose-50/60 border-b border-rose-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-clock text-rose-500"></i>
                    <h3 class="font-bold text-rose-900 text-xs uppercase tracking-wider">Overdue Calls Queue ({{ $overdueTasks->count() }})</h3>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-200 text-rose-800">URGENT</span>
            </div>
            <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @forelse($overdueTasks as $lead)
                <div class="p-3.5 hover:bg-slate-50 transition text-xs flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-800">{{ $lead->student_name }}</span>
                            <span class="px-1.5 py-0.2 bg-slate-100 text-slate-600 rounded text-[9px] font-medium">{{ $lead->class_name ?? 'Class N/A' }}</span>
                        </div>
                        <p class="text-slate-500 text-[11px] mt-0.5">Parent: {{ $lead->parent_name }} • <a href="tel:{{ $lead->phone }}" class="text-cyan-600 font-bold hover:underline">{{ $lead->phone }}</a></p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="text-[10px] text-rose-600 font-semibold">
                                <i class="fa-regular fa-calendar-xmark mr-0.5"></i>Due: {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="text-[10px] text-slate-400">Assigned: {{ $lead->assignedStaff ? $lead->assignedStaff->name : 'Admissions Desk' }}</span>
                        </div>
                    </div>
                    <div>
                        <button onclick="openFollowupModal({{ $lead->id }}, '{{ addslashes($lead->student_name) }}', {{ $lead->lead_stage_id }})" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-[11px] transition shadow-xs flex items-center space-x-1">
                            <i class="fa-solid fa-phone-volume text-[10px]"></i>
                            <span>Call & Log</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-check text-2xl text-emerald-400 mb-1"></i>
                    <p>No overdue follow-up calls!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- 2. Today's Due Calls Queue -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 bg-amber-50/60 border-b border-amber-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-calendar-day text-amber-600"></i>
                    <h3 class="font-bold text-amber-900 text-xs uppercase tracking-wider">Scheduled for Today ({{ $todayTasks->count() }})</h3>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-200 text-amber-800">TODAY</span>
            </div>
            <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @forelse($todayTasks as $lead)
                <div class="p-3.5 hover:bg-slate-50 transition text-xs flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-800">{{ $lead->student_name }}</span>
                            <span class="px-1.5 py-0.2 bg-slate-100 text-slate-600 rounded text-[9px] font-medium">{{ $lead->class_name ?? 'Class N/A' }}</span>
                        </div>
                        <p class="text-slate-500 text-[11px] mt-0.5">Parent: {{ $lead->parent_name }} • <a href="tel:{{ $lead->phone }}" class="text-cyan-600 font-bold hover:underline">{{ $lead->phone }}</a></p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="text-[10px] text-amber-700 font-semibold">
                                <i class="fa-regular fa-clock mr-0.5"></i>Due Today
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="text-[10px] text-slate-400">Assigned: {{ $lead->assignedStaff ? $lead->assignedStaff->name : 'Admissions Desk' }}</span>
                        </div>
                    </div>
                    <div>
                        <button onclick="openFollowupModal({{ $lead->id }}, '{{ addslashes($lead->student_name) }}', {{ $lead->lead_stage_id }})" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-[11px] transition shadow-xs flex items-center space-x-1">
                            <i class="fa-solid fa-phone-volume text-[10px]"></i>
                            <span>Call & Log</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    <i class="fa-regular fa-calendar-check text-2xl text-slate-300 mb-1"></i>
                    <p>No more calls scheduled for today!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Communication History Feed & Upcoming Tasks -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Tasks Table (1 col) -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Upcoming Scheduled ({{ $upcomingTasks->count() }})</h4>
                <span class="text-[10px] text-indigo-600 font-bold">NEXT WEEKS</span>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                @forelse($upcomingTasks as $lead)
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs hover:border-slate-200 transition">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800">{{ $lead->student_name }}</span>
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                            {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M') }}
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-500 mt-1">Parent: {{ $lead->parent_name }} • {{ $lead->phone }}</div>
                    <div class="mt-2 pt-2 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400">{{ $lead->stage ? $lead->stage->name : 'In Pipeline' }}</span>
                        <button onclick="openFollowupModal({{ $lead->id }}, '{{ addslashes($lead->student_name) }}', {{ $lead->lead_stage_id }})" class="text-[11px] text-cyan-600 font-bold hover:underline">Log Note</button>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-400 text-xs">No upcoming tasks scheduled.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Follow-up Touchpoints Feed (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-list-ul text-cyan-600"></i>
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Recent Communication & Interaction Activity Log</h4>
                </div>
                <span class="text-[10px] font-bold text-slate-400">{{ $recentLogs->count() }} LOGS</span>
            </div>
            <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @forelse($recentLogs as $log)
                <div class="p-3.5 hover:bg-slate-50 transition text-xs flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold shrink-0 mt-0.5">
                        @if($log->type == 'Phone Call')
                            <i class="fa-solid fa-phone text-xs"></i>
                        @elseif($log->type == 'WhatsApp Message')
                            <i class="fa-brands fa-whatsapp text-xs text-emerald-600"></i>
                        @elseif($log->type == 'Campus Visit / Walk-in')
                            <i class="fa-solid fa-school text-xs text-indigo-600"></i>
                        @else
                            <i class="fa-regular fa-comment-dots text-xs"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h5 class="font-bold text-slate-800">
                                {{ $log->lead ? $log->lead->student_name : 'Lead #' . $log->lead_id }}
                                <span class="text-slate-400 font-normal">({{ $log->type }})</span>
                            </h5>
                            <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 text-xs mt-1 bg-slate-50 p-2 rounded-lg border border-slate-100">
                            {{ $log->response_notes }}
                        </p>
                        @if($log->next_follow_up_date)
                        <div class="mt-1 text-[10px] text-indigo-600 font-medium">
                            <i class="fa-regular fa-clock mr-1"></i>Next Follow-up set for: {{ \Carbon\Carbon::parse($log->next_follow_up_date)->format('d M Y') }}
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    <p>No interaction logs recorded yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Followup Modal -->
<div id="followupModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-cyan-400">Log Follow-up Interaction</h3>
                <h4 id="modalStudentName" class="text-sm font-bold mt-0.5 text-white">Student Name</h4>
            </div>
            <button onclick="closeFollowupModal()" class="text-slate-400 hover:text-white transition p-1 text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="followupForm" method="POST" class="p-5 space-y-3.5 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Interaction Type</label>
                <select name="type" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    <option value="Phone Call">Phone Call</option>
                    <option value="Campus Visit / Walk-in">Campus Visit / Walk-in</option>
                    <option value="WhatsApp Message">WhatsApp Message</option>
                    <option value="Email Sent">Email Sent</option>
                    <option value="Counselling Session">Counselling Session</option>
                    <option value="Entrance Exam Slot">Entrance Exam Slot</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Response & Discussion Notes <span class="text-rose-500">*</span></label>
                <textarea name="response_notes" rows="3" required placeholder="Parent confirmed student will visit for entrance test this Saturday..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Next Follow-up Date</label>
                    <input type="date" name="next_follow_up_date" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Advance Stage</label>
                    <select name="lead_stage_id" id="modalStageSelect" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        @foreach($stages as $stg)
                        <option value="{{ $stg->id }}">{{ $stg->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="closeFollowupModal()" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition">Cancel</button>
                <button type="submit" class="px-4 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-semibold transition">Save Interaction</button>
            </div>
        </form>
    </div>
</div>

<script>
function openFollowupModal(leadId, studentName, stageId) {
    document.getElementById('modalStudentName').textContent = studentName;
    document.getElementById('modalStageSelect').value = stageId;
    document.getElementById('followupForm').action = "/school/leads/" + leadId + "/followup";
    document.getElementById('followupModal').classList.remove('hidden');
}
function closeFollowupModal() {
    document.getElementById('followupModal').classList.add('hidden');
}
</script>
@endsection

