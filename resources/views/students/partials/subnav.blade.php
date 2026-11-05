<div class="bg-white border-b border-slate-200 px-6 py-2.5 mb-6 -mx-6 -mt-6 sticky top-0 z-10 flex flex-wrap items-center justify-between gap-3 shadow-xs">
    <div class="flex items-center space-x-1 overflow-x-auto custom-scroll text-xs">
        <a href="{{ route('students.dashboard') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.dashboard') ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-gauge-high text-xs text-blue-400"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('students.create') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.create') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-user-plus text-xs text-blue-400"></i>
            <span>Admit Student</span>
        </a>
        <a href="{{ route('students.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.index') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-users text-xs text-blue-400"></i>
            <span>Student List</span>
        </a>
        <a href="{{ route('students.face-search') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.face-search') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-camera text-xs text-indigo-400"></i>
            <span>Photo Search</span>
        </a>
        <a href="{{ route('students.parents') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.parents') ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-people-roof text-xs text-purple-400"></i>
            <span>Parents</span>
        </a>
        <a href="{{ route('students.attendance') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.attendance') ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-clipboard-user text-xs text-amber-400"></i>
            <span>Attendance</span>
        </a>
        <a href="{{ route('students.behavior') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.behavior') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-award text-xs text-emerald-400"></i>
            <span>Behavior</span>
        </a>
        <a href="{{ route('students.houses') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.houses') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-shield-halved text-xs text-rose-400"></i>
            <span>Houses</span>
        </a>
        <a href="{{ route('students.categories') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.categories') ? 'bg-cyan-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-layer-group text-xs text-cyan-400"></i>
            <span>Categories</span>
        </a>
        <a href="{{ route('students.exits') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.exits') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-file-invoice text-xs text-amber-400"></i>
            <span>TC & Exit</span>
        </a>
        <a href="{{ route('students.health') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.health') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-heart-pulse text-xs text-teal-400"></i>
            <span>Health</span>
        </a>
        <a href="{{ route('students.recycle-bin') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('students.recycle-bin') ? 'bg-red-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-trash-can text-xs text-red-400"></i>
            <span>Recycle Bin</span>
        </a>
    </div>

    <!-- Quick Admission Action Button -->
    <div>
        <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs flex items-center space-x-1.5 shadow-xs transition">
            <i class="fa-solid fa-user-plus text-[10px]"></i>
            <span>New Admission</span>
        </a>
    </div>
</div>

