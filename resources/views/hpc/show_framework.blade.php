@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-5xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('hpc.frameworks') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Card Editions</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <!-- Framework Banner -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6 shadow-sm">
        <div class="flex items-center gap-3 mb-2 flex-wrap">
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                {{ $framework->stage }} Stage
            </span>
            <span class="font-mono text-xs text-slate-400">{{ $framework->code }}</span>
        </div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
            {{ $framework->title }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-4">
            {{ $framework->description ?? 'PARAKH NEP-2020 Holistic Progress Card edition.' }}
        </p>

        @if($framework->is_locked)
            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-200 flex items-start gap-2.5">
                <i class="fa-solid fa-lock text-amber-600 mt-0.5"></i>
                <div>
                    Cards have been issued against this framework, so its rubric schema is locked. Changing competency definitions would alter what an already-issued card measures.
                </div>
            </div>
        @endif
    </div>

    <!-- Section 1: Assign Classes -->
    <div id="classes" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6 shadow-sm">
        <div class="pb-4 border-b border-slate-100 dark:border-slate-700 mb-4">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                <i class="fa-solid fa-chalkboard-user text-violet-600"></i>
                <span>Classes using this edition ({{ count($assignedClassIds) }})</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Tick the classes that issue this card. Once a class is assigned, a teacher creating cards for it never sees an edition list — the right card is chosen automatically. A class belongs to one edition only, so ticking it here moves it off any other edition.
            </p>
        </div>

        <form method="POST" action="{{ route('hpc.frameworks.classes', $framework->id) }}">
            @csrf

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6 max-h-72 overflow-y-auto pr-1">
                @foreach($classes as $c)
                    @php $isAssigned = in_array($c->id, $assignedClassIds); @endphp
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border {{ $isAssigned ? 'border-violet-300 bg-violet-50/50 dark:bg-violet-950/30 dark:border-violet-700 font-semibold text-violet-900 dark:text-violet-200' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/40 text-slate-700 dark:text-slate-300' }} cursor-pointer transition text-xs">
                        <input type="checkbox" name="school_class_ids[]" value="{{ $c->id }}" {{ $isAssigned ? 'checked' : '' }} class="rounded text-violet-600 focus:ring-violet-500">
                        <span>{{ $c->name }}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                    Save Class Assignment
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Attainment Levels -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6 shadow-sm">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-signal text-emerald-600"></i>
            <span>Attainment Levels ({{ count($framework->levels_json ?? []) }})</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($framework->levels_json ?? [] as $lvl)
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300 font-black text-sm flex items-center justify-center mb-2">
                        {{ $lvl['code'] }}
                    </div>
                    <strong class="text-xs text-slate-900 dark:text-white block">{{ $lvl['name'] }}</strong>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">{{ $lvl['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section 3: Domains & Competencies -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6 shadow-sm">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-shapes text-blue-600"></i>
            <span>Developmental Domains ({{ count($framework->domains_json ?? []) }})</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($framework->domains_json ?? [] as $dom)
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300">{{ $dom['code'] ?? 'DOM' }}</span>
                        <strong class="text-xs text-slate-900 dark:text-white">{{ $dom['name'] }}</strong>
                    </div>
                    <ul class="space-y-1 text-xs text-slate-600 dark:text-slate-400 pl-4 list-disc">
                        @foreach($dom['descriptors'] ?? [] as $desc)
                            <li>{{ $desc }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section 4: Parts Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-table-list text-pink-600"></i>
                <span>Parts of this Card</span>
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase">
                    <tr>
                        <th class="px-4 py-3">Part</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Filled By</th>
                        <th class="px-4 py-3 text-right">Mandatory</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($framework->parts_json ?? [] as $p)
                        <tr>
                            <td class="px-4 py-3 font-bold text-violet-700 dark:text-violet-400">{{ $p['code'] }}</td>
                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $p['title'] }}</td>
                            <td class="px-4 py-3">{{ $p['filled_by'] }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ ($p['required'] ?? true) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ($p['required'] ?? true) ? 'Required' : 'Optional' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

