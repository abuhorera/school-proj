@extends('layouts.app')

@section('title', 'Live Classes - Study Center')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('modules.study-center') }}" class="hover:text-blue-600 transition">Study Center</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Live Classes</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-video"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Live Classes Hub</h1>
                    <p class="text-xs text-slate-500">Schedule, launch, and monitor virtual classrooms, Zoom meetings, and Google Meet lectures.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="openModal('scheduleClassModal')" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Schedule Live Class</span>
            </button>
            <a href="{{ route('modules.live-classes', ['tab' => $tab === 'settings' ? 'manage' : 'settings']) }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid {{ $tab === 'settings' ? 'fa-chalkboard-user' : 'fa-sliders' }} text-slate-500"></i>
                <span>{{ $tab === 'settings' ? 'Manage Classes' : 'Provider Settings' }}</span>
            </a>
        </div>
    </div>

    <!-- Sub Navigation Pill Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
        <div class="inline-flex space-x-1 bg-slate-100 p-1 rounded-lg text-xs font-semibold">
            <a href="{{ route('modules.live-classes', ['tab' => 'manage']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab !== 'settings' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Manage Live Classes</span>
                <span class="ml-1 bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($sessions) }}</span>
            </a>
            <a href="{{ route('modules.live-classes', ['tab' => 'settings']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'settings' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-sliders"></i>
                <span>Platform & API Settings</span>
            </a>
        </div>

        @if($tab !== 'settings')
            <!-- Quick Filter Pills -->
            <div class="flex items-center space-x-1 text-xs">
                <button type="button" onclick="filterPlatform('all')" class="platform-filter-btn active-filter px-3 py-1.5 rounded-lg font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition">All</button>
                <button type="button" onclick="filterPlatform('Zoom')" class="platform-filter-btn px-3 py-1.5 rounded-lg font-medium text-slate-600 hover:bg-slate-100 transition"><i class="fa-solid fa-video text-blue-500 mr-1"></i> Zoom</button>
                <button type="button" onclick="filterPlatform('Google Meet')" class="platform-filter-btn px-3 py-1.5 rounded-lg font-medium text-slate-600 hover:bg-slate-100 transition"><i class="fa-brands fa-google text-emerald-500 mr-1"></i> Meet</button>
                <button type="button" onclick="filterPlatform('Scheduled')" class="platform-filter-btn px-3 py-1.5 rounded-lg font-medium text-slate-600 hover:bg-slate-100 transition"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-1"></span> Upcoming</button>
            </div>
        @endif
    </div>

    @if($tab === 'settings')
        <!-- ================================================================= -->
        <!-- TAB 2: PLATFORM & API SETTINGS                                    -->
        <!-- ================================================================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Zoom Video Integration -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-video"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Zoom Video Communications</h3>
                            <p class="text-xs text-slate-500">Official Server-to-Server OAuth Integration</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Connected
                    </span>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Zoom Account / Client ID</label>
                        <div class="relative">
                            <input type="text" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs cursor-text focus:outline-none" value="zm_live_client_8493021948" readonly>
                            <button type="button" onclick="navigator.clipboard.writeText('zm_live_client_8493021948')" class="absolute right-2 top-2 text-slate-400 hover:text-slate-600" title="Copy to clipboard">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Client Secret Token</label>
                        <input type="password" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs cursor-text focus:outline-none" value="••••••••••••••••••••••••" readonly>
                    </div>
                    <div class="flex items-center justify-between py-2 border-t border-slate-100">
                        <div>
                            <div class="font-semibold text-slate-800">Auto-Cloud Recording</div>
                            <div class="text-[11px] text-slate-500">Automatically save meeting recordings to Zoom Cloud</div>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center justify-between py-2 border-t border-slate-100">
                        <div>
                            <div class="font-semibold text-slate-800">Enforce Waiting Room</div>
                            <div class="text-[11px] text-slate-500">Host must admit students manually before class</div>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <button type="button" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Test Zoom Connection
                    </button>
                    <button type="button" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">Save Configuration</button>
                </div>
            </div>

            <!-- Google Meet Integration -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="fa-brands fa-google"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Google Workspace & Meet</h3>
                            <p class="text-xs text-slate-500">Google Calendar & Meet Video Links</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                    </span>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Google Workspace Domain</label>
                        <input type="text" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs cursor-text focus:outline-none" value="classroom@yugschool.edu.in" readonly>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Authorized Service Account</label>
                        <input type="text" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs cursor-text focus:outline-none" value="meet-svc@yug-erp-2026.iam.gserviceaccount.com" readonly>
                    </div>
                    <div class="flex items-center justify-between py-2 border-t border-slate-100">
                        <div>
                            <div class="font-semibold text-slate-800">Auto-Save to Google Drive</div>
                            <div class="text-[11px] text-slate-500">Recordings automatically stored in School Drive folder</div>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                    </div>
                    <div class="flex items-center justify-between py-2 border-t border-slate-100">
                        <div>
                            <div class="font-semibold text-slate-800">Student Mute on Entry</div>
                            <div class="text-[11px] text-slate-500">Mute microphone automatically when student joins</div>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <button type="button" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 flex items-center space-x-1">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Verify Google Workspace
                    </button>
                    <button type="button" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">Save Configuration</button>
                </div>
            </div>

            <!-- Jitsi Meet Self-Hosted Server -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-server"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Jitsi Meet (Self-Hosted / Free)</h3>
                            <p class="text-xs text-slate-500">Zero-cost open source video classrooms</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                        Ready
                    </span>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Jitsi Server Domain</label>
                        <input type="text" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs" value="meet.jit.si" readonly>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Room Security Prefix</label>
                        <input type="text" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs" value="YUG-SCH-2026-" readonly>
                    </div>
                </div>
            </div>

            <!-- BigBlueButton Server -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">BigBlueButton (BBB) Server</h3>
                            <p class="text-xs text-slate-500">Interactive whiteboard, breakout rooms & polls</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                        Configured
                    </span>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">BBB Server API Endpoint</label>
                        <input type="text" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs" value="https://bbb.yugschool.edu.in/bigbluebutton/api/" readonly>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Shared Secret Salt</label>
                        <input type="password" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-slate-700 font-mono text-xs" value="••••••••••••••••" readonly>
                    </div>
                </div>
            </div>

        </div>
    @else
        <!-- ================================================================= -->
        <!-- TAB 1: MANAGE LIVE CLASSES VIEW                                   -->
        <!-- ================================================================= -->

        <!-- 4 KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Sessions -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Total Live Sessions</div>
                    <div class="text-2xl font-bold text-slate-900">{{ count($sessions) }}</div>
                    <div class="text-[11px] text-blue-600 font-medium flex items-center">
                        <i class="fa-solid fa-calendar-check mr-1"></i> Academic Term 2026-27
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-video"></i>
                </div>
            </div>

            <!-- Scheduled Sessions -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Scheduled / Upcoming</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $sessions->where('status', 'Scheduled')->count() }}</div>
                    <div class="text-[11px] text-emerald-600 font-medium flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span> Ready to stream
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-broadcast-tower"></i>
                </div>
            </div>

            <!-- Enrolled Students -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Enrolled Students</div>
                    <div class="text-2xl font-bold text-slate-900">140+</div>
                    <div class="text-[11px] text-purple-600 font-medium flex items-center">
                        <i class="fa-solid fa-user-graduate mr-1"></i> Across All Batches
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <!-- Attendance Rate -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Virtual Attendance Rate</div>
                    <div class="text-2xl font-bold text-slate-900">98.5%</div>
                    <div class="text-[11px] text-amber-600 font-medium flex items-center">
                        <i class="fa-solid fa-chart-line mr-1"></i> High engagement
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
        </div>

        <!-- DataTable Container -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Table Header Controls -->
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <h2 class="text-sm font-bold text-slate-900">Live Classroom Schedule</h2>
                    <span class="bg-slate-200 text-slate-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ count($sessions) }}</span>
                </div>
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
                    <input type="text" id="liveClassSearch" onkeyup="searchLiveTable()" placeholder="Search title, teacher, class..." class="w-full bg-white border border-slate-300 rounded-lg pl-8 pr-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Table Responsive -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700" id="liveSessionsTable">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">#</th>
                            <th class="py-3 px-4">Lecture / Topic Title</th>
                            <th class="py-3 px-4">Platform</th>
                            <th class="py-3 px-4">Class &amp; Subject</th>
                            <th class="py-3 px-4">Host / Teacher</th>
                            <th class="py-3 px-4">Time Slot &amp; Duration</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sessions as $idx => $s)
                            <tr class="hover:bg-slate-50/80 transition live-session-row" data-platform="{{ $s->platform }}" data-status="{{ $s->status }}">
                                <td class="py-3 px-4 text-center font-mono text-slate-400 text-xs">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900 text-xs session-title">{{ $s->title }}</div>
                                    <div class="text-[11px] text-slate-500 flex items-center space-x-1 mt-0.5">
                                        <span class="font-mono bg-slate-100 px-1 rounded text-slate-600">ID: {{ $s->meeting_id ?? 'MC-84920' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @if(strtolower($s->platform) == 'zoom')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fa-solid fa-video mr-1 text-blue-600"></i> Zoom
                                        </span>
                                    @elseif(str_contains(strtolower($s->platform), 'meet') || str_contains(strtolower($s->platform), 'google'))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-brands fa-google mr-1 text-emerald-600"></i> Google Meet
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="fa-solid fa-server mr-1 text-purple-600"></i> {{ $s->platform }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-800">{{ $s->class_name }}</div>
                                    <div class="text-[11px] text-slate-500 flex items-center space-x-1">
                                        <i class="fa-solid fa-book-bookmark text-slate-400"></i>
                                        <span>{{ $s->subject_name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($s->host_name ?? 'T', 0, 1)) }}
                                        </div>
                                        <div class="font-medium text-slate-800">{{ $s->host_name }}</div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($s->start_time)->format('d M, h:i A') }}</div>
                                    <div class="text-[11px] text-slate-500">to {{ \Carbon\Carbon::parse($s->end_time)->format('h:i A') }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if(strtolower($s->status) === 'live')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5 animate-ping"></span> Live Now
                                        </span>
                                    @elseif(strtolower($s->status) === 'scheduled')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-regular fa-clock mr-1 text-emerald-600"></i> Scheduled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600">
                                            {{ $s->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <a href="{{ $s->join_url ?? '#' }}" target="_blank" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-3 py-1.5 rounded-lg shadow-sm transition">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                            <span>Join Now</span>
                                        </a>
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $s->join_url ?? '#' }}'); alert('Meeting link copied!')" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition" title="Copy Meeting URL">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto">
                                            <i class="fa-solid fa-video-slash"></i>
                                        </div>
                                        <div class="font-bold text-slate-700 text-sm">No Live Classes Scheduled</div>
                                        <p class="text-xs text-slate-500">Schedule your first virtual session for any class &amp; section using the button above.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                <div>Showing <span class="font-semibold text-slate-800">{{ count($sessions) }}</span> live class session(s)</div>
                <div class="flex items-center space-x-2 text-[11px]">
                    <span class="inline-flex items-center"><span class="w-2 h-2 rounded-full bg-blue-500 mr-1"></span> Zoom</span>
                    <span class="inline-flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-1"></span> Google Meet</span>
                    <span class="inline-flex items-center"><span class="w-2 h-2 rounded-full bg-purple-500 mr-1"></span> Jitsi / BBB</span>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- ========================================================================= -->
<!-- 🔥 TAILWIND PURE MODAL: SCHEDULE LIVE CLASS                               -->
<!-- ========================================================================= -->
<div id="scheduleClassModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-video"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Schedule Virtual Live Class</h3>
            </div>
            <button type="button" onclick="closeModal('scheduleClassModal')" class="text-slate-400 hover:text-slate-600 text-lg p-1 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Form Body -->
        <form action="{{ route('modules.live-classes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1">Lecture / Topic Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Thermodynamics Chapter Revision & Numericals" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Video Platform <span class="text-red-500">*</span></label>
                    <select name="platform" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="Zoom">Zoom Meetings</option>
                        <option value="Google Meet">Google Meet</option>
                        <option value="Jitsi Meet">Jitsi Meet</option>
                        <option value="BigBlueButton">BigBlueButton (BBB)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Target Class <span class="text-red-500">*</span></label>
                    <select name="class_name" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($classes as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Subject</label>
                    <select name="subject_name" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="General Studies">General Studies</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Host / Teacher Name</label>
                    <input type="text" name="host_name" value="Prof. Rajesh Sharma" placeholder="Teacher Name" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Start Date &amp; Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_time" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">End Time</label>
                    <input type="datetime-local" name="end_time" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1">Meeting Join URL / Link</label>
                <input type="url" name="join_url" placeholder="https://zoom.us/j/84920491823" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-[10px] text-slate-500 mt-1">Leave empty to auto-generate meeting link based on the selected platform.</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('scheduleClassModal')" class="bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-lg transition cursor-pointer">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Schedule Session</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Live Search Filter for Live Classes Table
function searchLiveTable() {
    const q = (document.getElementById('liveClassSearch').value || '').toLowerCase();
    const rows = document.querySelectorAll('.live-session-row');
    rows.forEach(r => {
        const text = r.textContent.toLowerCase();
        r.style.display = text.includes(q) ? '' : 'none';
    });
}

// Platform Category Filter
function filterPlatform(platform) {
    document.querySelectorAll('.platform-filter-btn').forEach(btn => {
        btn.classList.remove('bg-slate-200', 'font-bold');
    });
    event.target.classList.add('bg-slate-200', 'font-bold');

    const rows = document.querySelectorAll('.live-session-row');
    rows.forEach(r => {
        if (platform === 'all') {
            r.style.display = '';
        } else if (platform === 'Scheduled') {
            const status = r.getAttribute('data-status') || '';
            r.style.display = status.toLowerCase() === 'scheduled' ? '' : 'none';
        } else {
            const p = r.getAttribute('data-platform') || '';
            r.style.display = p.toLowerCase().includes(platform.toLowerCase()) ? '' : 'none';
        }
    });
}
</script>
@endsection
