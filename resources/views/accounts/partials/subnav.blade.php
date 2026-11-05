<div class="bg-white border-b border-slate-200 px-6 py-2.5 mb-6 -mx-6 -mt-6 sticky top-0 z-10 flex flex-wrap items-center justify-between gap-3 shadow-xs">
    <div class="flex items-center space-x-1 overflow-x-auto custom-scroll text-xs">
        <a href="{{ route('accounts.dashboard') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.dashboard') || request()->routeIs('management.accounts') ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-gauge-high text-xs text-amber-400"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('accounts.incomes.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.incomes.*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-arrow-down-left text-xs text-emerald-400"></i>
            <span>Income</span>
        </a>
        <a href="{{ route('accounts.expenses.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.expenses.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-arrow-up-right text-xs text-rose-400"></i>
            <span>Expense</span>
        </a>
        <a href="{{ route('accounts.day-book') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.day-book') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-book-journal-whills text-xs text-indigo-400"></i>
            <span>Day Book</span>
        </a>
        <a href="{{ route('accounts.chart-of-accounts') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.chart-of-accounts') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-sitemap text-xs text-blue-400"></i>
            <span>Chart of Accounts</span>
        </a>
        <a href="{{ route('accounts.income-heads.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.income-heads.*') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-tags text-xs text-emerald-400"></i>
            <span>Income Heads</span>
        </a>
        <a href="{{ route('accounts.expense-heads.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.expense-heads.*') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-tag text-xs text-rose-400"></i>
            <span>Expense Heads</span>
        </a>
        <a href="{{ route('accounts.bank-accounts.index') }}" class="px-3 py-1.5 rounded-lg font-semibold transition flex items-center space-x-1.5 {{ request()->routeIs('accounts.bank-accounts.*') ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <i class="fa-solid fa-building-columns text-xs text-purple-400"></i>
            <span>Bank Accounts</span>
        </a>
    </div>

    <!-- Quick Voucher Entry Trigger -->
    <div class="flex items-center space-x-2">
        <a href="{{ route('accounts.incomes.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1.5 rounded-lg font-bold text-xs flex items-center space-x-1 shadow-xs transition">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>New Income</span>
        </a>
        <a href="{{ route('accounts.expenses.create') }}" class="bg-rose-600 hover:bg-rose-700 text-white px-2.5 py-1.5 rounded-lg font-bold text-xs flex items-center space-x-1 shadow-xs transition">
            <i class="fa-solid fa-minus text-[10px]"></i>
            <span>New Expense</span>
        </a>
    </div>
</div>

