@extends('layouts.app')

@section('title', 'Question Bank Master - Online Examinations')

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

    <!-- Header bar with Actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-database"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Master Question Bank</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Multi-format items repository: MCQs, True/False, Multiple Response & Descriptive problems</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('online-exams.question-bank.bulk-upload') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200">
                <i class="fa-solid fa-file-import text-slate-500"></i>
                <span>Bulk Upload</span>
            </a>
            <a href="{{ route('online-exams.question-bank.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Question</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('online-exams.question-bank') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Search Prompt / Tags</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search questions..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Subject</label>
                <select name="subject_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ $subjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Question Type</label>
                <select name="type" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    <option value="single_choice" {{ $type == 'single_choice' ? 'selected' : '' }}>Single Choice (MCQ)</option>
                    <option value="true_false" {{ $type == 'true_false' ? 'selected' : '' }}>True / False</option>
                    <option value="multiple_choice" {{ $type == 'multiple_choice' ? 'selected' : '' }}>Multiple Response</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Difficulty</label>
                <select name="difficulty" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Levels</option>
                    <option value="Easy" {{ $difficulty == 'Easy' ? 'selected' : '' }}>Easy</option>
                    <option value="Medium" {{ $difficulty == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="Hard" {{ $difficulty == 'Hard' ? 'selected' : '' }}>Hard</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter Questions</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Questions Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Question Items Repository ({{ $questions->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Question Prompt</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">Topic / Chapter</th>
                        <th class="py-3 px-4">Marks</th>
                        <th class="py-3 px-4">Used</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($questions as $index => $q)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 max-w-md">
                            <div class="font-bold text-slate-900 line-clamp-2 leading-relaxed">{{ $q->question }}</div>
                            @if(is_array($q->options))
                            <div class="flex items-center space-x-1.5 mt-1 text-[10px] text-slate-500">
                                <span class="bg-slate-100 px-1.5 py-0.5 rounded font-bold">{{ count($q->options) }} Choices</span>
                                @if(is_array($q->correct_answers) && count($q->correct_answers) > 0)
                                <span class="text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded">Ans: {{ implode(', ', $q->correct_answers) }}</span>
                                @endif
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase font-mono">
                                {{ str_replace('_', ' ', $q->question_type) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $q->difficulty == 'Easy' ? 'bg-emerald-100 text-emerald-800' : ($q->difficulty == 'Hard' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $q->difficulty }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $q->schoolClass->name ?? 'Class' }}</td>
                        <td class="py-3 px-4 font-semibold text-indigo-700">{{ $q->subject->name ?? 'Subject' }}</td>
                        <td class="py-3 px-4 text-[11px] text-slate-500">
                            {{ $q->topic->name ?? 'General' }}
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $q->marks }}
                            @if($q->negative_marks > 0)
                            <span class="text-[10px] text-rose-500 block">(-{{ $q->negative_marks }})</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                                {{ $q->exams_count }} papers
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <form action="{{ route('online-exams.question-bank.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Remove question from master question bank?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Question">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-10 text-center text-slate-400">
                            <i class="fa-solid fa-database text-3xl text-slate-300 mb-2"></i>
                            <p class="text-sm font-semibold text-slate-600">No questions found in question bank.</p>
                            <a href="{{ route('online-exams.question-bank.create') }}" class="mt-3 inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs">
                                <i class="fa-solid fa-plus mr-1"></i> Add First Question
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questions->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $questions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

