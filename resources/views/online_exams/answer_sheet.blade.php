@extends('layouts.app')

@section('title', 'Candidate Answer Sheet - ' . ($attempt->student->first_name ?? 'Student'))

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

    <!-- Candidate Scorecard Header Banner -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl {{ $attempt->status == 'Passed' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center text-2xl font-black shadow-xs">
                <i class="fa-solid {{ $attempt->status == 'Passed' ? 'fa-check' : 'fa-xmark' }}"></i>
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $attempt->status == 'Passed' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $attempt->status }} ({{ $attempt->percentage }}%)
                    </span>
                    <span class="text-xs text-slate-400 font-mono">Adm: {{ $attempt->student->admission_number ?? 'ST-'.$attempt->student_id }}</span>
                </div>
                <h1 class="text-lg font-black text-slate-800 mt-0.5">
                    {{ $attempt->student->first_name ?? 'Student' }} {{ $attempt->student->last_name ?? '' }} — {{ $exam->title }}
                </h1>
                <p class="text-xs text-slate-500">
                    Score: <strong class="text-slate-800">{{ $attempt->score_obtained }} / {{ $exam->total_marks }}</strong> • 
                    Submitted: {{ $attempt->submitted_at ? $attempt->submitted_at->format('d M, Y h:i A') : 'Recently' }}
                </p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200">
                <i class="fa-solid fa-print text-slate-500"></i>
                <span>Print Answer Sheet</span>
            </button>
            <a href="{{ route('online-exams.results', $exam->id) }}" class="p-2 border border-slate-200 text-slate-500 hover:bg-slate-50 rounded-xl text-xs transition" title="Back to Results">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
    </div>

    <!-- Questions Evaluation Ledger -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Detailed Responses Breakdown</h3>
            <div class="flex items-center space-x-3 text-xs">
                <span class="flex items-center space-x-1 text-emerald-700 font-bold">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>{{ $attempt->correct_answers_count }} Correct</span>
                </span>
                <span class="flex items-center space-x-1 text-rose-700 font-bold">
                    <i class="fa-solid fa-circle-xmark text-rose-500"></i>
                    <span>{{ $attempt->incorrect_answers_count }} Incorrect</span>
                </span>
            </div>
        </div>

        <div class="divide-y divide-slate-100 p-4 space-y-4">
            @php $studentAnswers = $attempt->student_answers ?? []; @endphp

            @foreach($exam->questions as $index => $q)
            @php
                $ansInfo = $studentAnswers[$q->id] ?? null;
                $selected = $ansInfo['selected'] ?? null;
                $isCorrect = $ansInfo['is_correct'] ?? false;
                $marksAwarded = $ansInfo['marks_awarded'] ?? 0;
            @endphp
            <div class="p-4 rounded-xl border {{ $isCorrect ? 'border-emerald-100 bg-emerald-50/20' : ($selected !== null ? 'border-rose-100 bg-rose-50/20' : 'border-slate-100 bg-slate-50/20') }}">
                <div class="flex items-start justify-between gap-4 mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg font-bold text-xs flex items-center justify-center {{ $isCorrect ? 'bg-emerald-600 text-white' : ($selected !== null ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-600') }}">
                            {{ $index + 1 }}
                        </span>
                        <span class="text-xs font-bold text-slate-800">{{ $q->question }}</span>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $marksAwarded > 0 ? 'bg-emerald-100 text-emerald-800' : ($marksAwarded < 0 ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-600') }}">
                            {{ $marksAwarded > 0 ? '+' : '' }}{{ $marksAwarded }} Marks
                        </span>
                    </div>
                </div>

                <!-- Options Grid -->
                @if(is_array($q->options))
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3 pl-8">
                    @foreach($q->options as $opt)
                    @php
                        $key = $opt['key'] ?? '';
                        $isThisCorrect = is_array($q->correct_answers) && in_array($key, $q->correct_answers);
                        $isThisSelected = ($selected == $key);

                        $boxClass = 'bg-white border-slate-200 text-slate-700';
                        if ($isThisCorrect) {
                            $boxClass = 'bg-emerald-100 border-emerald-400 text-emerald-900 font-bold';
                        } elseif ($isThisSelected) {
                            $boxClass = 'bg-rose-100 border-rose-400 text-rose-900 font-bold';
                        }
                    @endphp
                    <div class="p-2.5 rounded-lg border text-xs flex items-center space-x-2 {{ $boxClass }}">
                        <span class="w-5 h-5 rounded font-black text-[10px] flex items-center justify-center {{ $isThisCorrect ? 'bg-emerald-600 text-white' : ($isThisSelected ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600') }}">
                            {{ $key }}
                        </span>
                        <span>{{ $opt['text'] ?? '' }}</span>
                        @if($isThisSelected)
                        <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $isThisCorrect ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }} ml-auto">
                            Student Choice
                        </span>
                        @elseif($isThisCorrect)
                        <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-emerald-600 text-white ml-auto">
                            Correct
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if($q->solution_html)
                <div class="mt-3 pl-8">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 leading-relaxed">
                        <strong class="text-slate-800">Explanation:</strong> {{ $q->solution_html }}
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

