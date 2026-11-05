@extends('layouts.app')

@section('title', 'Management Command Hub')

@section('content')
<div class="space-y-6">

    <!-- Top Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 rounded-2xl shadow-md border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-400/40 flex items-center justify-center text-indigo-400 text-lg">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">Management Control Center</h1>
                    <p class="text-xs text-indigo-200/80">Complete operational governance suite covering all 21 institutional modules</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold px-3 py-1.5 rounded-xl flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>All 21 Modules Active</span>
            </span>
        </div>
    </div>

    <!-- Quick Stat Strip for Management Operations -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Lead Inquiries</span>
            <span class="text-xl font-black text-indigo-600 mt-1 block">{{ $counts['enquiries'] }} Active</span>
            <span class="text-[10px] text-slate-400">Admission pipeline</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Inside Campus</span>
            <span class="text-xl font-black text-cyan-600 mt-1 block">{{ $counts['visitors'] }} Visitors</span>
            <span class="text-[10px] text-slate-400">Gate pass verified</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Bank Balances</span>
            <span class="text-xl font-black text-emerald-600 mt-1 block">₹{{ number_format($counts['bank_balance'] / 100000, 1) }}L</span>
            <span class="text-[10px] text-slate-400">2 Bank Accounts</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Classrooms</span>
            <span class="text-xl font-black text-orange-600 mt-1 block">{{ $counts['rooms'] }} Rooms</span>
            <span class="text-[10px] text-slate-400">3 Campus Blocks</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">PTM Meetings</span>
            <span class="text-xl font-black text-amber-600 mt-1 block">{{ $counts['ptm_scheduled'] }} Scheduled</span>
            <span class="text-[10px] text-slate-400">Upcoming term review</span>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Compliance</span>
            <span class="text-xl font-black text-emerald-600 mt-1 block">{{ $counts['compliance_items'] }}/4 Valid</span>
            <span class="text-[10px] text-emerald-600 font-semibold">CBSE & Fire NOC</span>
        </div>
    </div>

    <!-- Management Modules Grid (21 Modules) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-table-cells-large text-indigo-600"></i>
                <span>Management Modules Directory</span>
            </h2>
            <span class="text-xs text-slate-500">Studied from Multi School ERP v3.8</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- 1. Front Office & Gate Terminal -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-cyan-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-id-card-clip"></i>
                        </div>
                        <span class="bg-cyan-50 text-cyan-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Essential</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Front Office & Lead Pipeline</h3>
                    <p class="text-xs text-slate-500 mt-1">Admission inquiries, visitor book, postal records, complaints triage, and gate terminal.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">{{ $counts['enquiries'] }} Leads • {{ $counts['visitors'] }} Visitors</span>
                    <a href="{{ route('management.front-office') }}" class="text-xs font-bold text-cyan-600 hover:text-cyan-800 flex items-center space-x-1">
                        <span>Open Desk</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Accounts & Bank Books -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-emerald-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Finance</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Accounts & Ledger</h3>
                    <p class="text-xs text-slate-500 mt-1">Income heads, expense heads, general cashbook, and institutional bank accounts.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-emerald-600">₹{{ number_format($counts['incomes'], 0) }} Income Recorded</span>
                    <a href="{{ route('management.accounts') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center space-x-1">
                        <span>Open Accounts</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Classrooms & Infrastructure -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-orange-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-school-flag"></i>
                        </div>
                        <span class="bg-orange-50 text-orange-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Campus</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Classrooms & Buildings</h3>
                    <p class="text-xs text-slate-500 mt-1">Building blocks, room plans, capacity tracking, collision clash detection, and bookings.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">{{ $counts['rooms'] }} Rooms • {{ $counts['buildings'] }} Blocks</span>
                    <a href="{{ route('management.classrooms') }}" class="text-xs font-bold text-orange-600 hover:text-orange-800 flex items-center space-x-1">
                        <span>Manage Rooms</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 4. PTM & Lesson Planner -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-amber-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-handshake"></i>
                        </div>
                        <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Academics</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">PTM & Lesson Planner</h3>
                    <p class="text-xs text-slate-500 mt-1">Parent-Teacher conference schedules, weekly lesson plans, syllabus coverage audits.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">{{ $counts['ptm_scheduled'] }} PTMs • {{ $counts['lesson_plans'] }} Plans</span>
                    <a href="{{ route('management.ptm-lessons') }}" class="text-xs font-bold text-amber-600 hover:text-amber-800 flex items-center space-x-1">
                        <span>Open Planner</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 5. Compliance & Governance Vault -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-rose-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">100% Compliant</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Compliance & Regulatory Vault</h3>
                    <p class="text-xs text-slate-500 mt-1">CBSE mandatory public disclosure, fire safety NOC, building stability, and health certificates.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">4 Certificates Verified</span>
                    <a href="{{ route('management.compliance') }}" class="text-xs font-bold text-rose-600 hover:text-rose-800 flex items-center space-x-1">
                        <span>Inspect Vault</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 6. QR Attendance & Biometric Devices -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-teal-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-fingerprint"></i>
                        </div>
                        <span class="bg-teal-50 text-teal-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Hardware</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">QR & Biometric Devices</h3>
                    <p class="text-xs text-slate-500 mt-1">Terminal scanners, face monitoring cameras, biometric punch sync, and scan audit trails.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-emerald-600">Gate Agent Online</span>
                    <a href="{{ route('management.devices') }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 flex items-center space-x-1">
                        <span>Device Status</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 7. HPC & Competency Based Curriculum (CBC) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-violet-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        <span class="bg-violet-50 text-violet-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">NEP 2020</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Holistic Progress Card (HPC) & CBC</h3>
                    <p class="text-xs text-slate-500 mt-1">Competency strands, core learning outcomes, 360-degree holistic cards, and skill matrices.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">NEP 2020 Compliant</span>
                    <a href="{{ route('management.hpc-cbc') }}" class="text-xs font-bold text-violet-600 hover:text-violet-800 flex items-center space-x-1">
                        <span>Explore Framework</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 8. Online Examinations & Question Bank -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-blue-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Digital</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">Online Exams & OSM Module</h3>
                    <p class="text-xs text-slate-500 mt-1">Computer-based testing (CBT), question bank by chapter, and On-Screen Marking (OSM) evaluation.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-400">Digital Paper Evaluation</span>
                    <a href="{{ route('exams.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                        <span>Manage Exams</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- 9. School Website & CMS Builder -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-slate-400 transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg font-bold">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">CMS</span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-900">School Website CMS</h3>
                    <p class="text-xs text-slate-500 mt-1">Hero slides, public facilities, parent testimonials, and CBSE mandatory disclosure website portal.</p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-emerald-600">Website Live</span>
                    <a href="#" class="text-xs font-bold text-slate-700 hover:text-slate-900 flex items-center space-x-1">
                        <span>Edit Website</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

