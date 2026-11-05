@extends('layouts.app')

@section('title', 'On-Screen Marking (OSM)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1">OSM Digital Evaluation</div>
            <h1 class="text-2xl font-bold text-slate-800">On-Screen Marking (OSM) Console</h1>
            <p class="text-xs text-slate-500 mt-1">Digital evaluation of scanned answer scripts with blind double-marking and rubric annotations.</p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="bg-rose-500/30 text-rose-300 font-bold text-[10px] uppercase px-2.5 py-1 rounded-full border border-rose-500/40">
                Digital Evaluation Active
            </span>
            <h2 class="text-xl font-bold mt-2">CBSE Pattern On-Screen Marking Suite</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-xl leading-relaxed">
                Scan, upload, and digitally evaluate student physical answer booklets. Evaluators can annotate tick marks, crosses, step marks, and rubric scores with automated reconciliation.
            </p>
        </div>
        <div class="flex flex-col gap-2 shrink-0">
            <button onclick="alert('Starting evaluation session')" class="bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition text-center shadow">
                Launch Evaluator Workspace
            </button>
        </div>
    </div>
</div>
@endsection

