@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('hpc.partials.subnav', [
        'pageTitle' => 'HPC Activities',
        'pageSubtitle' => 'Observe classroom activities, log holistic performance and assess developmental competencies'
    ])

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <!-- Filters & Action Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('hpc.activities') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Activity Type</label>
                <select name="type" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-violet-500">
                    <option value="">All Types</option>
                    @foreach($activityTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Class</label>
                <select name="school_class_id" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-violet-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('school_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Search Activity</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Title or keyword..." class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-violet-500">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 text-xs font-semibold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 rounded-lg transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('hpc.activities') }}" class="px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Activities Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Activity</th>
                        <th class="px-4 py-3.5">Type</th>
                        <th class="px-4 py-3.5">Class</th>
                        <th class="px-4 py-3.5 text-center">Learners</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($activities as $index => $act)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $activities->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3.5 max-w-md">
                                <div class="font-bold text-slate-900 dark:text-white text-sm">
                                    {{ $act->title }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                                        <i class="fa-solid fa-compass text-[9px] mr-1"></i>{{ $act->domain }}
                                    </span>
                                    @if($act->pedagogies_json)
                                        <span class="text-[10px] text-slate-400">
                                            ({{ implode(', ', array_slice($act->pedagogies_json, 0, 2)) }})
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-1 rounded-md text-[10px] font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                    {{ $act->activity_type }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-medium text-slate-800 dark:text-slate-200">
                                {{ $act->schoolClass->name ?? 'Class' }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    {{ $act->marks->count() }} marked
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('hpc.activities.mark', $act->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition shadow-sm">
                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                        <span>Mark</span>
                                    </a>
                                    <form method="POST" action="{{ route('hpc.activities.destroy', $act->id) }}" onsubmit="return confirm('Delete this activity? All associated marks will be removed.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition" title="Delete">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-12 h-12 rounded-full bg-violet-50 dark:bg-violet-950/40 text-violet-600 mx-auto flex items-center justify-center text-lg mb-3">
                                        <i class="fa-solid fa-shapes"></i>
                                    </div>
                                    <p class="font-medium text-slate-700 dark:text-slate-200 mb-1">No activities found</p>
                                    <p class="text-xs text-slate-500 mb-4">Create your first pedagogical activity mapped to NEP 2020 developmental domains.</p>
                                    <a href="{{ route('hpc.activities.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition">
                                        <i class="fa-solid fa-plus"></i> New Activity
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

