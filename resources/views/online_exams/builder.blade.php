@extends('layouts.app')

@section('title', 'Exam Paper Builder - ' . $exam->title)

@section('content')
<div class="space-y-6">
    @include('online_exams.partials.subnav')

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

    <!-- Exam Particulars Banner -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">
                    {{ $exam->kind }} Paper Builder
                </span>
                <span class="text-xs text-slate-400 font-mono">{{ $exam->duration }} Mins</span>
                @if($exam->negative_marking)
                <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">Negative Marking: -{{ $exam->negative_mark_rate }}</span>
                @endif
            </div>
            <h1 class="text-xl font-black text-slate-900">{{ $exam->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ $exam->schoolClass->name ?? 'Class' }} ({{ $exam->section->name ?? 'A' }}) • {{ $exam->subject->name ?? 'Subject' }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('pickQuestionsModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Questions from Bank</span>
            </button>
            <a href="{{ route('online-exams.take', $exam->id) }}" target="_blank" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-eye text-xs"></i>
                <span>Preview CBT Simulator</span>
            </a>
            <a href="{{ route('online-exams.manage') }}" class="p-2 border border-slate-200 text-slate-500 hover:bg-slate-50 rounded-xl text-xs transition" title="Back to Exams">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
    </div>

    <!-- Stats Row: Total Questions & Marks -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Questions</span>
                <h4 class="text-xl font-black text-slate-800 mt-0.5">{{ $exam->questions->count() }}</h4>
            </div>
            <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold"><i class="fa-solid fa-list-ol"></i></span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Paper Marks</span>
                <h4 class="text-xl font-black text-emerald-600 mt-0.5">{{ $exam->total_marks }}</h4>
            </div>
            <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold"><i class="fa-solid fa-award"></i></span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pass Criteria</span>
                <h4 class="text-xl font-black text-purple-600 mt-0.5">{{ $exam->passing_percentage }}%</h4>
            </div>
            <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold"><i class="fa-solid fa-shield-halved"></i></span>
        </div>
    </div>

    <!-- Attached Questions Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Configured Questions in this Paper</h3>
            <span class="text-xs text-slate-400">{{ $exam->questions->count() }} items listed</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($exam->questions as $index => $q)
            <div class="p-4 hover:bg-slate-50/40 transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start space-x-3">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <div class="flex items-center space-x-2 mb-1.5 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 font-mono uppercase">
                                    {{ str_replace('_', ' ', $q->question_type) }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $q->difficulty == 'Easy' ? 'bg-emerald-100 text-emerald-800' : ($q->difficulty == 'Hard' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $q->difficulty }}
                                </span>
                                @if($q->topic)
                                <span class="text-[10px] text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded">
                                    <i class="fa-solid fa-bookmark text-[9px] mr-1"></i>{{ $q->topic->name }}
                                </span>
                                @endif
                                <span class="text-xs font-bold text-slate-700 ml-auto">
                                    {{ $q->pivot->marks ?? $q->marks }} Marks
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm leading-relaxed">{{ $q->question }}</h4>

                            <!-- Options preview -->
                            @if(is_array($q->options))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3">
                                @foreach($q->options as $opt)
                                @php
                                    $isCorrect = is_array($q->correct_answers) && in_array($opt['key'] ?? '', $q->correct_answers);
                                @endphp
                                <div class="px-3 py-1.5 rounded-lg border text-xs flex items-center space-x-2 {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-slate-200 text-slate-700' }}">
                                    <span class="w-5 h-5 rounded font-black text-[10px] flex items-center justify-center {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $opt['key'] ?? '' }}
                                    </span>
                                    <span>{{ $opt['text'] ?? '' }}</span>
                                    @if($isCorrect)
                                    <i class="fa-solid fa-check text-emerald-600 text-xs ml-auto"></i>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif

                            @if($q->solution_html)
                            <div class="mt-2 text-[11px] text-slate-500 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                <strong>Explanation:</strong> {{ $q->solution_html }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detach Button -->
                    <form action="{{ route('online-exams.builder.remove-question', ['examId' => $exam->id, 'questionId' => $q->id]) }}" method="POST" onsubmit="return confirm('Remove question from this paper?')" class="shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Remove question from paper">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-pen-ruler text-3xl text-slate-300 mb-2"></i>
                <h4 class="font-bold text-slate-700 text-sm">No Questions Attached Yet</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-4">Click below to select and import questions directly from your Question Bank into this exam paper.</p>
                <button onclick="document.getElementById('pickQuestionsModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs">
                    <i class="fa-solid fa-plus mr-1"></i> Add Questions from Question Bank
                </button>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal: Pick Questions from Question Bank -->
<div id="pickQuestionsModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Select Questions from Question Bank</h3>
                <p class="text-[11px] text-slate-400">Choose questions to attach to '{{ $exam->title }}'</p>
            </div>
            <button onclick="document.getElementById('pickQuestionsModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('online-exams.builder.add-question', $exam->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="p-4 overflow-y-auto flex-1 space-y-3">
                @forelse($availableQuestions as $avail)
                <label class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-blue-50/30 hover:border-blue-300 transition flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" name="question_ids[]" value="{{ $avail->id }}" class="mt-1 rounded text-blue-600 focus:ring-blue-500">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 text-[10px] mb-1">
                            <span class="font-bold bg-slate-200 px-1.5 py-0.5 rounded text-slate-700 uppercase">{{ $avail->question_type }}</span>
                            <span class="font-semibold text-slate-500">{{ $avail->subject->name ?? 'General' }}</span>
                            <span class="font-bold text-emerald-600 ml-auto">{{ $avail->marks }} Marks</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-800 leading-snug">{{ $avail->question }}</p>
                    </div>
                </label>
                @empty
                <div class="py-8 text-center text-slate-400 text-xs">
                    <p>No additional questions available in the question bank for this subject.</p>
                    <a href="{{ route('online-exams.question-bank.create') }}" class="text-blue-600 font-bold hover:underline mt-2 inline-block">Create New Question</a>
                </div>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <span class="text-xs text-slate-500">Select all questions you wish to include.</span>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="document.getElementById('pickQuestionsModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs">Attach Selected Questions</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

