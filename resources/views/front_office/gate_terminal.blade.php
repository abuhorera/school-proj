@extends('layouts.app')

@section('title', 'Gate Terminal - Security Control - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- Terminal Header Screen -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 border border-slate-800 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-800/80">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-barcode"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-lg font-bold text-white tracking-wide">Main Gate 1 — Security Control Console</h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                            ONLINE ACTIVE
                        </span>
                    </div>
                    <p class="text-xs text-slate-400">Scan student gate pass RFID barcode or enter pass number for departure/return verification</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('front-office.gate-passes') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg border border-slate-700 transition">
                    &larr; Passes Registry
                </a>
                <a href="{{ route('front-office.gate-terminal') }}" class="px-3.5 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-lg border border-slate-700 transition">
                    Clear Screen
                </a>
            </div>
        </div>

        <!-- Scanner Input Bar -->
        <div class="pt-5 max-w-2xl">
            <form method="GET" action="{{ route('front-office.gate-terminal') }}" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <i class="fa-solid fa-qrcode absolute left-3.5 top-3.5 text-emerald-400 text-base"></i>
                    <input type="text" name="pass" value="{{ $queryPass }}" autofocus placeholder="Scan Barcode / Enter Pass # (e.g. GP-2026-101)..." class="w-full bg-slate-900 text-white font-mono text-sm rounded-xl pl-11 pr-4 py-3 border-2 border-slate-700 focus:outline-none focus:border-emerald-500 transition shadow-inner">
                </div>
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition shadow-lg flex items-center space-x-2">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Verify Pass</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Scanner Output Area: Found Pass vs Stream -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Verified Candidate Gate Card (8 cols) -->
        <div class="lg:col-span-8">
            @if($foundPass)
                <div class="bg-white rounded-2xl border-2 border-emerald-400 shadow-xl overflow-hidden animate-in fade-in duration-200">
                    <div class="p-4 bg-emerald-600 text-white flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                            <span class="text-sm font-bold uppercase tracking-wider">Gate Pass Verified Authenticated</span>
                        </div>
                        <span class="font-mono text-xs font-bold bg-white/20 px-2.5 py-1 rounded">
                            {{ $foundPass->pass_number }}
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 pb-6 border-b border-slate-100">
                            <!-- Student Details -->
                            <div class="flex items-start space-x-4">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-700 font-bold text-xl flex items-center justify-center shrink-0 border border-indigo-200">
                                    {{ substr($foundPass->visitor_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">Student Candidate</div>
                                    <h3 class="text-xl font-bold text-slate-800 leading-tight">{{ $foundPass->visitor_name }}</h3>
                                    <div class="text-xs text-slate-500 mt-1">
                                        Admission #: <span class="font-mono font-bold text-slate-700">{{ $foundPass->student->admission_no ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $foundPass->status == 'out' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        Status: {{ strtoupper($foundPass->status) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Escort Details -->
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 min-w-64 space-y-2">
                                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Authorized Escort</div>
                                <div class="font-bold text-sm text-slate-800">{{ $foundPass->escort_name }}</div>
                                <div class="text-xs text-slate-600 flex items-center justify-between">
                                    <span>Relation:</span>
                                    <span class="font-semibold text-slate-800">{{ $foundPass->escort_relation }}</span>
                                </div>
                                <div class="text-xs text-slate-600 flex items-center justify-between">
                                    <span>Contact:</span>
                                    <span class="font-mono font-semibold text-slate-800">{{ $foundPass->escort_phone }}</span>
                                </div>
                                <div class="text-xs text-slate-600 flex items-center justify-between">
                                    <span>ID Verified:</span>
                                    <span class="text-emerald-700 font-semibold">{{ $foundPass->escort_id_proof ?? 'Verified' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Departure Context -->
                        <div class="py-4 border-b border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                            <div>
                                <span class="text-slate-400 block mb-0.5">Stated Reason:</span>
                                <span class="font-bold text-slate-800">{{ $foundPass->purpose }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block mb-0.5">Departure Gate:</span>
                                <span class="font-bold text-slate-800">{{ $foundPass->gate_name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block mb-0.5">Departure Time:</span>
                                <span class="font-semibold text-slate-700">{{ $foundPass->out_time ? date('h:i A', strtotime($foundPass->out_time)) : 'Not Departed Yet' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block mb-0.5">Return Expected:</span>
                                <span class="font-semibold text-slate-700">{{ $foundPass->is_return_expected ? 'Yes (Today)' : 'No (Day End)' }}</span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="py-3 text-xs text-slate-600 italic">
                            "{{ $foundPass->reason_text ?? 'Authorized early release by guardian and administration.' }}"
                        </div>

                        <!-- Big Action Trigger -->
                        <div class="pt-4 flex items-center justify-end space-x-3">
                            @if($foundPass->status != 'out')
                                <form method="POST" action="{{ route('front-office.gate-passes.status', $foundPass->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="out">
                                    <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white rounded-xl font-bold text-xs shadow-lg transition flex items-center space-x-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        <span>AUTHORIZE CHECK OUT (DEPARTURE)</span>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('front-office.gate-passes.status', $foundPass->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="returned">
                                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs shadow-lg transition flex items-center space-x-2">
                                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                        <span>AUTHORIZE RETURN (CHECK IN)</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Scanner Waiting for Gate Pass</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                        Scan the student barcode pass or type pass reference number into the top scanner box to load authorized verification card.
                    </p>
                </div>
            @endif
        </div>

        <!-- Right: Today's Gate Passes Stream (4 cols) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-bold text-slate-800">Today's Gate Stream</h4>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700">
                        {{ $todayPasses->count() }} Issued
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($todayPasses as $p)
                        <a href="{{ route('front-office.gate-terminal', ['pass' => $p->pass_number]) }}" class="block p-3 rounded-xl border border-slate-100 hover:border-emerald-300 hover:bg-emerald-50/20 transition group">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-mono font-bold text-indigo-600 group-hover:text-emerald-700">{{ $p->pass_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $p->status == 'out' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ strtoupper($p->status) }}
                                </span>
                            </div>
                            <div class="font-bold text-slate-800 text-xs">{{ $p->visitor_name }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 flex items-center justify-between">
                                <span>Escort: {{ $p->escort_name }}</span>
                                <span>{{ $p->out_time ? date('h:i A', strtotime($p->out_time)) : 'Pending' }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400">
                            No gate passes created today.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

