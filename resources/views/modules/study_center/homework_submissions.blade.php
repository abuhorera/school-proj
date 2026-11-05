@extends('layouts.app')

@section('title', 'Homework Submissions & Evaluation')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('study-center.index') }}" class="text-slate-700 hover:text-blue-600 font-medium">Study Center</a>
                <span>/</span>
                <a href="{{ route('study-center.homework') }}" class="text-slate-700 hover:text-blue-600 font-medium">Homework</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Submissions</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Homework Submissions &amp; Evaluation</h1>
                    <p class="text-xs text-slate-500">Review student attachments, award numerical marks, and enter qualitative feedback.</p>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('study-center.homework') }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-arrow-left text-slate-400"></i>
                <span>Back to Homeworks</span>
            </a>
        </div>
    </div>

    <!-- Alert / Flash Notification Banner -->
    @if(session('success'))
        <div class="flex items-center justify-between p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <!-- Assignment Meta Overview Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                    {{ $homework->subject->name ?? 'Subject' }}
                </span>
                <h2 class="text-base font-bold text-slate-900 mt-1">{{ $homework->title }}</h2>
                @if($homework->description)
                    <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">{{ $homework->description }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-4 text-xs font-semibold">
                <div class="text-center p-3 bg-slate-50 rounded-xl border border-slate-100 min-w-[90px]">
                    <div class="text-[11px] text-slate-400 font-normal">Target Class</div>
                    <div class="text-slate-800 font-bold mt-0.5">{{ $homework->schoolClass->name ?? 'Class' }}</div>
                </div>
                <div class="text-center p-3 bg-slate-50 rounded-xl border border-slate-100 min-w-[90px]">
                    <div class="text-[11px] text-slate-400 font-normal">Due Date</div>
                    <div class="text-slate-800 font-bold mt-0.5">{{ \Carbon\Carbon::parse($homework->due_date)->format('d M, Y') }}</div>
                </div>
                <div class="text-center p-3 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 min-w-[90px]">
                    <div class="text-[11px] font-normal">Submitted</div>
                    <div class="font-bold text-sm mt-0.5">{{ $submissions->count() }} / {{ $students->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions Evaluation Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Student Submissions Matrix</h3>
                <p class="text-xs text-slate-500">Grading status for all enrolled students in {{ $homework->schoolClass->name ?? 'this class' }}</p>
            </div>
            <span class="text-xs font-semibold text-slate-500">
                Total Enrolled: <strong class="text-slate-800">{{ $students->count() }}</strong>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Roll No</th>
                        <th class="py-3 px-4">Submitted At</th>
                        <th class="py-3 px-4">Attachment</th>
                        <th class="py-3 px-4">Marks (Out of 20)</th>
                        <th class="py-3 px-4">Teacher Remarks</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($students as $std)
                        @php
                            $sub = $submissions->where('student_id', $std->id)->first();
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Student Name -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $std->first_name }} {{ $std->last_name }}</div>
                                <div class="text-[11px] text-slate-400">Adm: {{ $std->admission_no ?? 'N/A' }}</div>
                            </td>

                            <!-- Roll No -->
                            <td class="py-3.5 px-4 whitespace-nowrap font-semibold text-slate-800">
                                {{ $std->roll_no ?? ($std->enrollment_roll_no ?? '-') }}
                            </td>

                            <!-- Submitted At -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($sub && $sub->submitted_at)
                                    <span class="text-emerald-700 font-semibold">{{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M, h:i A') }}</span>
                                @else
                                    <span class="text-amber-600 font-medium">Pending Submission</span>
                                @endif
                            </td>

                            <!-- Attachment -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($sub && $sub->file_name)
                                    <span class="inline-flex items-center space-x-1 text-blue-600 font-semibold hover:underline cursor-pointer">
                                        <i class="fa-solid fa-file-pdf"></i>
                                        <span>{{ $sub->file_name }}</span>
                                    </span>
                                @elseif($sub)
                                    <span class="text-slate-400">Text Response</span>
                                @else
                                    <span class="text-slate-300">&mdash;</span>
                                @endif
                            </td>

                            <!-- Marks -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($sub && $sub->marks !== null)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $sub->marks }} / 20
                                    </span>
                                @elseif($sub)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Unevaluated
                                    </span>
                                @else
                                    <span class="text-slate-300">&mdash;</span>
                                @endif
                            </td>

                            <!-- Remarks -->
                            <td class="py-3.5 px-4">
                                @if($sub && $sub->remarks)
                                    <div class="text-slate-600 truncate max-w-xs text-[11px]">{{ $sub->remarks }}</div>
                                @elseif($sub)
                                    <span class="text-slate-400 italic text-[11px]">No feedback yet</span>
                                @else
                                    <span class="text-slate-300">&mdash;</span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                @if($sub)
                                    <button type="button" onclick="openEvaluationModal('{{ $sub->id }}', '{{ $std->first_name }} {{ $std->last_name }}', '{{ $sub->marks ?? '' }}', '{{ addslashes($sub->remarks ?? '') }}')" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>{{ $sub->marks !== null ? 'Re-grade' : 'Grade' }}</span>
                                    </button>
                                @else
                                    <button type="button" disabled class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-400 cursor-not-allowed">
                                        Not Submitted
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-users text-3xl mb-2 text-slate-300 block"></i>
                                No students currently enrolled in this class.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Evaluation Modal -->
<div id="evaluationModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full border border-slate-200 shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Evaluate Student Submission</h3>
                <p id="evalModalStudentName" class="text-xs text-blue-600 font-semibold"></p>
            </div>
            <button type="button" onclick="closeModal('evaluationModal')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form id="evaluationForm" method="POST" action="" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Marks Awarded (Out of 20) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.5" min="0" max="20" name="marks" id="evalModalMarks" required placeholder="Enter marks (e.g. 18.5)" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 py-2.5 px-3 bg-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Teacher Remarks &amp; Feedback</label>
                <textarea name="remarks" id="evalModalRemarks" rows="3" placeholder="Provide constructive comments or corrections to the student..." class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 py-2.5 px-3 bg-white"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('evaluationModal')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm">Save &amp; Award Marks</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEvaluationModal(submissionId, studentName, marks, remarks) {
    document.getElementById('evalModalStudentName').textContent = 'Student: ' + studentName;
    document.getElementById('evalModalMarks').value = marks;
    document.getElementById('evalModalRemarks').value = remarks;
    document.getElementById('evaluationForm').action = '/school/study-center/homework/submissions/' + submissionId + '/evaluate';
    openModal('evaluationModal');
}
</script>
@endsection

