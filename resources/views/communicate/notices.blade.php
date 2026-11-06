@extends('layouts.app')

@section('title', 'Notice Board')

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
                <span class="text-blue-600 font-semibold">Notice Board</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Notice Board</h1>
                    <p class="text-xs text-slate-500">Announcements published to parents, staff, and classes across the campus.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('school.notices', ['session' => 'all']) }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-layer-group text-slate-500"></i>
                <span>View all sessions</span>
            </a>
            <a href="{{ route('school.notices.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Notice</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('school.notices') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Search Keyword</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notices by title or content..." class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Priority</label>
                <select name="priority" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    <option value="all">All Priorities</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent Alert</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('school.notices') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Notice List Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">Title</th>
                        <th class="py-3 px-4">Sent To</th>
                        <th class="py-3 px-4">Published On</th>
                        <th class="py-3 px-4 text-center">Social / App</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($notices as $index => $notice)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 text-center text-slate-500 font-mono">
                            {{ $notices->firstItem() + $index }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-sm hover:text-blue-600 cursor-pointer" onclick="viewNoticeModal({{ json_encode($notice) }})">
                                {{ $notice->title }}
                            </div>
                            <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                {{ Str::limit(strip_tags($notice->description), 85) }}
                            </div>
                            <div class="flex items-center space-x-2 mt-1">
                                @if($notice->priority === 'urgent')
                                    <span class="bg-rose-100 text-rose-700 font-bold px-1.5 py-0.2 rounded text-[10px]"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Urgent</span>
                                @elseif($notice->priority === 'high')
                                    <span class="bg-amber-100 text-amber-700 font-bold px-1.5 py-0.2 rounded text-[10px]"><i class="fa-solid fa-bell mr-1"></i>High</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 font-medium px-1.5 py-0.2 rounded text-[10px]">Normal</span>
                                @endif

                                @if($notice->is_popup)
                                    <span class="bg-indigo-100 text-indigo-700 font-bold px-1.5 py-0.2 rounded text-[10px]"><i class="fa-solid fa-mobile-screen mr-1"></i>App Popup Alert</span>
                                @endif

                                @if($notice->attachment)
                                    <a href="{{ asset($notice->attachment) }}" target="_blank" class="text-blue-600 hover:underline text-[10px] flex items-center space-x-1">
                                        <i class="fa-solid fa-paperclip"></i>
                                        <span>Attachment</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $rType = strtolower($notice->recipient_type ?? 'all');
                            @endphp
                            @if($rType === 'all' || $rType === 'entire school')
                                <span class="bg-blue-50 text-blue-700 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-globe mr-1.5 text-blue-500"></i> All (Entire School)
                                </span>
                            @elseif(str_contains($rType, 'staff'))
                                <span class="bg-purple-50 text-purple-700 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-chalkboard-user mr-1.5 text-purple-500"></i> All Staff
                                </span>
                            @elseif(str_contains($rType, 'parent'))
                                <span class="bg-emerald-50 text-emerald-700 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-users mr-1.5 text-emerald-500"></i> All Parents
                                </span>
                            @else
                                <span class="bg-amber-50 text-amber-700 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-graduation-cap mr-1.5 text-amber-500"></i> Specific Class
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-600">
                            {{ $notice->published_date ? date('d M, Y', strtotime($notice->published_date)) : date('d M, Y', strtotime($notice->created_at)) }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center text-emerald-600 font-semibold text-[11px] bg-emerald-50 px-2 py-0.5 rounded-full">
                                <i class="fa-solid fa-circle-check mr-1 text-[10px]"></i> Published
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex items-center space-x-1">
                                <button type="button" onclick="viewNoticeModal({{ json_encode($notice) }})" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View Full Notice">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                <a href="{{ route('school.notices.edit', $notice->id) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Notice">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('school.notices.destroy', $notice->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Notice">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clipboard-question text-4xl mb-3 block text-slate-300"></i>
                            <p class="font-semibold text-slate-600 text-sm">No notices published yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Click "Add New Notice" above to publish announcements to parents and staff.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($notices->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $notices->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: View Notice Details -->
<div id="viewNoticeModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-bullhorn text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900" id="vnTitle">Notice Details</h3>
                    <span class="text-[10px] text-slate-500" id="vnDate"></span>
                </div>
            </div>
            <button onclick="closeModal('viewNoticeModal')" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div class="flex items-center space-x-2">
                <span class="text-slate-500 font-medium">Audience:</span>
                <span class="font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded" id="vnAudience"></span>
                <span class="font-bold px-2 py-0.5 rounded" id="vnPriority"></span>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-slate-700 whitespace-pre-wrap leading-relaxed max-h-60 overflow-y-auto" id="vnContent">
            </div>

            <div id="vnAttachmentWrap" class="hidden">
                <a href="#" id="vnAttachmentLink" target="_blank" class="inline-flex items-center space-x-1.5 text-blue-600 hover:underline font-semibold">
                    <i class="fa-solid fa-paperclip"></i>
                    <span>Download Attached Document</span>
                </a>
            </div>
        </div>

        <div class="flex justify-end pt-2 border-t border-slate-100">
            <button onclick="closeModal('viewNoticeModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function viewNoticeModal(notice) {
    document.getElementById('vnTitle').textContent = notice.title;
    document.getElementById('vnDate').textContent = 'Published On: ' + (notice.published_date || notice.created_at || '');
    document.getElementById('vnAudience').textContent = (notice.recipient_type || 'Entire School').toUpperCase();
    
    const prio = document.getElementById('vnPriority');
    prio.textContent = (notice.priority || 'Normal').toUpperCase();
    if ((notice.priority || '').toLowerCase() === 'urgent') {
        prio.className = 'font-bold px-2 py-0.5 rounded bg-rose-100 text-rose-700';
    } else if ((notice.priority || '').toLowerCase() === 'high') {
        prio.className = 'font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700';
    } else {
        prio.className = 'font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600';
    }

    document.getElementById('vnContent').textContent = notice.description;

    const attWrap = document.getElementById('vnAttachmentWrap');
    const attLink = document.getElementById('vnAttachmentLink');
    if (notice.attachment) {
        attLink.href = '/' + notice.attachment;
        attWrap.classList.remove('hidden');
    } else {
        attWrap.classList.add('hidden');
    }

    openModal('viewNoticeModal');
}
</script>
@endsection

