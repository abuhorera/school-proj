@extends('layouts.app')

@section('title', 'PTM Follow-ups - Parent Teacher Meetings')

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
                    <i class="fa-solid fa-list-check"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">PTM Follow-ups</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Post-meeting action items, remedial interventions, and parent-teacher agreements</p>
        </div>
        <div>
            <button onclick="document.getElementById('addFollowupModal').classList.remove('hidden')" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>New Follow-up</span>
            </button>
        </div>
    </div>

    <!-- Status KPI Filter Pills -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('ptm.followups') }}" class="p-3.5 rounded-2xl border transition shadow-xs {{ empty($status) ? 'bg-amber-500 text-white border-amber-500' : 'bg-white border-slate-100 text-slate-700 hover:bg-slate-50' }}">
            <span class="text-[10px] uppercase font-bold tracking-wider {{ empty($status) ? 'text-amber-100' : 'text-slate-400' }}">All Follow-ups</span>
            <div class="text-xl font-black mt-1 font-mono">{{ $openCount + $inProgressCount + $completedCount }}</div>
        </a>

        <a href="{{ route('ptm.followups', ['status' => 'Open']) }}" class="p-3.5 rounded-2xl border transition shadow-xs {{ $status == 'Open' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white border-slate-100 text-slate-700 hover:bg-slate-50' }}">
            <span class="text-[10px] uppercase font-bold tracking-wider {{ $status == 'Open' ? 'text-rose-100' : 'text-rose-500' }}">Open</span>
            <div class="text-xl font-black mt-1 font-mono">{{ $openCount }}</div>
        </a>

        <a href="{{ route('ptm.followups', ['status' => 'In progress']) }}" class="p-3.5 rounded-2xl border transition shadow-xs {{ $status == 'In progress' ? 'bg-sky-600 text-white border-sky-600' : 'bg-white border-slate-100 text-slate-700 hover:bg-slate-50' }}">
            <span class="text-[10px] uppercase font-bold tracking-wider {{ $status == 'In progress' ? 'text-sky-100' : 'text-sky-500' }}">In Progress</span>
            <div class="text-xl font-black mt-1 font-mono">{{ $inProgressCount }}</div>
        </a>

        <a href="{{ route('ptm.followups', ['status' => 'Completed']) }}" class="p-3.5 rounded-2xl border transition shadow-xs {{ $status == 'Completed' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white border-slate-100 text-slate-700 hover:bg-slate-50' }}">
            <span class="text-[10px] uppercase font-bold tracking-wider {{ $status == 'Completed' ? 'text-emerald-100' : 'text-emerald-500' }}">Completed</span>
            <div class="text-xl font-black mt-1 font-mono">{{ $completedCount }}</div>
        </a>
    </div>

    <!-- Follow-ups Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Action Items & Remedial Log ({{ $followups->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Meeting</th>
                        <th class="py-3 px-4">Action plan</th>
                        <th class="py-3 px-4">Assignee</th>
                        <th class="py-3 px-4">Due</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($followups as $f)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                {{ $f->student->first_name ?? 'Student' }} {{ $f->student->last_name ?? '' }}
                            </div>
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                {{ $f->student->admission_no ?? 'YIS-00' }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-800 truncate max-w-xs">{{ $f->meeting->title ?? 'PTM' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $f->meeting ? \Carbon\Carbon::parse($f->meeting->meeting_date)->format('d M Y') : '' }}</div>
                        </td>
                        <td class="py-3 px-4 max-w-md">
                            <div class="text-slate-800 font-medium">{{ $f->action_plan }}</div>
                            @if($f->admin_notes)
                                <div class="text-[10px] text-slate-400 mt-1 italic">Note: {{ $f->admin_notes }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-700">
                            {{ $f->assigned_to ?? 'Mentor' }}
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] {{ ($f->status != 'Completed' && $f->due_date && \Carbon\Carbon::parse($f->due_date)->isPast()) ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                            {{ $f->due_date ? \Carbon\Carbon::parse($f->due_date)->format('d M Y') : 'No date' }}
                            @if($f->status != 'Completed' && $f->due_date && \Carbon\Carbon::parse($f->due_date)->isPast())
                                <div class="text-[9px] uppercase tracking-wider text-rose-500 font-bold">Overdue</div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($f->status == 'Completed')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check text-[9px] mr-0.5"></i> Completed
                                </span>
                            @elseif($f->status == 'In progress')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    In progress
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Open
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                @if($f->status != 'Completed')
                                <form action="{{ route('ptm.followups.update', $f->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Completed">
                                    <button type="submit" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold transition" title="Mark as Completed">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('ptm.followups.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Remove follow-up item?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Follow-up">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">No follow-up action plans recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: New Follow-up -->
<div id="addFollowupModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-lg w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Create New PTM Follow-up</h3>
            <button onclick="document.getElementById('addFollowupModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('ptm.followups.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Parent-Teacher Meeting <span class="text-rose-500">*</span></label>
                <select name="ptm_meeting_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Select Meeting --</option>
                    @foreach($meetings as $m)
                        <option value="{{ $m->id }}">{{ $m->title }} ({{ \Carbon\Carbon::parse($m->meeting_date)->format('d M Y') }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Student <span class="text-rose-500">*</span></label>
                <select name="student_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Select Student --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->admission_no ?? 'ID' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Action Plan / Remedial Task <span class="text-rose-500">*</span></label>
                <textarea name="action_plan" required rows="2.5" placeholder="e.g. Supplementary worksheets in Mathematics, reading lab practice, front-row seating..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assignee / Mentor</label>
                    <input type="text" name="assigned_to" placeholder="e.g. Rajesh Sharma (Class Teacher)" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Due Date</label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+14 days')) }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addFollowupModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-xs">Save Follow-up</button>
            </div>
        </form>
    </div>
</div>
@endsection

