@extends('layouts.app')

@section('title', 'QR & Biometric Devices Hub')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">Management /</span>
                <h1 class="text-xl font-bold text-slate-900">QR Code Attendance & Biometric Devices</h1>
            </div>
            <p class="text-xs text-slate-500">Hardware gate scanners, optical facial recognition cameras, and realtime punch sync logs</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('management.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Management Hub
            </a>
        </div>
    </div>

    <!-- Device Status Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-900">Gate 1 QR Scanner</h4>
                    <span class="text-[10px] text-slate-400">Main Student Entrance</span>
                </div>
            </div>
            <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>ONLINE</span>
            </span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-900">Admin Biometric Punch</h4>
                    <span class="text-[10px] text-slate-400">Staff Room Terminal A</span>
                </div>
            </div>
            <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>ONLINE</span>
            </span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-900">Face Monitoring Cam</h4>
                    <span class="text-[10px] text-slate-400">Foyer Arch Scanner</span>
                </div>
            </div>
            <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>ONLINE</span>
            </span>
        </div>
    </div>

    <!-- Live Punch Stream -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-satellite-dish text-teal-600"></i>
                <span>Live Device Punch & Scan Audit</span>
            </h2>
            <span class="text-xs bg-slate-100 font-bold px-2.5 py-0.5 rounded-full text-slate-600">Continuous Poll</span>
        </div>

        <div class="space-y-2 text-xs">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-check-circle text-emerald-500 text-sm"></i>
                    <div>
                        <div class="font-bold text-slate-800">Sunita Sharma (EMP-002)</div>
                        <div class="text-[10px] text-slate-400">Staff Room Terminal A • Fingerprint Verified</div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="font-mono font-bold text-slate-700">08:14:22 AM</span>
                    <span class="text-[10px] text-emerald-600 font-semibold block">In-Time Recorded</span>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-check-circle text-emerald-500 text-sm"></i>
                    <div>
                        <div class="font-bold text-slate-800">Dr. Arvind Mehta (EMP-001)</div>
                        <div class="text-[10px] text-slate-400">Staff Room Terminal A • Facial Recognition Verified</div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="font-mono font-bold text-slate-700">08:05:10 AM</span>
                    <span class="text-[10px] text-emerald-600 font-semibold block">In-Time Recorded</span>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-check-circle text-blue-500 text-sm"></i>
                    <div>
                        <div class="font-bold text-slate-800">Kabir Singh (YISADM-004)</div>
                        <div class="text-[10px] text-slate-400">Gate 1 QR Scanner • Parent ID Badge Verified</div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="font-mono font-bold text-slate-700">08:29:45 AM</span>
                    <span class="text-[10px] text-blue-600 font-semibold block">Student In-Time</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

