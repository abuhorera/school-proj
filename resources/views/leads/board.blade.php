@extends('layouts.app')

@section('title', 'Admissions Pipeline Kanban Board')

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

    <!-- Pipeline Filters & Search Header Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('leads.board') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
            <!-- Search -->
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate, phone..." class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
            </div>

            <!-- Class Filter -->
            <div>
                <select name="class_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    <option value="">All Classes</option>
                    @foreach($schoolClasses as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Source Filter -->
            <div>
                <select name="source_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    <option value="">All Sources</option>
                    @foreach($sources as $s)
                    <option value="{{ $s->id }}" {{ request('source_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Counselor Filter -->
            <div>
                <select name="assigned_to" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    <option value="">All Counselors</option>
                    @foreach($counselors as $cs)
                    <option value="{{ $cs->id }}" {{ request('assigned_to') == $cs->id ? 'selected' : '' }}>{{ $cs->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-lg transition shadow-xs">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'class_id', 'source_id', 'assigned_to']))
                <a href="{{ route('leads.board') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-lg transition text-center" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Kanban Board Container (Horizontal Scrollable) -->
    <div class="overflow-x-auto pb-4 custom-scroll">
        <div class="flex space-x-4 min-w-max items-start">
            @foreach($stages as $stage)
            <!-- Stage Column -->
            <div class="w-72 bg-slate-100/80 rounded-2xl border border-slate-200/80 flex flex-col max-h-[calc(100vh-250px)] shadow-xs">
                <!-- Column Header -->
                <div class="p-3.5 border-b border-slate-200 flex items-center justify-between bg-white rounded-t-2xl">
                    <div class="flex items-center space-x-2 truncate">
                        <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $stage->color }}"></span>
                        <h4 class="font-bold text-slate-800 text-xs truncate" title="{{ $stage->name }}">{{ $stage->name }}</h4>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold text-slate-600 bg-slate-100 border border-slate-200">
                        {{ $stage->leads->count() }}
                    </span>
                </div>

                <!-- Column Cards Container -->
                <div class="p-3 space-y-3 overflow-y-auto flex-1 custom-scroll" style="min-height: 200px;">
                    @forelse($stage->leads as $lead)
                    <!-- Lead Card -->
                    <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md transition relative group text-xs">
                        <div class="flex items-start justify-between">
                            <div>
                                <h5 class="font-bold text-slate-800 text-sm hover:text-cyan-600 transition">{{ $lead->student_name }}</h5>
                                <div class="flex items-center space-x-1.5 mt-0.5">
                                    <span class="px-1.5 py-0.2 bg-cyan-50 text-cyan-700 font-bold rounded text-[9px] border border-cyan-100">{{ $lead->class_name ?? 'Class N/A' }}</span>
                                    <span class="text-slate-400 text-[10px]">•</span>
                                    <span class="text-slate-500 text-[10px]">{{ $lead->source_name ?? 'Direct' }}</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $lead->status == 'converted' ? 'bg-emerald-100 text-emerald-800' : ($lead->status == 'lost' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ strtoupper($lead->status) }}
                            </span>
                        </div>

                        <!-- Parent & Contact Details -->
                        <div class="mt-2.5 pt-2 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                            <div class="flex items-center justify-between">
                                <span class="truncate"><i class="fa-regular fa-user text-slate-400 mr-1"></i>{{ $lead->parent_name }}</span>
                                <a href="tel:{{ $lead->phone }}" class="text-cyan-600 font-bold hover:underline shrink-0">
                                    <i class="fa-solid fa-phone mr-1"></i>{{ $lead->phone }}
                                </a>
                            </div>

                            @if($lead->follow_up_date)
                            @php
                                $isOverdue = \Carbon\Carbon::parse($lead->follow_up_date)->isPast() && !\Carbon\Carbon::parse($lead->follow_up_date)->isToday() && $lead->status == 'active';
                                $isToday = \Carbon\Carbon::parse($lead->follow_up_date)->isToday();
                            @endphp
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-[10px]">Follow-up:</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded {{ $isOverdue ? 'bg-rose-100 text-rose-700' : ($isToday ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                    {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}
                                </span>
                            </div>
                            @endif

                            @if($lead->assignedStaff)
                            <div class="flex items-center justify-between text-[10px] text-slate-400 pt-0.5">
                                <span>Counselor:</span>
                                <span class="font-medium text-slate-600">{{ $lead->assignedStaff->name }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Card Actions & Quick Stage Move -->
                        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-1">
                            <button onclick="openFollowupModal({{ $lead->id }}, '{{ addslashes($lead->student_name) }}', {{ $lead->lead_stage_id }})" class="px-2 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded text-[10px] font-semibold transition border border-slate-200/80 flex items-center space-x-1">
                                <i class="fa-solid fa-comment-dots text-cyan-600"></i>
                                <span>Note</span>
                            </button>

                            <!-- Quick Move Stage Select -->
                            <form action="{{ route('leads.stage.update', $lead->id) }}" method="POST" class="inline">
                                @csrf
                                <select name="lead_stage_id" onchange="this.form.submit()" class="text-[10px] py-1 px-1.5 bg-slate-50 border border-slate-200 rounded text-slate-700 font-semibold focus:outline-none hover:bg-white cursor-pointer" title="Move to Stage">
                                    <option value="" disabled>Move to...</option>
                                    @foreach($stages as $stgOpt)
                                    <option value="{{ $stgOpt->id }}" {{ $stgOpt->id == $lead->lead_stage_id ? 'selected' : '' }}>
                                        &rarr; {{ $stgOpt->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-xs border border-dashed border-slate-300 rounded-xl bg-slate-50/50">
                        <i class="fa-regular fa-folder-open text-xl mb-1 text-slate-300"></i>
                        <p>No leads in stage</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
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
                <textarea name="response_notes" rows="3" required placeholder="Parent discussed fee structure, scheduled campus tour next Monday..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition"></textarea>
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

