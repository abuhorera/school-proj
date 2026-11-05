<div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-2 mb-6">
    <div class="flex items-center space-x-1 overflow-x-auto scrollbar-none text-xs font-bold">
        <a href="{{ route('lesson-plans.dashboard') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.dashboard') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('lesson-plans.plans') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.plans*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-book-bookmark"></i>
            <span>Lesson Plans</span>
        </a>

        <a href="{{ route('lesson-plans.guide') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.guide') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-book-open-reader"></i>
            <span>Guide</span>
        </a>

        <a href="{{ route('lesson-plans.review') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.review*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-user-check"></i>
            <span>Review (HOD)</span>
        </a>

        <a href="{{ route('lesson-plans.approvals') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.approvals*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-stamp"></i>
            <span>Approvals</span>
        </a>

        <a href="{{ route('lesson-plans.coverage') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.coverage*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-bars-progress"></i>
            <span>Coverage</span>
        </a>

        <a href="{{ route('lesson-plans.reports') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.reports*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-column"></i>
            <span>Reports</span>
        </a>

        <a href="{{ route('lesson-plans.settings') }}" 
           class="px-3 py-2 rounded-xl transition flex items-center space-x-1.5 whitespace-nowrap {{ request()->routeIs('lesson-plans.settings*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>
    </div>
</div>

