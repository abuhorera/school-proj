@extends('layouts.app')

@section('title', 'Chapters & Topics - Online Examinations')

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

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-book-bookmark"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Chapters & Syllabus Topics</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Organize questions by textbook chapters, units, and subject concept domains</p>
        </div>
        <div>
            <button onclick="document.getElementById('addTopicModal').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add Chapter / Topic</span>
            </button>
        </div>
    </div>

    <!-- Topics Master Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Configured Chapters & Learning Units ({{ $topics->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Chapter / Topic / Sub-topic</th>
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">Questions</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($topics as $index => $t)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">{{ $t->name }}</div>
                            @if($t->chapter)
                            <span class="text-[10px] text-slate-400 font-mono">{{ $t->chapter }}</span>
                            @endif
                            @if($t->description)
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $t->description }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded text-[11px]">
                                {{ $t->subject->name ?? 'General' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('online-exams.question-bank', ['subject_id' => $t->subject_id]) }}" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center space-x-1">
                                <i class="fa-solid fa-list-check text-[9px]"></i>
                                <span>{{ $t->questions_count }} Questions</span>
                            </a>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <form action="{{ route('online-exams.question-topics.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Remove this topic?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Topic">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-book-bookmark text-3xl text-slate-300 mb-2"></i>
                            <p>No chapters or topics added yet.</p>
                            <button onclick="document.getElementById('addTopicModal').classList.remove('hidden')" class="mt-2 text-indigo-600 font-bold text-xs hover:underline">Add Chapter / Topic</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Topic / Chapter -->
<div id="addTopicModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add Chapter / Syllabus Topic</h3>
            <button onclick="document.getElementById('addTopicModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('online-exams.question-topics.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Chapter / Unit Identifier</label>
                <input type="text" name="chapter" placeholder="e.g. Chapter 3, Unit 1, Module B" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Topic Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Linear Equations in Two Variables" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description / Focus Areas</label>
                <textarea name="description" rows="2" placeholder="Concepts, theorems, and learning objectives covered..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addTopicModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Topic</button>
            </div>
        </form>
    </div>
</div>
@endsection

