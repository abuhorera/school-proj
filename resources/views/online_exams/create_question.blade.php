@extends('layouts.app')

@section('title', 'Add New Question - Master Question Bank')

@section('content')
<div class="space-y-6">
    @include('online_exams.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-plus"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Add New Question</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Author a question item with options, correct answer keys, marks, and explanation solution</p>
        </div>
        <a href="{{ route('online-exams.question-bank') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200 self-start md:self-auto">
            <i class="fa-solid fa-arrow-left text-slate-500"></i>
            <span>Back to Question Bank</span>
        </a>
    </div>

    <!-- Question Authoring Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <form action="{{ route('online-exams.question-bank.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Row 1: Academic Classification -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Question Type <span class="text-rose-500">*</span></label>
                    <select id="questionTypeSelect" name="question_type" onchange="toggleOptionSections()" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="single_choice">Single Choice (MCQ)</option>
                        <option value="true_false">True / False</option>
                        <option value="multiple_choice">Multiple Response</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Chapter / Topic</label>
                    <select name="question_topic_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- None / General --</option>
                        @foreach($topics as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->chapter ?? 'Topic' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 2: Scoring & Difficulty -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Difficulty Level <span class="text-rose-500">*</span></label>
                    <select name="difficulty" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="Easy">Easy (Recall & Basics)</option>
                        <option value="Medium" selected>Medium (Application & Logic)</option>
                        <option value="Hard">Hard (Analysis & Complex Problem)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Marks Awarded <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.5" name="marks" value="1.0" min="0.5" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Negative Marks (Deduction)</label>
                    <input type="number" step="0.25" name="negative_marks" value="0.00" min="0" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <!-- Row 3: Question Text -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Question Prompt <span class="text-rose-500">*</span></label>
                <textarea name="question" rows="3" required placeholder="Type the question text or mathematical problem here..." class="w-full text-xs rounded-xl border border-slate-200 p-3 focus:ring-2 focus:ring-emerald-500 focus:outline-hidden"></textarea>
            </div>

            <!-- Row 4A: MCQ Options Builder (Default) -->
            <div id="mcqOptionsBlock" class="space-y-3 pt-3 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-slate-700">Answer Choices & Correct Key <span class="text-rose-500">*</span></label>
                    <span class="text-[11px] text-slate-400">Select the radio button next to the correct answer.</span>
                </div>

                <div class="space-y-2.5">
                    @foreach(['A', 'B', 'C', 'D'] as $key)
                    <div class="flex items-center space-x-2">
                        <label class="flex items-center space-x-1.5 cursor-pointer bg-slate-50 border border-slate-200 px-2.5 py-2 rounded-xl">
                            <input type="radio" name="correct_answer" value="{{ $key }}" {{ $key == 'A' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs font-black text-slate-700">{{ $key }}</span>
                        </label>
                        <input type="text" name="opt_{{ strtolower($key) }}" required placeholder="Option {{ $key }} text..." class="flex-1 text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Row 4B: True/False Options Block -->
            <div id="tfOptionsBlock" class="hidden space-y-3 pt-3 border-t border-slate-100">
                <label class="text-xs font-bold text-slate-700 block">Correct Answer Key <span class="text-rose-500">*</span></label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2 cursor-pointer bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl">
                        <input type="radio" name="tf_correct" value="True" checked class="text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-slate-800">True</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl">
                        <input type="radio" name="tf_correct" value="False" class="text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-slate-800">False</span>
                    </label>
                </div>
            </div>

            <!-- Row 5: Solution & Hint -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Solution Explanation (Shown after exam)</label>
                    <textarea name="solution_html" rows="2" placeholder="Step-by-step mathematical derivation or reasoning..." class="w-full text-xs rounded-xl border border-slate-200 p-2.5 focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hint (Optional)</label>
                    <textarea name="hint_html" rows="2" placeholder="Guidance clue for practice tests..." class="w-full text-xs rounded-xl border border-slate-200 p-2.5 focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
            </div>

            <!-- Row 6: Tags -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tags (Comma-separated)</label>
                <input type="text" name="tags" placeholder="e.g. algebra, quadratics, board-2026, competitive" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500">
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <a href="{{ route('online-exams.question-bank') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save to Question Bank</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleOptionSections() {
    const type = document.getElementById('questionTypeSelect').value;
    const mcqBlock = document.getElementById('mcqOptionsBlock');
    const tfBlock = document.getElementById('tfOptionsBlock');

    if (type === 'true_false') {
        mcqBlock.classList.add('hidden');
        tfBlock.classList.remove('hidden');
    } else {
        mcqBlock.classList.remove('hidden');
        tfBlock.classList.add('hidden');
    }
}
</script>
@endsection

