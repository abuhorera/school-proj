@extends('layouts.app')

@section('title', 'All Leads Directory')

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

    <!-- Search & Multi-Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('leads.list') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3 text-xs">
            <!-- Search Keyword -->
            <div class="md:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate, parent, phone, email..." class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
            </div>

            <!-- Stage Filter -->
            <div>
                <select name="stage_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    <option value="">All Stages</option>
                    @foreach($stages as $stg)
                    <option value="{{ $stg->id }}" {{ request('stage_id') == $stg->id ? 'selected' : '' }}>{{ $stg->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Source Filter -->
            <div>
                <select name="source_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    <option value="">All Sources</option>
                    @foreach($sources as $src)
                    <option value="{{ $src->id }}" {{ request('source_id') == $src->id ? 'selected' : '' }}>{{ $src->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class Filter -->
            <div>
                <select name="class_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    <option value="">All Classes</option>
                    @foreach($schoolClasses as $sc)
                    <option value="{{ $sc->id }}" {{ request('class_id') == $sc->id ? 'selected' : '' }}>{{ $sc->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-lg transition shadow-xs">
                    Apply Filter
                </button>
                @if(request()->hasAny(['search', 'stage_id', 'source_id', 'class_id', 'status']))
                <a href="{{ route('leads.list') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-lg transition" title="Clear Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Master Leads Directory Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-list-check text-cyan-600"></i>
                <h3 class="font-bold text-slate-800 text-sm">All Admissions Enquiries Directory</h3>
                <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-bold ml-2">{{ $leads->total() }} Total</span>
            </div>
            <button onclick="document.getElementById('newLeadModal').classList.remove('hidden')" class="px-3 py-1.5 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add Enquiry</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3.5">Candidate / Student</th>
                        <th class="p-3.5">Parent & Contact</th>
                        <th class="p-3.5">Target Class</th>
                        <th class="p-3.5">Lead Source</th>
                        <th class="p-3.5">Pipeline Stage</th>
                        <th class="p-3.5">Assigned Counselor</th>
                        <th class="p-3.5">Next Follow-up</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-slate-50 transition">
                        <!-- Candidate Name -->
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800 text-sm">{{ $lead->student_name }}</div>
                            <div class="text-[10px] text-slate-400">Created {{ $lead->created_at->format('d M Y') }}</div>
                        </td>

                        <!-- Parent & Contact -->
                        <td class="p-3.5">
                            <div class="font-medium text-slate-700">{{ $lead->parent_name }}</div>
                            <div class="flex items-center space-x-2 mt-0.5">
                                <a href="tel:{{ $lead->phone }}" class="text-cyan-600 font-semibold hover:underline flex items-center space-x-1">
                                    <i class="fa-solid fa-phone text-[9px]"></i>
                                    <span>{{ $lead->phone }}</span>
                                </a>
                                @if($lead->email)
                                <span class="text-slate-300">|</span>
                                <span class="text-slate-400 text-[10px]">{{ $lead->email }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Class -->
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $lead->class_name ?? 'Class N/A' }}
                            </span>
                        </td>

                        <!-- Lead Source -->
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                {{ $lead->source_name ?? 'Direct' }}
                            </span>
                        </td>

                        <!-- Stage -->
                        <td class="p-3.5">
                            <form action="{{ route('leads.stage.update', $lead->id) }}" method="POST" class="inline">
                                @csrf
                                <select name="lead_stage_id" onchange="this.form.submit()" class="text-[11px] py-1 px-2 rounded-lg font-bold border border-slate-200 focus:outline-none cursor-pointer" style="background-color: {{ $lead->stage ? $lead->stage->color.'15' : '#f1f5f9' }}; color: {{ $lead->stage ? $lead->stage->color : '#334155' }};">
                                    @foreach($stages as $stg)
                                    <option value="{{ $stg->id }}" {{ $stg->id == $lead->lead_stage_id ? 'selected' : '' }}>
                                        {{ $stg->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>

                        <!-- Assigned Counselor -->
                        <td class="p-3.5 text-slate-600">
                            {{ $lead->assignedStaff ? $lead->assignedStaff->name : 'Unassigned' }}
                        </td>

                        <!-- Follow-up Date -->
                        <td class="p-3.5">
                            @if($lead->follow_up_date)
                            @php
                                $isOverdue = \Carbon\Carbon::parse($lead->follow_up_date)->isPast() && !\Carbon\Carbon::parse($lead->follow_up_date)->isToday() && $lead->status == 'active';
                                $isToday = \Carbon\Carbon::parse($lead->follow_up_date)->isToday();
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $isOverdue ? 'bg-rose-100 text-rose-700' : ($isToday ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}
                            </span>
                            @else
                            <span class="text-slate-400 text-[11px]">Not scheduled</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="p-3.5 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <button onclick="openFollowupModal({{ $lead->id }}, '{{ addslashes($lead->student_name) }}', {{ $lead->lead_stage_id }})" class="p-1.5 rounded-lg text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 transition" title="Log Follow-up Call / Note">
                                    <i class="fa-solid fa-phone-volume"></i>
                                </button>
                                <form action="{{ route('leads.delete', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete lead \'{{ addslashes($lead->student_name) }}\'?');" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Lead">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-12 text-center text-slate-400">
                            <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300"></i>
                            <p class="font-medium">No leads match your filter criteria.</p>
                            <a href="{{ route('leads.list') }}" class="text-cyan-600 text-xs font-semibold hover:underline mt-1 inline-block">Reset all filters</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $leads->links() }}
        </div>
        @endif
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

