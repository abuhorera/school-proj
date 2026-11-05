@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('hpc.partials.subnav', [
        'pageTitle' => 'Card Editions',
        'pageSubtitle' => 'Stage-specific NEP 2020 & PARAKH holistic assessment frameworks and competency rubrics'
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

    <div class="space-y-6">
        @foreach($frameworks as $fw)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap mb-1.5">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                                {{ $fw->title }}
                            </h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                                {{ $fw->stage }} Stage
                            </span>
                            <span class="text-xs text-slate-400 font-mono">
                                {{ $fw->code }}
                            </span>
                            @if($fw->is_locked)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                    <i class="fa-solid fa-lock text-[9px] mr-1"></i>Locked
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-3xl leading-relaxed">
                            {{ $fw->description ?? 'PARAKH NEP-2020 holistic progress assessment model.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('hpc.frameworks.show', $fw->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span>Assign classes</span>
                        </a>

                        <form method="POST" action="{{ route('hpc.frameworks.toggle', $fw->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 rounded-xl transition">
                                {{ $fw->is_active ? 'Hide' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats Badges -->
                <div class="py-4 flex items-center gap-3 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <i class="fa-solid fa-cube text-violet-600"></i>
                        <span>{{ count($fw->parts_json ?? []) }} Parts</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <i class="fa-solid fa-signal text-emerald-600"></i>
                        <span>{{ count($fw->levels_json ?? []) }} Attainment Levels</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <i class="fa-solid fa-users text-blue-600"></i>
                        <span>{{ $fw->classes->count() }} Classes Assigned</span>
                    </span>
                </div>

                <!-- Assigned Classes Pills -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-2">Classes using this edition:</span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @forelse($fw->classes as $c)
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-300 border border-violet-100 dark:border-violet-900/40">
                                {{ $c->name }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-400 italic">No classes assigned yet. Click "Assign classes" to map grades to this card edition.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

