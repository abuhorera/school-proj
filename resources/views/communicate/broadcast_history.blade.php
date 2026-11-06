@extends('layouts.app')

@section('title', 'Broadcast History')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Communicate</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Broadcast History</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Broadcast History</h1>
                    <p class="text-xs text-slate-500">Log of mass messages dispatched via SMS, WhatsApp, Email, and Push notifications.</p>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('school.communicate.broadcast', ['session' => 'all']) }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-layer-group text-slate-500"></i>
                <span>View all sessions</span>
            </a>
            <a href="{{ route('school.communicate.templates') }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-file-code text-slate-500"></i>
                <span>SMS &amp; WhatsApp templates</span>
            </a>
            <a href="{{ route('school.communicate.broadcast.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Compose New Broadcast</span>
            </a>
        </div>
    </div>

    <!-- Search & Channel Filter -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('school.communicate.broadcast') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Search Subject or Message</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search broadcasts..." class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Filter Channel</label>
                <select name="channel" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    <option value="all">All Channels</option>
                    <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS Only</option>
                    <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp Only</option>
                    <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email Only</option>
                    <option value="push" {{ request('channel') == 'push' ? 'selected' : '' }}>Push Alerts</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('school.communicate.broadcast') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Broadcast History Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">Channels</th>
                        <th class="py-3 px-4">Target</th>
                        <th class="py-3 px-4">Analytics (S/D/O)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($broadcasts as $index => $b)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 text-center text-slate-500 font-mono">
                            {{ $broadcasts->firstItem() + $index }}
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-600 whitespace-nowrap">
                            {{ date('d M Y, h:i A', strtotime($b->created_at)) }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-sm hover:text-blue-600 cursor-pointer" onclick="viewBroadcastDetails({{ json_encode($b) }})">
                                {{ $b->subject }}
                            </div>
                            <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5 font-mono">
                                {{ Str::limit($b->body, 70) }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap items-center gap-1">
                                @php $channels = (array) $b->channels; @endphp
                                @if(in_array('sms', $channels))
                                    <span class="bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded text-[10px] font-bold"><i class="fa-solid fa-comment-sms mr-1"></i>SMS</span>
                                @endif
                                @if(in_array('whatsapp', $channels))
                                    <span class="bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded text-[10px] font-bold"><i class="fa-brands fa-whatsapp mr-1"></i>WA</span>
                                @endif
                                @if(in_array('email', $channels))
                                    <span class="bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded text-[10px] font-bold"><i class="fa-solid fa-envelope mr-1"></i>Mail</span>
                                @endif
                                @if(in_array('push', $channels))
                                    <span class="bg-purple-50 text-purple-700 px-1.5 py-0.5 rounded text-[10px] font-bold"><i class="fa-solid fa-bell mr-1"></i>Push</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-semibold text-[11px]">
                                {{ $b->recipient_group }} ({{ $b->recipients_count }})
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-700 whitespace-nowrap">
                            <span class="text-blue-600 font-bold" title="Sent">{{ $b->sent_count }}</span> /
                            <span class="text-emerald-600 font-bold" title="Delivered">{{ $b->delivered_count }}</span> /
                            <span class="text-purple-600 font-bold" title="Opened">{{ $b->opened_count }}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center text-emerald-700 font-bold text-[11px] bg-emerald-50 px-2 py-0.5 rounded-full">
                                <i class="fa-solid fa-check-double mr-1 text-[10px]"></i> Completed
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex items-center space-x-1">
                                <button type="button" onclick="viewBroadcastDetails({{ json_encode($b) }})" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View Full Report">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                <form action="{{ route('school.communicate.broadcast.resend', $b->id) }}" method="POST" class="inline" onsubmit="return confirm('Resend this broadcast to all recipients?');">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Resend Broadcast">
                                        <i class="fa-solid fa-rotate-right text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-regular fa-paper-plane text-4xl mb-3 block text-slate-300"></i>
                            <p class="font-semibold text-slate-600 text-sm">No broadcasts yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Messages you send will appear here with delivery analytics and status reports.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($broadcasts->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $broadcasts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: View Broadcast Report -->
<div id="viewBroadcastModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-chart-pie text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900" id="vbSubject">Broadcast Details</h3>
                    <span class="text-[10px] text-slate-500" id="vbDate"></span>
                </div>
            </div>
            <button onclick="closeModal('viewBroadcastModal')" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <!-- 3 Stat Blocks -->
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="p-2.5 bg-blue-50 border border-blue-100 rounded-xl">
                    <div class="text-[10px] text-blue-600 font-bold uppercase">Sent</div>
                    <div class="text-base font-bold text-blue-900" id="vbSent">0</div>
                </div>
                <div class="p-2.5 bg-emerald-50 border border-emerald-100 rounded-xl">
                    <div class="text-[10px] text-emerald-600 font-bold uppercase">Delivered</div>
                    <div class="text-base font-bold text-emerald-900" id="vbDelivered">0</div>
                </div>
                <div class="p-2.5 bg-purple-50 border border-purple-100 rounded-xl">
                    <div class="text-[10px] text-purple-600 font-bold uppercase">Opened</div>
                    <div class="text-base font-bold text-purple-900" id="vbOpened">0</div>
                </div>
            </div>

            <div>
                <span class="text-slate-500 font-medium">Audience:</span>
                <span class="font-bold text-slate-800 ml-1" id="vbTarget"></span>
            </div>

            <div>
                <label class="block text-slate-500 font-medium mb-1">Message Body Dispatched:</label>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-slate-700 whitespace-pre-wrap leading-relaxed font-mono" id="vbBody">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2 border-t border-slate-100">
            <button onclick="closeModal('viewBroadcastModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function viewBroadcastDetails(b) {
    document.getElementById('vbSubject').textContent = b.subject;
    document.getElementById('vbDate').textContent = 'Dispatched on: ' + b.created_at;
    document.getElementById('vbSent').textContent = b.sent_count;
    document.getElementById('vbDelivered').textContent = b.delivered_count;
    document.getElementById('vbOpened').textContent = b.opened_count;
    document.getElementById('vbTarget').textContent = b.recipient_group + ' (' + b.recipients_count + ' recipients)';
    document.getElementById('vbBody').textContent = b.body;

    openModal('viewBroadcastModal');
}
</script>
@endsection

