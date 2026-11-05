<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-700">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('classrooms.rooms') }}" class="hover:text-primary-600">Classrooms</a>
                @if(isset($pageTitle))
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $pageTitle }}</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                <i class="fa-solid fa-door-open text-orange-500"></i>
                <span>{{ $pageTitle ?? 'Classroom Management' }}</span>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-950/50 dark:text-orange-300">Campus Spaces</span>
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                {{ $pageSubtitle ?? 'Every room on campus — who is in it, right now.' }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" onclick="document.getElementById('quickSetupModal') ? document.getElementById('quickSetupModal').classList.remove('hidden') : window.location.href='{{ route('classrooms.rooms') }}#quick-setup'" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Quick Setup</span>
            </button>
            <a href="{{ route('classrooms.free-rooms') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-lg hover:bg-emerald-100 transition shadow-sm">
                <i class="fa-solid fa-bolt"></i>
                <span>Find Free Room</span>
            </a>
            <a href="{{ route('classrooms.guide') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-circle-question"></i>
                <span>Guide</span>
            </a>
        </div>
    </div>

    <!-- Scrollable Tabbed Sub-navigation -->
    <div class="flex items-center gap-1 mt-4 overflow-x-auto border-b border-slate-200 dark:border-slate-700 pb-px text-xs font-medium">
        <a href="{{ route('classrooms.rooms') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.rooms') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-list-check"></i>
            <span>Classroom List</span>
        </a>
        <a href="{{ route('classrooms.buildings') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.buildings') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-building"></i>
            <span>Buildings & Blocks</span>
        </a>
        <a href="{{ route('classrooms.plan') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.plan') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-map-location-dot"></i>
            <span>Room Plan</span>
        </a>
        <a href="{{ route('classrooms.plan.capacity') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.plan.capacity') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Capacity Report</span>
        </a>
        <a href="{{ route('classrooms.timetable') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.timetable') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Room Timetable</span>
        </a>
        <a href="{{ route('classrooms.free-rooms') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.free-rooms') ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-door-closed text-emerald-500"></i>
            <span>Free Rooms</span>
        </a>
        <a href="{{ route('classrooms.usage') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.usage') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-chart-column"></i>
            <span>Room Usage</span>
        </a>
        <a href="{{ route('classrooms.clashes') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.clashes') ? 'border-red-500 text-red-600 dark:text-red-400 bg-red-50/50 dark:bg-red-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
            <span>Room Clashes</span>
        </a>
        <a href="{{ route('classrooms.bookings') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.bookings*') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Bookings</span>
        </a>
        <a href="{{ route('classrooms.maintenance') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.maintenance*') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-wrench"></i>
            <span>Maintenance</span>
        </a>
        <a href="{{ route('classrooms.labels') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-t-lg transition border-b-2 whitespace-nowrap {{ request()->routeIs('classrooms.labels') ? 'border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 font-bold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-tags"></i>
            <span>Labels</span>
        </a>
    </div>
</div>

