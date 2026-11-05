@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('hpc.partials.subnav', [
        'pageTitle' => 'Holistic Progress Card',
        'pageSubtitle' => 'A whole-child report — the three abilities, no marks or grades'
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

    <!-- Action Alerts / Notice Callouts -->
    <div class="space-y-3 mb-6">
        @if($unassignedClassesCount > 0)
            <div class="p-3.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 flex items-center justify-center font-bold text-sm shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="text-xs sm:text-sm text-amber-900 dark:text-amber-200">
                        <span class="font-semibold">{{ $unassignedClassesCount }} class(es)</span> are not on an edition yet — teachers there will be asked which card to use.
                    </div>
                </div>
                <a href="{{ route('hpc.frameworks') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition shrink-0 self-start sm:self-auto">
                    <span>Assign classes</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        @endif

        @if($submittedCardsCount > 0)
            <div class="p-3.5 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/80 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 flex items-center justify-center font-bold text-sm shrink-0">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <div class="text-xs sm:text-sm text-blue-900 dark:text-blue-200">
                        <span class="font-semibold">{{ $submittedCardsCount }} card(s)</span> are waiting for your review.
                    </div>
                </div>
                <a href="{{ route('hpc.cards', ['status' => 'teacher_submitted']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shrink-0 self-start sm:self-auto">
                    <span>Review now</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        @endif

        @if($reviewedCardsCount > 0)
            <div class="p-3.5 bg-violet-50 dark:bg-violet-950/40 border border-violet-200 dark:border-violet-800/80 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/60 text-violet-700 dark:text-violet-300 flex items-center justify-center font-bold text-sm shrink-0">
                        <i class="fa-solid fa-stamp"></i>
                    </div>
                    <div class="text-xs sm:text-sm text-violet-900 dark:text-violet-200">
                        <span class="font-semibold">{{ $reviewedCardsCount }} reviewed card(s)</span> are ready to publish.
                    </div>
                </div>
                <a href="{{ route('hpc.cards', ['status' => 'reviewed']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition shrink-0 self-start sm:self-auto">
                    <span>Publish</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        @endif
    </div>

    <!-- 4 KPI Stat Boxes -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Editions Adopted</span>
                <span class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-layer-group"></i>
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $activeFrameworksCount }}</div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Stage editions configured</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Classes On Edition</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $assignedClassesCount }}</div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $unassignedClassesCount }} not yet assigned</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cards This Year</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-address-card"></i>
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $totalCardsCount }}</div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $publishedCardsCount }} published · {{ $draftCardsCount }} draft</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Activities Set Up</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-shapes"></i>
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $totalActivitiesCount }}</div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pedagogical activities logged</p>
        </div>
    </div>

    <!-- How it works — your steps (5 Cards) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-violet-600"></i>
            <span>How it works — your steps</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Step 1 -->
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900/60 text-violet-700 dark:text-violet-300 font-bold text-xs flex items-center justify-center mb-2.5">
                        1
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white mb-1">Adopt an edition</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-3">
                        Pick the card edition for your stage. You get your own copy to issue from.
                    </p>
                </div>
                <a href="{{ route('hpc.frameworks') }}" class="text-[11px] font-semibold text-violet-600 dark:text-violet-400 hover:underline inline-flex items-center gap-1">
                    <span>Card Editions</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>

            <!-- Step 2 -->
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900/60 text-violet-700 dark:text-violet-300 font-bold text-xs flex items-center justify-center mb-2.5">
                        2
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white mb-1">Assign your classes</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-3">
                        Say which classes use each edition, so teachers are never asked which card to use.
                    </p>
                </div>
                <a href="{{ route('hpc.frameworks') }}" class="text-[11px] font-semibold text-violet-600 dark:text-violet-400 hover:underline inline-flex items-center gap-1">
                    <span>Assign classes</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>

            <!-- Step 3 -->
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900/60 text-violet-700 dark:text-violet-300 font-bold text-xs flex items-center justify-center mb-2.5">
                        3
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white mb-1">Create cards for a class</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-3">
                        One click makes a card for every learner in the class.
                    </p>
                </div>
                <a href="{{ route('hpc.cards.create') }}" class="text-[11px] font-semibold text-violet-600 dark:text-violet-400 hover:underline inline-flex items-center gap-1">
                    <span>Create cards</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>

            <!-- Step 4 -->
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-full bg-violet-100 dark:bg-violet-900/60 text-violet-700 dark:text-violet-300 font-bold text-xs flex items-center justify-center mb-2.5">
                        4
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white mb-1">Fill in the parts</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-3">
                        Type the written parts; tick the assessment grids. Some parts the parent fills.
                    </p>
                </div>
                <a href="{{ route('hpc.cards') }}" class="text-[11px] font-semibold text-violet-600 dark:text-violet-400 hover:underline inline-flex items-center gap-1">
                    <span>Progress Cards</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>

            <!-- Step 5 -->
            <div class="p-4 rounded-xl bg-violet-50/60 dark:bg-violet-950/30 border border-violet-200 dark:border-violet-800/80 flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-full bg-violet-600 text-white font-bold text-xs flex items-center justify-center mb-2.5">
                        5
                    </div>
                    <h3 class="text-xs font-bold text-violet-900 dark:text-violet-200 mb-1">Review, publish & hand out</h3>
                    <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
                        Send for review, publish, then print the official card — or the colour card for parents.
                    </p>
                </div>
                <a href="{{ route('hpc.cards') }}" class="text-[11px] font-bold text-violet-700 dark:text-violet-300 hover:underline inline-flex items-center gap-1">
                    <span>Start here</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Shortcuts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('hpc.cards') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-violet-400 transition shadow-sm group">
            <div class="flex items-center gap-3.5 mb-2">
                <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 flex items-center justify-center text-base group-hover:scale-110 transition">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-violet-600 transition">Progress Cards</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Create, fill in, review and print cards</p>
                </div>
            </div>
            <div class="text-xs text-violet-600 font-semibold flex items-center gap-1 mt-2">
                <span>View all cards</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </div>
        </a>

        <a href="{{ route('hpc.activities') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-violet-400 transition shadow-sm group">
            <div class="flex items-center gap-3.5 mb-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base group-hover:scale-110 transition">
                    <i class="fa-solid fa-shapes"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 transition">Activities</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Set up and observe classroom activities</p>
                </div>
            </div>
            <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-2">
                <span>View activities</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </div>
        </a>

        <a href="{{ route('hpc.card-appearance') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-violet-400 transition shadow-sm group">
            <div class="flex items-center gap-3.5 mb-2">
                <div class="w-10 h-10 rounded-xl bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400 flex items-center justify-center text-base group-hover:scale-110 transition">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-pink-600 transition">Card Appearance</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Emblem, custom stamps, & colour themes</p>
                </div>
            </div>
            <div class="text-xs text-pink-600 font-semibold flex items-center gap-1 mt-2">
                <span>Configure styling</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </div>
        </a>
    </div>

    <!-- Recent Cards Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Recent Progress Cards</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Latest multidimensional learner cards generated</p>
            </div>
            <a href="{{ route('hpc.cards') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-700">View All &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Learner</th>
                        <th class="px-4 py-3">Class</th>
                        <th class="px-4 py-3">Stage</th>
                        <th class="px-4 py-3">Term</th>
                        <th class="px-4 py-3">Filled</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($recentCards as $card)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-900 dark:text-white">
                                    {{ $card->student->full_name ?? ($card->student->first_name . ' ' . $card->student->last_name) }}
                                </div>
                                <div class="text-[10px] text-slate-400">{{ $card->student->admission_no ?? 'ADM-' . $card->student_id }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ $card->schoolClass->name ?? 'Class' }}</span>
                                <span class="text-[10px] text-slate-400 block">Roll {{ $card->student->roll_no ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                                    {{ $card->stage }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $card->term }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $card->parts_filled_count }}/{{ $card->total_parts_count }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($card->status === 'published')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Published</span>
                                @elseif($card->status === 'reviewed')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Reviewed</span>
                                @elseif($card->status === 'teacher_submitted')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Submitted</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('hpc.cards.show', $card->id) }}" class="px-2.5 py-1 text-[11px] font-semibold bg-violet-600 hover:bg-violet-700 text-white rounded-md transition">Open</a>
                                    <a href="{{ route('hpc.cards.preview', $card->id) }}" class="px-2 py-1 text-[11px] text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-md transition" title="Printable Card">Preview</a>
                                    <a href="{{ route('hpc.cards.colour', $card->id) }}" class="px-2 py-1 text-[11px] text-violet-600 hover:bg-violet-50 dark:hover:bg-slate-700 rounded-md transition" title="Colour Card for Parents">Colour</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                No cards created yet. Click "Create Cards" to generate progress cards for a class.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

