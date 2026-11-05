@extends('layouts.app')

@section('title', 'Communicate & Broadcast Hub')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Modules</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Communicate</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Communicate &amp; Broadcast Hub</h1>
                    <p class="text-xs text-slate-500">Campus announcements, holiday calendar, multi-channel SMS/Email broadcasts, and media gallery.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('modules.communicate', ['tab' => 'broadcast_create']) }}" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Compose Broadcast</span>
            </a>
            <button type="button" onclick="openModal('newNoticeModal')" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-plus text-slate-500"></i>
                <span>Post Notice</span>
            </button>
        </div>
    </div>

    <!-- Sub Navigation Pill Tabs -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center gap-1 bg-slate-100 p-1 rounded-lg text-xs font-semibold">
            <a href="{{ route('modules.communicate', ['tab' => 'notices']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'notices' || empty($tab) ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Notice Board</span>
                <span class="ml-1 bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($notices) }}</span>
            </a>
            <a href="{{ route('modules.communicate', ['tab' => 'events']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'events' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Events &amp; Holidays</span>
                <span class="ml-1 bg-emerald-100 text-emerald-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($events) }}</span>
            </a>
            <a href="{{ route('modules.communicate', ['tab' => 'broadcast_create']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'broadcast_create' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Compose Broadcast</span>
            </a>
            <a href="{{ route('modules.communicate', ['tab' => 'broadcast_history']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'broadcast_history' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Broadcast History</span>
            </a>
            <a href="{{ route('modules.communicate', ['tab' => 'gallery']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'gallery' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-images"></i>
                <span>Media Gallery</span>
            </a>
        </div>
    </div>

    <!-- 4 High-Density KPI Metric Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Active Notices -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Active Campus Notices</div>
                <div class="text-2xl font-bold text-slate-900">{{ count($notices) }}</div>
                <div class="text-[11px] text-blue-600 font-medium flex items-center">
                    <i class="fa-solid fa-bullhorn mr-1"></i> Public Notice Board
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Events &amp; Holidays</div>
                <div class="text-2xl font-bold text-slate-900">{{ count($events) }}</div>
                <div class="text-[11px] text-emerald-600 font-medium flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span> Academic Calendar
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>

        <!-- Broadcast Dispatches -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Dispatched Broadcasts</div>
                <div class="text-2xl font-bold text-slate-900">18</div>
                <div class="text-[11px] text-amber-600 font-medium flex items-center">
                    <i class="fa-solid fa-paper-plane mr-1"></i> SMS, WhatsApp &amp; Email
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-tower-broadcast"></i>
            </div>
        </div>

        <!-- Reach / Delivery -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Avg Delivery Success</div>
                <div class="text-2xl font-bold text-slate-900">99.2%</div>
                <div class="text-[11px] text-purple-600 font-medium flex items-center">
                    <i class="fa-solid fa-circle-check mr-1"></i> DLT Verified Gateways
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-message"></i>
            </div>
        </div>
    </div>

    @if($tab === 'events')
        <!-- ================================================================= -->
        <!-- TAB: CAMPUS EVENTS & ACADEMIC HOLIDAYS                            -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Campus Events &amp; Academic Holidays</h2>
                        <p class="text-[11px] text-slate-500">Official scheduled school celebrations, parent-teacher conferences, and vacations.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Event Title &amp; Occasion</th>
                            <th class="py-3 px-4">Event Date</th>
                            <th class="py-3 px-4">Location / Venue</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $ev)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                                        <span>{{ $ev->title }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] bg-slate-100 border border-slate-200">
                                        {{ $ev->event_date }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700">
                                    <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>
                                    {{ $ev->location ?? 'Main Campus Auditorium' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $ev->description ?? 'Campus-wide academic event.' }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ ucfirst($ev->status ?? 'Upcoming') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400">No scheduled events found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($tab === 'broadcast_create')
        <!-- ================================================================= -->
        <!-- TAB: COMPOSE BROADCAST                                            -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Compose Multi-Channel Broadcast Notification</h2>
                    <p class="text-xs text-slate-500">Dispatch bulk SMS, WhatsApp messages, and official emails to students, parents, and staff.</p>
                </div>
            </div>

            <form onsubmit="event.preventDefault(); alert('Broadcast queued successfully! Messages will be delivered via DLT gateway.');" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-semibold text-slate-700 text-xs mb-1.5">Target Audience Segment <span class="text-red-500">*</span></label>
                        <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option>All Enrolled Students &amp; Guardians (248 recipients)</option>
                            <option>Only Teaching &amp; Administrative Faculty (24 recipients)</option>
                            <option>Class 10 Board Candidates &amp; Parents</option>
                            <option>Hostel Residents (Blocks A &amp; B)</option>
                            <option>Bus Transport Route Passengers</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 text-xs mb-1.5">Dispatch Channels <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap items-center gap-4 pt-1">
                            <label class="inline-flex items-center space-x-2 text-xs font-medium text-slate-700 cursor-pointer">
                                <input type="checkbox" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                                <span><i class="fa-solid fa-comment-sms text-blue-600 mr-1"></i> SMS Gateway</span>
                            </label>
                            <label class="inline-flex items-center space-x-2 text-xs font-medium text-slate-700 cursor-pointer">
                                <input type="checkbox" checked class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                                <span class="text-emerald-700 font-semibold"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i> WhatsApp API</span>
                            </label>
                            <label class="inline-flex items-center space-x-2 text-xs font-medium text-slate-700 cursor-pointer">
                                <input type="checkbox" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                                <span><i class="fa-solid fa-envelope text-indigo-600 mr-1"></i> Official Email</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1.5">Subject / Headline <span class="text-red-500">*</span></label>
                    <input type="text" required placeholder="e.g. Weather Advisory: School Closure tomorrow due to heavy rain" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1.5">Notification Message Content <span class="text-red-500">*</span></label>
                    <textarea rows="4" required placeholder="Type the broadcast message here. Dynamic tags like {student_name}, {class} will be auto-replaced." class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                    <p class="text-[11px] text-slate-500 mt-1 flex items-center justify-between">
                        <span>Characters: ~160 (1 SMS Credit per recipient)</span>
                        <span class="text-blue-600 font-medium">Template DLT ID: DLT_SCH_9842</span>
                    </p>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="reset" class="bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-lg transition">Clear Draft</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-5 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition flex items-center space-x-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Send Immediate Broadcast</span>
                    </button>
                </div>
            </form>
        </div>

    @elseif($tab === 'gallery')
        <!-- ================================================================= -->
        <!-- TAB: MEDIA & PHOTO GALLERY                                        -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Campus Photo Albums &amp; Media Gallery</h2>
                    <p class="text-xs text-slate-500">Curated photo collections from annual sports, science exhibitions, and national celebrations.</p>
                </div>
                <button type="button" onclick="alert('Upload album modal')" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                    <i class="fa-solid fa-folder-plus mr-1"></i> New Album
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Album 1 -->
                <div class="rounded-xl border border-slate-200 overflow-hidden group hover:shadow-md transition">
                    <div class="h-44 bg-gradient-to-tr from-blue-600 to-indigo-700 text-white flex items-center justify-center text-4xl">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div class="p-4 space-y-1">
                        <div class="font-bold text-slate-900 text-sm">Annual Sports Meet 2026</div>
                        <p class="text-xs text-slate-500">42 Photographs &middot; Track, Field &amp; Relays</p>
                        <span class="inline-flex items-center text-[11px] text-blue-600 font-semibold pt-1">View Album &rarr;</span>
                    </div>
                </div>

                <!-- Album 2 -->
                <div class="rounded-xl border border-slate-200 overflow-hidden group hover:shadow-md transition">
                    <div class="h-44 bg-gradient-to-tr from-emerald-600 to-teal-700 text-white flex items-center justify-center text-4xl">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div class="p-4 space-y-1">
                        <div class="font-bold text-slate-900 text-sm">Science &amp; Robotics Exhibition</div>
                        <p class="text-xs text-slate-500">28 Photographs &middot; Senior Lab Projects</p>
                        <span class="inline-flex items-center text-[11px] text-emerald-600 font-semibold pt-1">View Album &rarr;</span>
                    </div>
                </div>

                <!-- Album 3 -->
                <div class="rounded-xl border border-slate-200 overflow-hidden group hover:shadow-md transition">
                    <div class="h-44 bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center text-4xl">
                        <i class="fa-solid fa-music"></i>
                    </div>
                    <div class="p-4 space-y-1">
                        <div class="font-bold text-slate-900 text-sm">Independence Day Cultural Fest</div>
                        <p class="text-xs text-slate-500">35 Photographs &middot; Choir &amp; Drama Club</p>
                        <span class="inline-flex items-center text-[11px] text-amber-600 font-semibold pt-1">View Album &rarr;</span>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- ================================================================= -->
        <!-- TAB: NOTICE BOARD (DEFAULT)                                       -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Official Digital Notice Board</h2>
                        <p class="text-[11px] text-slate-500">Institutional circulars, administrative alerts, and student guidelines.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-bold border border-blue-200">
                        {{ count($notices) }} Active Notices
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">#</th>
                            <th class="py-3 px-4">Notice Headline &amp; Details</th>
                            <th class="py-3 px-4">Target Group</th>
                            <th class="py-3 px-4">Publish Date</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($notices as $idx => $n)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 text-center font-mono text-slate-400 text-xs">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-xs">{{ $n->title }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ Str::limit($n->description, 85) }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                                        {{ $n->target_role ?? 'ALL' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-700">{{ $n->published_date }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check mr-1 text-emerald-600"></i> Published
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="alert('Notice: {{ addslashes($n->title) }}\n\n{{ addslashes($n->description) }}')" class="inline-flex items-center space-x-1 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-2.5 py-1.5 rounded-lg shadow-sm transition">
                                        <i class="fa-regular fa-eye text-[11px]"></i>
                                        <span>View</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400">No notices currently posted.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

<!-- ========================================================================= -->
<!-- 🔥 TAILWIND PURE MODAL: NEW NOTICE POST                                   -->
<!-- ========================================================================= -->
<div id="newNoticeModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Post Official Notice</h3>
            </div>
            <button type="button" onclick="closeModal('newNoticeModal')" class="text-slate-400 hover:text-slate-600 text-lg p-1 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form onsubmit="event.preventDefault(); alert('Notice published to Notice Board!'); closeModal('newNoticeModal');" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1">Notice Title / Headline <span class="text-red-500">*</span></label>
                <input type="text" required placeholder="e.g. Schedule for Quarterly Formative Assessments 2026" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Target Group</label>
                    <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="ALL">All Campus (Students, Parents, Staff)</option>
                        <option value="STUDENTS">Students Only</option>
                        <option value="PARENTS">Guardians / Parents Only</option>
                        <option value="STAFF">Faculty &amp; Staff Only</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Publish Date</label>
                    <input type="date" value="{{ date('Y-m-d') }}" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1">Notice Description / Circular Body <span class="text-red-500">*</span></label>
                <textarea rows="4" required placeholder="Type full notice circular text..." class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('newNoticeModal')" class="bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-lg transition cursor-pointer">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Publish Notice</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
