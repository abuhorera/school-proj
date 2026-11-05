<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-700">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-600">Home</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('hpc.dashboard') }}" class="hover:text-primary-600">Holistic Progress Card</a>
                @if(isset($pageTitle))
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $pageTitle }}</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                <i class="fa-solid fa-child-reaching text-violet-600"></i>
                <span>{{ $pageTitle ?? 'Holistic Progress Card' }}</span>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">NEP 2020 / PARAKH</span>
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                {{ $pageSubtitle ?? 'A whole-child report — the three abilities, multi-dimensional assessment without numerical marks or ranks.' }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('hpc.cards.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-violet-600 rounded-lg hover:bg-violet-700 shadow-sm transition">
                <i class="fa-solid fa-plus"></i>
                <span>Create Cards</span>
            </a>
            <a href="{{ route('hpc.activities.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/60 shadow-sm transition">
                <i class="fa-solid fa-calendar-plus text-violet-600"></i>
                <span>New Activity</span>
            </a>
        </div>
    </div>

    <!-- Tab navigation -->
    <div class="flex items-center gap-1 mt-4 overflow-x-auto border-b border-slate-200 dark:border-slate-700 pb-px text-sm">
        <a href="{{ route('hpc.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-t-lg transition border-b-2 {{ request()->routeIs('hpc.dashboard') ? 'border-violet-600 text-violet-600 dark:text-violet-400 bg-violet-50/50 dark:bg-violet-950/20 font-semibold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-gauge"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('hpc.activities') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-t-lg transition border-b-2 {{ request()->routeIs('hpc.activities*') ? 'border-violet-600 text-violet-600 dark:text-violet-400 bg-violet-50/50 dark:bg-violet-950/20 font-semibold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-shapes"></i>
            <span>Activities</span>
        </a>
        <a href="{{ route('hpc.cards') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-t-lg transition border-b-2 {{ request()->routeIs('hpc.cards*') ? 'border-violet-600 text-violet-600 dark:text-violet-400 bg-violet-50/50 dark:bg-violet-950/20 font-semibold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-id-card"></i>
            <span>Progress Cards</span>
        </a>
        <a href="{{ route('hpc.frameworks') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-t-lg transition border-b-2 {{ request()->routeIs('hpc.frameworks*') ? 'border-violet-600 text-violet-600 dark:text-violet-400 bg-violet-50/50 dark:bg-violet-950/20 font-semibold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Card Editions</span>
        </a>
        <a href="{{ route('hpc.card-appearance') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-t-lg transition border-b-2 {{ request()->routeIs('hpc.card-appearance') ? 'border-violet-600 text-violet-600 dark:text-violet-400 bg-violet-50/50 dark:bg-violet-950/20 font-semibold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <i class="fa-solid fa-palette"></i>
            <span>Card Appearance</span>
        </a>
    </div>
</div>
