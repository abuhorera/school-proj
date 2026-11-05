@extends('layouts.app')

@section('title', 'Compliance & Regulatory Vault')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Management /</span>
                <h1 class="text-xl font-bold text-slate-900">Compliance & Regulatory Vault</h1>
            </div>
            <p class="text-xs text-slate-500">Government disclosures, statutory safety NOCs, CBSE affiliation documents, and inspection readiness</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('management.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Management Hub
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base">
                <i class="fa-solid fa-certificate"></i>
            </div>
            <div>
                <h3 class="font-bold text-emerald-900 text-sm">Institution Compliant with Statutory Authorities</h3>
                <p class="text-emerald-700 mt-0.5">All 4 mandatory CBSE and municipal safety NOCs are verified and active for Session 2026-27.</p>
            </div>
        </div>
        <span class="bg-emerald-600 text-white font-black text-xs px-3 py-1.5 rounded-xl uppercase tracking-wider">
            100% Score
        </span>
    </div>

    <!-- Compliance Documents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($complianceItems as $ci)
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $ci->category }}</span>
                        <h4 class="font-bold text-sm text-slate-900 mt-0.5">{{ $ci->title }}</h4>
                        <div class="text-[11px] text-slate-500 mt-1">
                            <strong>Authority:</strong> {{ $ci->issuing_authority }}
                        </div>
                    </div>
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase">
                        {{ $ci->status }}
                    </span>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <div class="text-[11px] text-slate-500">
                        Valid: <strong class="text-slate-800">{{ date('d M Y', strtotime($ci->issue_date)) }}</strong> to <strong class="text-emerald-700">{{ date('d M Y', strtotime($ci->expiry_date)) }}</strong>
                    </div>
                    <button class="text-indigo-600 hover:text-indigo-800 font-bold text-xs flex items-center space-x-1">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>View Certificate</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

