@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('hpc.partials.subnav', [
        'pageTitle' => 'Progress Cards',
        'pageSubtitle' => 'Generate, fill in, review and publish 360-degree holistic report cards for every learner'
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

    <!-- Top Action Bar & Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('hpc.cards') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
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
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Status</label>
                <select name="status" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-violet-500">
                    <option value="all">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="teacher_submitted" {{ request('status') == 'teacher_submitted' ? 'selected' : '' }}>Submitted (Pending Review)</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed (Ready to Publish)</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Search Learner</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or admission no." class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-violet-500">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 text-xs font-semibold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 rounded-lg transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('hpc.cards') }}" class="px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Cards Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Learner</th>
                        <th class="px-4 py-3.5">Class</th>
                        <th class="px-4 py-3.5">Stage</th>
                        <th class="px-4 py-3.5">Term</th>
                        <th class="px-4 py-3.5 text-center">Filled in</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($cards as $index => $card)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $cards->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900 dark:text-white">
                                    {{ $card->student->full_name ?? ($card->student->first_name . ' ' . $card->student->last_name) }}
                                </div>
                                <div class="text-[10px] text-slate-400">{{ $card->student->admission_no ?? 'ADM-' . $card->student_id }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ $card->schoolClass->name ?? 'Class' }}</span>
                                <span class="text-[10px] text-slate-400 block">Roll {{ $card->student->roll_no ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                                    {{ $card->stage }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">{{ $card->term }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $card->parts_filled_count }}/{{ $card->total_parts_count }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($card->status === 'published')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        <i class="fa-solid fa-circle-check text-[9px] mr-1"></i>Published
                                    </span>
                                @elseif($card->status === 'reviewed')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                        <i class="fa-solid fa-stamp text-[9px] mr-1"></i>Reviewed
                                    </span>
                                @elseif($card->status === 'teacher_submitted')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                        <i class="fa-solid fa-clock text-[9px] mr-1"></i>Submitted
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('hpc.cards.show', $card->id) }}" class="px-2.5 py-1 text-xs font-semibold bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition shadow-sm">
                                        Open
                                    </a>
                                    <a href="{{ route('hpc.cards.preview', $card->id) }}" class="px-2 py-1 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition" title="Printable Preview">
                                        Preview
                                    </a>
                                    <a href="{{ route('hpc.cards.colour', $card->id) }}" class="px-2 py-1 text-xs text-violet-600 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-slate-700 rounded-lg transition" title="Colour Card for Family">
                                        Colour
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-12 h-12 rounded-full bg-violet-50 dark:bg-violet-950/40 text-violet-600 mx-auto flex items-center justify-center text-lg mb-3">
                                        <i class="fa-solid fa-address-card"></i>
                                    </div>
                                    <p class="font-medium text-slate-700 dark:text-slate-200 mb-1">No progress cards found</p>
                                    <p class="text-xs text-slate-500 mb-4">Click below to generate cards for an entire classroom in one click.</p>
                                    <a href="{{ route('hpc.cards.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 transition">
                                        <i class="fa-solid fa-plus"></i> Create Cards for a Class
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cards->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $cards->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

