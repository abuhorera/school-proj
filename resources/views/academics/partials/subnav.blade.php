<!-- Academics Module Top Sub-Navigation (Matching Demo MultiSchool ERP acad-tabs) -->
<div class="bg-white border-b border-slate-200 -mt-6 -mx-6 px-6 pt-4 mb-6 sticky top-0 z-10 shadow-xs">
    <div class="flex items-center justify-between pb-3">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-base">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Academic Management</h2>
                <p class="text-xs text-slate-500">NEP 2020 Aligned • Classes, Sections, Subjects, Periods & Timetable Grid</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if(isset($currentSession))
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                    Current Session: {{ $currentSession->name }}
                </span>
            @endif
            <a href="{{ route('academics.timetable') }}" class="px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition flex items-center space-x-1.5">
                <i class="fa-solid fa-calendar-days text-indigo-600"></i>
                <span class="hidden sm:inline">Timetable</span>
            </a>
            <a href="{{ route('academics.promote-students') }}" class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-angles-up"></i>
                <span>Promote Batch</span>
            </a>
        </div>
    </div>

    <!-- Scrollable Tab Bar -->
    <div class="flex items-center space-x-1 overflow-x-auto custom-scroll -mb-px text-xs font-medium border-t border-slate-100 pt-1">
        <a href="{{ route('academics.dashboard') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ (request()->routeIs('academics.dashboard') || request()->routeIs('academics.index')) ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('academics.sessions') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.sessions*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Sessions</span>
        </a>
        <a href="{{ route('academics.classes') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.classes*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-chalkboard"></i>
            <span>Classes</span>
        </a>
        <a href="{{ route('academics.sections') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.sections*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-users-rectangle"></i>
            <span>Sections</span>
        </a>
        <a href="{{ route('academics.subjects') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.subjects*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-book-bookmark"></i>
            <span>Subjects</span>
        </a>
        <a href="{{ route('academics.assign-subjects') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.assign-subjects*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-link"></i>
            <span>Assign Subjects</span>
            <span class="bg-indigo-100 text-indigo-700 text-[9px] px-1.5 py-0.2 rounded-full font-bold ml-1">NEW</span>
        </a>
        <a href="{{ route('academics.assign-electives') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.assign-electives*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-list-check"></i>
            <span>Assign Electives</span>
        </a>
        <a href="{{ route('academics.assign-class-teacher') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.assign-class-teacher*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Assign Teacher</span>
        </a>
        <a href="{{ route('academics.time-slots') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ (request()->routeIs('academics.time-slots*') || request()->routeIs('academics.period-sets*')) ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-regular fa-clock"></i>
            <span>Manage Periods</span>
        </a>
        <a href="{{ route('academics.timetable') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.timetable*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-calendar-week"></i>
            <span>Class Timetable</span>
        </a>
        <a href="{{ route('academics.promote-students') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('academics.promote*') ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-user-graduate"></i>
            <span>Promote Students</span>
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-r-lg flex items-center justify-between text-xs text-emerald-800 shadow-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-3.5 rounded-r-lg flex items-center justify-between text-xs text-rose-800 shadow-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
@endif

