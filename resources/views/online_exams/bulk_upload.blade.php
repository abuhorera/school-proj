@extends('layouts.app')

@section('title', 'Bulk Upload Questions - Online Examinations')

@section('content')
<div class="space-y-6">
    @include('online_exams.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-file-import"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Bulk Upload Questions</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Quickly import question sets using structured JSON batches or CSV data</p>
        </div>
        <a href="{{ route('online-exams.question-bank') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200 self-start md:self-auto">
            <i class="fa-solid fa-arrow-left text-slate-500"></i>
            <span>Back to Question Bank</span>
        </a>
    </div>

    <!-- Bulk Upload Form Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <form action="{{ route('online-exams.question-bank.bulk-upload.process') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Class <span class="text-rose-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-bold text-slate-700">JSON Question Array Data <span class="text-rose-500">*</span></label>
                    <button type="button" onclick="loadSampleQuestions()" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold flex items-center space-x-1">
                        <i class="fa-solid fa-code text-[11px]"></i>
                        <span>Load Sample Questions JSON</span>
                    </button>
                </div>
                <textarea id="jsonDataInput" name="json_data" rows="12" required placeholder='[
  {
    "question": "What is the capital of India?",
    "type": "single_choice",
    "difficulty": "Easy",
    "marks": 1.0,
    "options": [
      {"key": "A", "text": "New Delhi"},
      {"key": "B", "text": "Mumbai"},
      {"key": "C", "text": "Kolkata"},
      {"key": "D", "text": "Chennai"}
    ],
    "correct": ["A"],
    "solution": "New Delhi is the official national capital of India."
  }
]' class="w-full text-xs font-mono rounded-xl border border-slate-200 p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                <a href="{{ route('online-exams.question-bank') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-upload"></i>
                    <span>Execute Bulk Import</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadSampleQuestions() {
    const sample = [
        {
            "question": "Which gas is primarily responsible for the greenhouse effect on Earth?",
            "type": "single_choice",
            "difficulty": "Easy",
            "marks": 1.0,
            "options": [
                {"key": "A", "text": "Carbon Dioxide (CO2)"},
                {"key": "B", "text": "Nitrogen (N2)"},
                {"key": "C", "text": "Argon (Ar)"},
                {"key": "D", "text": "Oxygen (O2)"}
            ],
            "correct": ["A"],
            "solution": "Carbon dioxide is a major greenhouse gas absorbing and re-radiating infrared heat."
        },
        {
            "question": "Newton's First Law of Motion is also widely known as the Law of:",
            "type": "single_choice",
            "difficulty": "Easy",
            "marks": 2.0,
            "options": [
                {"key": "A", "text": "Inertia"},
                {"key": "B", "text": "Momentum"},
                {"key": "C", "text": "Action and Reaction"},
                {"key": "D", "text": "Gravitation"}
            ],
            "correct": ["A"],
            "solution": "Newton's first law states that an object continues in its state of rest or uniform motion unless acted upon by a net external force (inertia)."
        }
    ];

    document.getElementById('jsonDataInput').value = JSON.stringify(sample, null, 2);
}
</script>
@endsection

