<div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-2 mb-6">
    <div class="flex items-center space-x-1 overflow-x-auto scrollbar-none text-xs font-bold">
        <a href="{{ route('ptm.dashboard') }}" 
           class="px-3.5 py-2 rounded-xl transition flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('ptm.dashboard') ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>PTM Dashboard</span>
        </a>

        <a href="{{ route('ptm.meetings') }}" 
           class="px-3.5 py-2 rounded-xl transition flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('ptm.meetings*') ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Schedule Meetings</span>
        </a>

        <a href="{{ route('ptm.record') }}" 
           class="px-3.5 py-2 rounded-xl transition flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('ptm.record*') ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-clipboard-user"></i>
            <span>Attendance & Remarks</span>
        </a>

        <a href="{{ route('ptm.followups') }}" 
           class="px-3.5 py-2 rounded-xl transition flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('ptm.followups*') ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-list-check"></i>
            <span>PTM Follow-ups</span>
        </a>

        <a href="{{ route('ptm.reports') }}" 
           class="px-3.5 py-2 rounded-xl transition flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('ptm.reports*') ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-file-lines"></i>
            <span>PTM Reports</span>
        </a>

        <a href="{{ route('ptm.guide') }}" 
           class="px-3.5 py-2 rounded-xl transition flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('ptm.guide') ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-book-open-reader"></i>
            <span>PTM Guide</span>
        </a>
    </div>
</div>
