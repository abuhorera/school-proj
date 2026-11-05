<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT Online Test - {{ $exam->title }}</title>
    <!-- Tailwind CSS CDN -->
        <!-- Local Compiled CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- FontAwesome 6 (Local primary with CDN fallback) -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <!-- Tailwind Engine -->
    <script src="{{ asset('js/tailwind.cdn.js') }}"></script>
    <script>
        if (typeof tailwind === 'undefined') {
            document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        }
    </script>
    <style>
        .question-pane { display: none; }
        .question-pane.active { display: block; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 font-sans flex flex-col justify-between">

    <!-- Top CBT Examination Control Bar -->
    <header class="bg-slate-900 text-white shadow-md border-b border-slate-800 px-6 py-3 sticky top-0 z-30 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <span class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-sm">
                <i class="fa-solid fa-laptop-code"></i>
            </span>
            <div>
                <h1 class="text-sm font-black tracking-wide">{{ $exam->title }}</h1>
                <p class="text-[11px] text-slate-400">
                    {{ $exam->schoolClass->name ?? 'Class' }} • {{ $exam->subject->name ?? 'Subject' }} • Total: {{ $exam->questions->count() }} Questions ({{ $exam->total_marks }} Marks)
                </p>
            </div>
        </div>

        <!-- Real-time Countdown Timer -->
        <div class="flex items-center space-x-4">
            <div class="bg-slate-800 border border-slate-700 px-3.5 py-1.5 rounded-xl flex items-center space-x-2">
                <i class="fa-regular fa-clock text-blue-400 text-sm"></i>
                <div>
                    <span class="text-[9px] uppercase tracking-wider text-slate-400 block leading-none">Time Remaining</span>
                    <span id="countdownDisplay" class="font-mono font-bold text-sm text-white">30:00</span>
                </div>
            </div>
            <a href="{{ route('online-exams.manage') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition">
                Exit Simulator
            </a>
        </div>
    </header>

    <!-- Main Test Workspace -->
    <main class="max-w-7xl mx-auto w-full p-4 md:p-6 flex-1">
        <form id="cbtExamForm" action="{{ route('online-exams.submit', $exam->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to finish and submit your exam?')">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left/Center Column: Question Card & Controls -->
                <div class="lg:col-span-3 space-y-4">
                    @foreach($exam->questions as $index => $q)
                    <div id="q_pane_{{ $index }}" class="question-pane bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 {{ $index == 0 ? 'active' : '' }}">
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-800 font-black text-xs">
                                    Question {{ $index + 1 }} of {{ $exam->questions->count() }}
                                </span>
                                <span class="text-xs text-slate-500 font-semibold">{{ $q->difficulty }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-emerald-600">+{{ $q->pivot->marks ?? $q->marks }} Marks</span>
                                @if($exam->negative_marking)
                                <span class="text-[10px] text-rose-500 font-semibold block">(-{{ $exam->negative_mark_rate }} Neg)</span>
                                @endif
                            </div>
                        </div>

                        <!-- Question Prompt -->
                        <div class="mb-6">
                            <p class="text-sm font-bold text-slate-900 leading-relaxed">{{ $q->question }}</p>
                        </div>

                        <!-- Options Input -->
                        @if(is_array($q->options))
                        <div class="space-y-3">
                            @foreach($q->options as $opt)
                            <label class="p-3.5 rounded-xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50/20 transition flex items-center space-x-3 cursor-pointer">
                                <input type="radio" 
                                       name="answers[{{ $q->id }}]" 
                                       value="{{ $opt['key'] ?? '' }}" 
                                       onchange="markAnswered({{ $index }})" 
                                       class="text-blue-600 focus:ring-blue-500 h-4 w-4">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $opt['key'] ?? '' }}
                                </span>
                                <span class="text-xs font-medium text-slate-800">{{ $opt['text'] ?? '' }}</span>
                            </label>
                            @endforeach
                        </div>
                        @endif

                        <!-- Action Buttons Row -->
                        <div class="flex items-center justify-between pt-6 mt-6 border-t border-slate-100">
                            <button type="button" 
                                    onclick="navigateQuestion({{ $index - 1 }})" 
                                    class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $index == 0 ? 'invisible' : '' }}">
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Previous</span>
                            </button>

                            @if($index < $exam->questions->count() - 1)
                            <button type="button" 
                                    onclick="navigateQuestion({{ $index + 1 }})" 
                                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                                <span>Save & Next</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                            @else
                            <button type="submit" 
                                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-md">
                                <i class="fa-solid fa-check-double"></i>
                                <span>Final Submit Exam</span>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Right Column: Student Selector & Question Navigator Palette -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Student Candidate Selection -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Candidate Profile</label>
                        <select name="student_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 font-bold text-slate-800 focus:ring-2 focus:ring-blue-500">
                            @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->admission_number ?? 'ID: '.$st->id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Question Palette -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Questions Palette</h3>
                        
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($exam->questions as $index => $q)
                            <button type="button" 
                                    id="palette_btn_{{ $index }}" 
                                    onclick="navigateQuestion({{ $index }})" 
                                    class="h-9 rounded-lg font-bold text-xs flex items-center justify-center transition border {{ $index == 0 ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                {{ $index + 1 }}
                            </button>
                            @endforeach
                        </div>

                        <!-- Legend -->
                        <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-[11px] text-slate-500">
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded bg-emerald-500"></span>
                                <span>Answered</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded bg-blue-600"></span>
                                <span>Current Question</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded bg-slate-100 border border-slate-300"></span>
                                <span>Not Answered</span>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
                                <i class="fa-solid fa-flag-checkered"></i>
                                <span>Submit Exam Now</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        let currentIdx = 0;
        const totalQuestions = {{ $exam->questions->count() }};
        const durationMinutes = {{ $exam->duration }};
        let timeRemaining = durationMinutes * 60;

        // Timer
        const timerElem = document.getElementById('countdownDisplay');
        const interval = setInterval(() => {
            if (timeRemaining <= 0) {
                clearInterval(interval);
                alert('Time expired! Submitting examination paper automatically.');
                document.getElementById('cbtExamForm').submit();
                return;
            }
            timeRemaining--;
            const mins = Math.floor(timeRemaining / 60);
            const secs = timeRemaining % 60;
            timerElem.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }, 1000);

        function navigateQuestion(idx) {
            if (idx < 0 || idx >= totalQuestions) return;

            // Hide old pane
            document.getElementById(`q_pane_${currentIdx}`).classList.remove('active');
            
            // Update palette styling
            updatePaletteBtn(currentIdx);

            currentIdx = idx;

            // Show new pane
            document.getElementById(`q_pane_${currentIdx}`).classList.add('active');
            
            // Set current in palette
            const curBtn = document.getElementById(`palette_btn_${currentIdx}`);
            curBtn.className = "h-9 rounded-lg font-bold text-xs flex items-center justify-center transition border bg-blue-600 text-white border-blue-600";
        }

        function markAnswered(idx) {
            updatePaletteBtn(idx);
        }

        function updatePaletteBtn(idx) {
            const btn = document.getElementById(`palette_btn_${idx}`);
            const pane = document.getElementById(`q_pane_${idx}`);
            const isChecked = pane.querySelector('input[type="radio"]:checked');

            if (idx === currentIdx) {
                btn.className = "h-9 rounded-lg font-bold text-xs flex items-center justify-center transition border bg-blue-600 text-white border-blue-600";
            } else if (isChecked) {
                btn.className = "h-9 rounded-lg font-bold text-xs flex items-center justify-center transition border bg-emerald-500 text-white border-emerald-500";
            } else {
                btn.className = "h-9 rounded-lg font-bold text-xs flex items-center justify-center transition border bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100";
            }
        }
    </script>
</body>
</html>

