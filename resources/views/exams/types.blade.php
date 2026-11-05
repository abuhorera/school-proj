@extends('layouts.app')

@section('title', 'Manage Exam Types')

@section('content')
<div class="space-y-6">
    @include('exams.partials.subnav')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Master Exam Types Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Configured Exam Assessment Types</h3>
                    <p class="text-[11px] text-slate-400">Classifies components (Theory, Practical, Oral, Internal, Portfolio)</p>
                </div>
                <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">{{ $types->count() }} TYPES</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="p-3.5">#</th>
                            <th class="p-3.5">Assessment Type Name</th>
                            <th class="p-3.5">Abbreviation</th>
                            <th class="p-3.5">Weightage</th>
                            <th class="p-3.5">Sort</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($types as $idx => $t)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-800">{{ $t->name }}</span>
                                @if($t->description)
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->description }}</p>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ $t->abbreviation }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600 font-medium">
                                {{ $t->weightage }}
                            </td>
                            <td class="p-3.5 text-slate-500">
                                {{ $t->sort_order }}
                            </td>
                            <td class="p-3.5 text-right">
                                <form action="{{ route('exams.types.delete', $t->id) }}" method="POST" onsubmit="return confirm('Remove exam type \'{{ addslashes($t->name) }}\'?');" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Delete Type">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">No exam types configured.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 1 Col: Add New Exam Type Form -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Add Evaluation Component</h4>
            <form action="{{ route('exams.types.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Type Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Oral / Viva Voce" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Abbreviation (Short Code)</label>
                    <input type="text" name="abbreviation" placeholder="e.g. VV / PR" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Weightage Percentage</label>
                    <input type="text" name="weightage" value="20%" placeholder="e.g. 20%" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Sort Order Position</label>
                    <input type="number" name="sort_order" value="{{ $types->count() + 1 }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="2" placeholder="e.g. Verbal questioning and pronunciation assessment..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs"></textarea>
                </div>
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition shadow-xs">
                    Save Assessment Type
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

