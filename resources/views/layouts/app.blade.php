<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ $school->name ?? 'Multi School ERP' }}</title>
    <!-- 1. Primary Local Compiled CSS (Instant 0ms load, offline support) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- 2. FontAwesome 6 Icons (Local primary with CDN fallback) -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">

    <!-- 3. Tailwind JIT Engine (Local primary with safe fallback & config) -->
    <script src="{{ asset('js/tailwind.cdn.js') }}"></script>
    <script>
        if (typeof tailwind === 'undefined') {
            document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        }
    </script>
    <script>
        if (typeof tailwind !== 'undefined') {
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                50: '#f0f5ff',
                                100: '#e0ecff',
                                500: '#1d4ed8',
                                600: '#1e40af',
                                700: '#1d3557',
                                800: '#0f172a',
                                900: '#090d16',
                            }
                        }
                    }
                }
            };
        }
    </script>

    <!-- 4. Chart.js (Local primary with CDN fallback) -->
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script>
        if (typeof Chart === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js"><\/script>');
        }
    </script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #090d16; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        .treeview-sublink { transition: all 0.15s ease-in-out; }
        .treeview-sublink:hover { padding-left: 1.15rem; }
        .active-sublink { background-color: rgba(30, 41, 59, 0.9) !important; color: #38bdf8 !important; font-weight: 600 !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Navigation Bar -->
    <header class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-50">
        <div class="px-4 py-2.5 flex items-center justify-between">
            <!-- Left Branding & Toggle -->
            <div class="flex items-center space-x-3">
                <button type="button" onclick="toggleSidebarCollapse()" class="text-slate-400 hover:text-white p-1 rounded-lg focus:outline-none">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 text-white hover:text-blue-400 transition">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center font-bold text-lg shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm tracking-wide leading-tight">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }}</div>
                        <div class="text-[10px] text-slate-400 flex items-center space-x-1.5">
                            <span class="bg-blue-900/60 text-blue-300 px-1.5 py-0.2 rounded font-semibold text-[9px]">v3.8.0</span>
                            <span>•</span>
                            <span class="text-emerald-400 font-medium">Session: 2026-2027</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center space-x-3 text-xs">
                <!-- Academic Session Pill -->
                <div class="hidden sm:flex items-center space-x-1.5 bg-slate-800/80 px-2.5 py-1.5 rounded-md border border-slate-700/60 text-slate-300">
                    <i class="fa-regular fa-calendar-check text-blue-400"></i>
                    <span class="font-medium">2026-27</span>
                </div>

                <!-- Management Hub Pill -->
                <a href="{{ route('management.index') }}" class="hidden md:flex items-center space-x-1.5 bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 px-2.5 py-1.5 rounded-md border border-indigo-500/40 transition">
                    <i class="fa-solid fa-layer-group text-xs text-indigo-400"></i>
                    <span class="font-semibold text-xs">Management Hub</span>
                </a>

                <!-- Quick Add Action Button -->
                <div class="relative group">
                    <button class="flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-3 py-1.5 rounded-md shadow-sm transition">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span class="hidden md:inline">Quick Action</span>
                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-slate-900 border border-slate-700 rounded-lg shadow-xl py-1 hidden group-hover:block z-50">
                        <a href="{{ route('students.create') }}" class="flex items-center space-x-2 px-3 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white">
                            <i class="fa-solid fa-user-plus text-blue-400 w-4"></i>
                            <span>Add Student</span>
                        </a>
                        <a href="{{ route('fees.collect') }}" class="flex items-center space-x-2 px-3 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white">
                            <i class="fa-solid fa-receipt text-emerald-400 w-4"></i>
                            <span>Collect Fees (POS)</span>
                        </a>
                        <a href="{{ route('attendance.index') }}" class="flex items-center space-x-2 px-3 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white">
                            <i class="fa-solid fa-clipboard-user text-amber-400 w-4"></i>
                            <span>Mark Attendance</span>
                        </a>
                        <a href="{{ route('staff.create') }}" class="flex items-center space-x-2 px-3 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white">
                            <i class="fa-solid fa-id-badge text-purple-400 w-4"></i>
                            <span>Add Staff</span>
                        </a>
                        <a href="{{ route('management.front-office') }}" class="flex items-center space-x-2 px-3 py-2 text-xs text-slate-200 hover:bg-slate-800 hover:text-white">
                            <i class="fa-solid fa-door-open text-cyan-400 w-4"></i>
                            <span>Gate Pass Issue</span>
                        </a>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 bg-slate-800 hover:bg-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-700 text-slate-200 transition">
                        <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-[10px]">
                            SA
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="font-medium text-xs leading-none">school admin</div>
                            <div class="text-[9px] text-slate-400">School Admin</div>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-slate-900 border border-slate-700 rounded-lg shadow-xl py-1 hidden group-hover:block z-50">
                        <div class="px-3 py-2 border-b border-slate-800">
                            <div class="font-medium text-xs text-white">school admin</div>
                            <div class="text-[10px] text-slate-400">admin@yugschool.com</div>
                        </div>
                        <a href="{{ route('dashboard') }}" class="block px-3 py-1.5 text-xs text-slate-300 hover:bg-slate-800">Dashboard</a>
                        <a href="{{ route('management.index') }}" class="block px-3 py-1.5 text-xs text-indigo-300 hover:bg-slate-800">Management Suite</a>
                        <a href="{{ route('demo.login', 'superadmin') }}" class="block px-3 py-1.5 text-xs text-slate-300 hover:bg-slate-800">Switch to Superadmin</a>
                        <div class="border-t border-slate-800 my-1"></div>
                        <a href="{{ route('logout') }}" class="block px-3 py-1.5 text-xs text-red-400 hover:bg-slate-800">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Theme Switcher Strip (10 Layouts) -->
        <div class="bg-slate-950 px-4 py-1.5 border-t border-slate-800/80 flex items-center space-x-2 text-[11px] overflow-x-auto custom-scroll">
            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] flex items-center space-x-1 shrink-0">
                <i class="fa-solid fa-palette text-blue-400"></i>
                <span>Themes:</span>
            </span>
            <a href="{{ route('theme.set', 'executive-pro-x') }}" class="shrink-0 px-2.5 py-0.5 rounded-full {{ ($currentTheme ?? 'executive-pro-x') == 'executive-pro-x' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
                Executive Pro X ✦✦ (Default)
            </a>
            <a href="{{ route('theme.set', 'executive-pro') }}" class="shrink-0 px-2.5 py-0.5 rounded-full {{ ($currentTheme ?? '') == 'executive-pro' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
                Executive Pro ✦
            </a>
            <a href="{{ route('theme.set', 'macos') }}" class="shrink-0 px-2.5 py-0.5 rounded-full {{ ($currentTheme ?? '') == 'macos' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
                Mac OS Style 🍎
            </a>
            <a href="{{ route('theme.set', 'command-center') }}" class="shrink-0 px-2.5 py-0.5 rounded-full {{ ($currentTheme ?? '') == 'command-center' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
                Futuristic Command Center 🚀
            </a>
            <a href="{{ route('theme.set', 'action-v3') }}" class="shrink-0 px-2.5 py-0.5 rounded-full {{ ($currentTheme ?? '') == 'action-v3' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
                Action V3 (Smart) ✨
            </a>
            <a href="{{ route('theme.set', 'analytics-pro') }}" class="shrink-0 px-2.5 py-0.5 rounded-full {{ ($currentTheme ?? '') == 'analytics-pro' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
                Analytics Pro 📊
            </a>
            <a href="{{ route('theme.set', 'classic') }}" class="shrink-0 px-2.5 py-0.5 rounded-full {{ ($currentTheme ?? '') == 'classic' ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-300 hover:bg-slate-800' }}">
                Classic Legacy
            </a>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar Navigation (Dark Navy) with TRUE ACCORDION TREEVIEWS (ALL 21 MANAGEMENT MODULES) -->
        <aside id="main-sidebar" class="w-64 bg-slate-900 text-slate-300 border-r border-slate-800 flex flex-col shrink-0 overflow-y-auto custom-scroll">
            
            <!-- Live Sidebar Search Bar -->
            <div class="p-2.5 border-b border-slate-800/80 sticky top-0 bg-slate-900 z-10">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-500 text-xs"></i>
                    <input type="text" oninput="filterSidebarMenu(this.value)" placeholder="Search 21 Modules..." class="w-full bg-slate-800/90 text-slate-200 text-xs rounded-lg pl-8 pr-3 py-1.5 border border-slate-700/60 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500">
                </div>
            </div>

            <div class="p-2.5 text-[10px] font-bold tracking-wider uppercase text-slate-500">Core Navigation</div>
            <nav id="sidebar-nav" class="space-y-0.5 px-2 text-xs pb-10">
                <!-- Dashboard Link -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                    <i class="fa-solid fa-gauge-high w-4 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- ======================================================== -->
                <!-- 🔥 COMPLETE MANAGEMENT ACCORDION (21 MODULES EXACT MATCH)-->
                <!-- ======================================================== -->
                <div class="pt-3 pb-1.5 px-3 flex items-center justify-between border-t border-slate-800/80 mt-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">MANAGEMENT (21)</span>
                    <a href="{{ route('management.index') }}" class="text-[10px] font-bold text-slate-400 hover:text-indigo-400 uppercase">Hub</a>
                </div>

                <!-- 1. Finance & Fees Treeview (16 Sub-items) -->
                @php $isFeesActive = request()->routeIs('fees.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-fees')" id="menu-fees-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isFeesActive ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-wallet w-4 text-emerald-400 text-center"></i>
                            <span>Finance & Fees</span>
                        </div>
                        <i id="menu-fees-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isFeesActive ? 'rotate-90 text-emerald-400' : '' }}"></i>
                    </a>
                    <ul id="menu-fees" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isFeesActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('fees.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.dashboard') ? 'active-sublink' : '' }}">Fees Dashboard</a></li>
                        <li><a href="{{ route('fees.collect') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 font-bold hover:text-emerald-300 hover:bg-slate-800 {{ request()->routeIs('fees.collect') ? 'active-sublink' : '' }}">⚡ Collect Fees (POS)</a></li>
                        <li><a href="{{ route('fees.due') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.due') ? 'active-sublink' : '' }}">Search Due Fees</a></li>
                        <li><a href="{{ route('fees.transactions') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.transactions') ? 'active-sublink' : '' }}">All Transactions</a></li>
                        <li><a href="{{ route('fees.online') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.online') ? 'active-sublink' : '' }}">Online Transactions</a></li>
                        <li><a href="{{ route('fees.challans') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.challans') ? 'active-sublink' : '' }}">Fee Challans</a></li>
                        <li><a href="{{ route('fees.assign') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.assign') ? 'active-sublink' : '' }}">Assign Fees</a></li>
                        <li><a href="{{ route('fees.carry-forward') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.carry-forward') ? 'active-sublink' : '' }}">Fees Carry Forward</a></li>
                        <li><a href="{{ route('fees.groups') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.groups') ? 'active-sublink' : '' }}">Fee Groups</a></li>
                        <li><a href="{{ route('fees.discounts') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.discounts') ? 'active-sublink' : '' }}">Fees Discount</a></li>
                        <li><a href="{{ route('fees.types') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.types') ? 'active-sublink' : '' }}">Fee Types</a></li>
                        <li><a href="{{ route('fees.due-slips') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.due-slips') ? 'active-sublink' : '' }}">Generate Due Slip</a></li>
                        <li><a href="{{ route('fees.due-slips-history') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.due-slips-history') ? 'active-sublink' : '' }}">Due Slip History</a></li>
                        <li><a href="{{ route('fees.audit') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('fees.audit') ? 'active-sublink' : '' }}">Fee Data Audit</a></li>
                        <li><a href="{{ route('fees.imports') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-400 hover:text-indigo-300 hover:bg-slate-800 {{ request()->routeIs('fees.imports') ? 'active-sublink' : '' }}">Import Center <span class="bg-indigo-900 text-indigo-200 text-[9px] px-1 rounded font-bold">NEW</span></a></li>
                        <li><a href="{{ route('fees.fine-rules') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-amber-400 hover:text-amber-300 hover:bg-slate-800 {{ request()->routeIs('fees.fine-rules') ? 'active-sublink' : '' }}">Late Fine Rules <span class="bg-amber-900 text-amber-200 text-[9px] px-1 rounded font-bold">NEW</span></a></li>
                    </ul>
                </div>

                <!-- 2. Accounts Treeview (Exact match to demo) -->
                @php $isAccountsActive = request()->routeIs('accounts.*') || request()->routeIs('management.accounts'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-accounts')" id="menu-accounts-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isAccountsActive ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-calculator w-4 text-emerald-500 text-center"></i>
                            <span>Accounts</span>
                        </div>
                        <i id="menu-accounts-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isAccountsActive ? 'rotate-90 text-emerald-400' : '' }}"></i>
                    </a>
                    <ul id="menu-accounts" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isAccountsActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('accounts.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('accounts.dashboard') || request()->routeIs('management.accounts') ? 'active-sublink' : '' }}">Accounts Dashboard</a></li>
                        <li><a href="{{ route('accounts.incomes.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 font-bold hover:text-emerald-300 hover:bg-slate-800 {{ request()->routeIs('accounts.incomes.*') ? 'active-sublink' : '' }}">Income</a></li>
                        <li><a href="{{ route('accounts.expenses.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-rose-400 font-bold hover:text-rose-300 hover:bg-slate-800 {{ request()->routeIs('accounts.expenses.*') ? 'active-sublink' : '' }}">Expense</a></li>
                        <li><a href="{{ route('accounts.income-heads.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('accounts.income-heads.*') ? 'active-sublink' : '' }}">Income Heads</a></li>
                        <li><a href="{{ route('accounts.expense-heads.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('accounts.expense-heads.*') ? 'active-sublink' : '' }}">Expense Heads</a></li>
                        <li><a href="{{ route('accounts.bank-accounts.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-purple-400 font-bold hover:text-purple-300 hover:bg-slate-800 {{ request()->routeIs('accounts.bank-accounts.*') ? 'active-sublink' : '' }}">Bank Accounts</a></li>
                        <li><a href="{{ route('accounts.day-book') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-400 hover:text-indigo-300 hover:bg-slate-800 {{ request()->routeIs('accounts.day-book') ? 'active-sublink' : '' }}">Day Book</a></li>
                        <li><a href="{{ route('accounts.chart-of-accounts') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-blue-400 hover:text-blue-300 hover:bg-slate-800 {{ request()->routeIs('accounts.chart-of-accounts') ? 'active-sublink' : '' }}">Chart of Accounts</a></li>
                    </ul>
                </div>

                <!-- 3. Student Information Treeview (12 Sub-items) -->
                @php $isStudentActive = request()->routeIs('students.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-students')" id="menu-students-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isStudentActive ? 'bg-slate-800 text-blue-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-user-graduate w-4 text-blue-400 text-center"></i>
                            <span>Student Information</span>
                        </div>
                        <i id="menu-students-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isStudentActive ? 'rotate-90 text-blue-400' : '' }}"></i>
                    </a>
                    <ul id="menu-students" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isStudentActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('students.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('students.dashboard') ? 'active-sublink' : '' }}">Student Dashboard</a></li>
                        <li><a href="{{ route('students.create') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-blue-400 font-bold hover:text-blue-300 hover:bg-slate-800 {{ request()->routeIs('students.create') ? 'active-sublink' : '' }}">⚡ Student Admission</a></li>
                        <li><a href="{{ route('students.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('students.index') ? 'active-sublink' : '' }}">Student List</a></li>
                        <li><a href="{{ route('students.face-search') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-400 hover:text-indigo-300 hover:bg-slate-800 {{ request()->routeIs('students.face-search') ? 'active-sublink' : '' }}">Search by Photo</a></li>
                        <li><a href="{{ route('students.parents') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-purple-400 hover:text-purple-300 hover:bg-slate-800 {{ request()->routeIs('students.parents') ? 'active-sublink' : '' }}">Parents & Guardians</a></li>
                        <li><a href="{{ route('students.attendance') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-amber-400 font-semibold hover:text-amber-300 hover:bg-slate-800 {{ request()->routeIs('students.attendance') ? 'active-sublink' : '' }}">Student Attendance</a></li>
                        <li><a href="{{ route('students.behavior') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 hover:text-emerald-300 hover:bg-slate-800 {{ request()->routeIs('students.behavior') ? 'active-sublink' : '' }}">Behavior Records</a></li>
                        <li><a href="{{ route('students.houses') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-rose-400 hover:text-rose-300 hover:bg-slate-800 {{ request()->routeIs('students.houses') ? 'active-sublink' : '' }}">Student Houses</a></li>
                        <li><a href="{{ route('students.categories') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-cyan-400 hover:text-cyan-300 hover:bg-slate-800 {{ request()->routeIs('students.categories') ? 'active-sublink' : '' }}">Student Categories</a></li>
                        <li><a href="{{ route('students.exits') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('students.exits') ? 'active-sublink' : '' }}">TC & Exit</a></li>
                        <li><a href="{{ route('students.health') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-teal-400 hover:text-teal-300 hover:bg-slate-800 {{ request()->routeIs('students.health') ? 'active-sublink' : '' }}">Health Records</a></li>
                        <li><a href="{{ route('students.recycle-bin') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-red-400 hover:text-red-300 hover:bg-slate-800 {{ request()->routeIs('students.recycle-bin') ? 'active-sublink' : '' }}">Deleted Students <span class="bg-red-900/60 text-red-300 text-[9px] px-1 rounded font-bold">NEW</span></a></li>
                    </ul>
                </div>

                <!-- 4. Academics Treeview (11 Sub-items) -->
                @php $isAcademicsActive = request()->routeIs('academics.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-academics')" id="menu-academics-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isAcademicsActive ? 'bg-slate-800 text-indigo-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-school w-4 text-indigo-400 text-center"></i>
                            <span>Academics</span>
                        </div>
                        <i id="menu-academics-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isAcademicsActive ? 'rotate-90 text-indigo-400' : '' }}"></i>
                    </a>
                    <ul id="menu-academics" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isAcademicsActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('academics.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('academics.dashboard') || request()->routeIs('academics.index')) ? 'active-sublink' : '' }}">Academic Dashboard</a></li>
                        <li><a href="{{ route('academics.sessions') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.sessions*') ? 'active-sublink' : '' }}">Academic Sessions</a></li>
                        <li><a href="{{ route('academics.classes') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.classes*') ? 'active-sublink' : '' }}">Classes</a></li>
                        <li><a href="{{ route('academics.sections') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.sections*') ? 'active-sublink' : '' }}">Sections</a></li>
                        <li><a href="{{ route('academics.subjects') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.subjects*') ? 'active-sublink' : '' }}">Subjects</a></li>
                        <li><a href="{{ route('academics.assign-subjects') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-300 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.assign-subjects*') ? 'active-sublink' : '' }}">Assign Subjects <span class="bg-indigo-900 text-indigo-200 text-[9px] px-1 rounded">NEW</span></a></li>
                        <li><a href="{{ route('academics.assign-electives') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.assign-electives*') ? 'active-sublink' : '' }}">Assign Electives</a></li>
                        <li><a href="{{ route('academics.assign-class-teacher') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.assign-class-teacher*') ? 'active-sublink' : '' }}">Assign Class Teacher</a></li>
                        <li><a href="{{ route('academics.time-slots') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('academics.time-slots*') || request()->routeIs('academics.period-sets*') ? 'active-sublink' : '' }}">Manage Periods</a></li>
                        <li><a href="{{ route('academics.timetable') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-400 font-bold hover:text-indigo-300 hover:bg-slate-800 {{ request()->routeIs('academics.timetable*') ? 'active-sublink' : '' }}">⚡ Class Timetable</a></li>
                        <li><a href="{{ route('academics.promote-students') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 font-semibold hover:text-emerald-300 hover:bg-slate-800 {{ request()->routeIs('academics.promote*') ? 'active-sublink' : '' }}">Promote Students</a></li>
                    </ul>
                </div>

                <!-- 5. School Website Treeview (10 Sub-items) -->
                @php $isWebsiteActive = request()->routeIs('website.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-website')" id="menu-website-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isWebsiteActive ? 'bg-slate-800 text-sky-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-globe w-4 text-sky-400 text-center"></i>
                            <span>School Website</span>
                        </div>
                        <i id="menu-website-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isWebsiteActive ? 'rotate-90 text-sky-400' : '' }}"></i>
                    </a>
                    <ul id="menu-website" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isWebsiteActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('website.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('website.index') ? 'active-sublink' : '' }}">Website Overview</a></li>
                        <li><a href="{{ route('website.pages') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('website.pages') ? 'active-sublink' : '' }}">Pages</a></li>
                        <li><a href="{{ route('website.slides') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('website.slides') ? 'active-sublink' : '' }}">Hero Slides</a></li>
                        <li><a href="{{ route('website.facilities') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('website.facilities') ? 'active-sublink' : '' }}">Facilities</a></li>
                        <li><a href="{{ route('website.testimonials') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('website.testimonials') ? 'active-sublink' : '' }}">Testimonials</a></li>
                        <li><a href="{{ route('website.pages') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800">Navigation Menu</a></li>
                        <li><a href="{{ route('website.disclosure') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-rose-400 font-bold hover:text-rose-300 hover:bg-slate-800 {{ request()->routeIs('website.disclosure') ? 'active-sublink' : '' }}">CBSE Disclosure</a></li>
                        <li><a href="{{ route('website.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800">Website Templates</a></li>
                        <li><a href="{{ route('website.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800">Design & Settings</a></li>
                        <li><a href="{{ route('website.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800">Website (Full HTML)</a></li>
                    </ul>
                </div>

                <!-- 6. Front Office Treeview (8 Sub-items) -->
                @php $isFrontOfficeActive = request()->routeIs('front-office.*') || request()->routeIs('management.front-office'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-frontoffice')" id="menu-frontoffice-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isFrontOfficeActive ? 'bg-slate-800 text-cyan-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-desktop w-4 text-cyan-400 text-center"></i>
                            <span>Front Office</span>
                        </div>
                        <i id="menu-frontoffice-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isFrontOfficeActive ? 'rotate-90 text-cyan-400' : '' }}"></i>
                    </a>
                    <ul id="menu-frontoffice" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isFrontOfficeActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('front-office.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('front-office.dashboard') || request()->routeIs('front-office.index')) ? 'active-sublink' : '' }}">Front Office Dashboard</a></li>
                        <li><a href="{{ route('front-office.admission-enquiries') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-cyan-400 font-bold hover:text-cyan-300 hover:bg-slate-800 {{ request()->routeIs('front-office.admission-enquiries*') ? 'active-sublink' : '' }}">Admission Enquiries</a></li>
                        <li><a href="{{ route('front-office.visitor-logs') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('front-office.visitor-logs*') ? 'active-sublink' : '' }}">Visitor Book</a></li>
                        <li><a href="{{ route('front-office.complaints') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-amber-400 hover:text-amber-300 hover:bg-slate-800 {{ request()->routeIs('front-office.complaints*') ? 'active-sublink' : '' }}">Complaints & Grievances</a></li>
                        <li><a href="{{ route('front-office.postal-records') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('front-office.postal-records*') ? 'active-sublink' : '' }}">Postal Records</a></li>
                        <li><a href="{{ route('front-office.gate-passes') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('front-office.gate-passes*') && !request()->routeIs('front-office.gate-terminal*') ? 'active-sublink' : '' }}">Gate Passes</a></li>
                        <li><a href="{{ route('front-office.gate-terminal') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 font-semibold hover:text-emerald-300 hover:bg-slate-800 {{ request()->routeIs('front-office.gate-terminal*') ? 'active-sublink' : '' }}">⚡ Gate Terminal</a></li>
                        <li><a href="{{ route('front-office.campus-workers') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-300 hover:text-white hover:bg-slate-800 {{ request()->routeIs('front-office.campus-workers*') ? 'active-sublink' : '' }}">Campus Workers</a></li>
                    </ul>
                </div>

                <!-- 7. Lead Management Treeview (5 Sub-items) -->
                @php $isLeadsActive = request()->routeIs('leads.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-leads')" id="menu-leads-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isLeadsActive ? 'bg-slate-800 text-cyan-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-bullhorn w-4 text-cyan-400 text-center"></i>
                            <span>Lead Management</span>
                        </div>
                        <i id="menu-leads-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isLeadsActive ? 'rotate-90 text-cyan-400' : '' }}"></i>
                    </a>
                    <ul id="menu-leads" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isLeadsActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('leads.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('leads.dashboard') || request()->routeIs('leads.index')) ? 'active-sublink' : '' }}">Lead Dashboard</a></li>
                        <li><a href="{{ route('leads.board') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-cyan-400 font-bold hover:text-cyan-300 hover:bg-slate-800 {{ request()->routeIs('leads.board') ? 'active-sublink' : '' }}">Pipeline Board (Kanban)</a></li>
                        <li><a href="{{ route('leads.list') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('leads.list') ? 'active-sublink' : '' }}">All Leads Directory</a></li>
                        <li><a href="{{ route('leads.tasks') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-amber-400 hover:text-amber-300 hover:bg-slate-800 {{ request()->routeIs('leads.tasks') ? 'active-sublink' : '' }}">Follow-up Tasks</a></li>
                        <li><a href="{{ route('leads.settings') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('leads.settings') ? 'active-sublink' : '' }}">Sources & Stages</a></li>
                    </ul>
                </div>

                <!-- 8. Offline Examinations Treeview (12 Sub-items Exact Match) -->
                @php $isExamsActive = request()->routeIs('exams.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-exams')" id="menu-exams-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isExamsActive ? 'bg-slate-800 text-purple-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-file-signature w-4 text-purple-400 text-center"></i>
                            <span>Offline Examinations</span>
                        </div>
                        <i id="menu-exams-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isExamsActive ? 'rotate-90 text-purple-400' : '' }}"></i>
                    </a>
                    <ul id="menu-exams" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isExamsActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('exams.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.dashboard') ? 'active-sublink' : '' }}">Exam Dashboard</a></li>
                        <li><a href="{{ route('exams.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('exams.index') || request()->routeIs('exams.create')) ? 'active-sublink' : '' }}">Manage Offline Exams</a></li>
                        <li><a href="{{ route('exams.types') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.types') ? 'active-sublink' : '' }}">Exam Types</a></li>
                        <li><a href="{{ route('exams.schedule') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.schedule') ? 'active-sublink' : '' }}">Schedule & Marks Setup</a></li>
                        <li><a href="{{ route('exams.marks.entry') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-purple-400 font-bold hover:text-purple-300 hover:bg-slate-800 {{ (request()->routeIs('exams.marks.entry') || request()->routeIs('exams.enter-marks')) ? 'active-sublink' : '' }}">⚡ Enter Marks</a></li>
                        <li><a href="{{ route('exams.cocurricular') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.cocurricular') ? 'active-sublink' : '' }}">Cocurricular Grades</a></li>
                        <li><a href="{{ route('exams.grades') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.grades') ? 'active-sublink' : '' }}">Manage Grades</a></li>
                        <li><a href="{{ route('exams.marksheet.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-400 font-semibold hover:text-indigo-300 hover:bg-slate-800 {{ request()->routeIs('exams.marksheet.index') ? 'active-sublink' : '' }}">Generate Marksheet</a></li>
                        <li><a href="{{ route('exams.marksheet.upload') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.marksheet.upload') ? 'active-sublink' : '' }}">Upload Marksheet</a></li>
                        <li><a href="{{ route('exams.marksheet.upload-list') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.marksheet.upload-list') ? 'active-sublink' : '' }}">Manage Uploads</a></li>
                        <li><a href="{{ route('exams.marksheet.remarks') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('exams.marksheet.remarks') ? 'active-sublink' : '' }}">Teacher Remarks</a></li>
                        <li><a href="{{ route('exams.admit-cards') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-amber-400 font-bold hover:text-amber-300 hover:bg-slate-800 {{ request()->routeIs('exams.admit-cards') ? 'active-sublink' : '' }}">Admit Cards (Hall Tickets)</a></li>
                    </ul>
                </div>

                <!-- 9. CBC Academics Treeview (6 Sub-items Exact Match) -->
                @php $isCBCActive = request()->routeIs('cbc.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-cbc')" id="menu-cbc-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isCBCActive ? 'bg-slate-800 text-violet-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-shapes w-4 text-violet-400 text-center"></i>
                            <span>CBC Academics</span>
                        </div>
                        <i id="menu-cbc-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isCBCActive ? 'rotate-90 text-violet-400' : '' }}"></i>
                    </a>
                    <ul id="menu-cbc" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isCBCActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('cbc.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('cbc.dashboard') || request()->routeIs('cbc.index')) ? 'active-sublink' : '' }}">CBC Dashboard</a></li>
                        <li><a href="{{ route('cbc.strands') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('cbc.strands') ? 'active-sublink' : '' }}">Strands & Outcomes</a></li>
                        <li><a href="{{ route('cbc.assessments') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-violet-400 font-bold hover:text-violet-300 hover:bg-slate-800 {{ request()->routeIs('cbc.assessments') ? 'active-sublink' : '' }}">CBC Assessments</a></li>
                        <li><a href="{{ route('cbc.competencies') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('cbc.competencies') ? 'active-sublink' : '' }}">Core Competencies</a></li>
                        <li><a href="{{ route('cbc.pathways') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('cbc.pathways') ? 'active-sublink' : '' }}">Pathways & Tracks</a></li>
                        <li><a href="{{ route('cbc.reports') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-indigo-400 font-semibold hover:text-indigo-300 hover:bg-slate-800 {{ request()->routeIs('cbc.reports*') ? 'active-sublink' : '' }}">CBC Reports</a></li>
                    </ul>
                </div>

                <!-- 10. Online Examinations Treeview (4 Sub-items) -->
                @php $isOnlineExamsActive = request()->routeIs('online-exams.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-online-exams')" id="menu-online-exams-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isOnlineExamsActive ? 'bg-slate-800 text-blue-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-laptop-code w-4 text-blue-400 text-center"></i>
                            <span>Online Examinations</span>
                        </div>
                        <i id="menu-online-exams-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isOnlineExamsActive ? 'rotate-90 text-blue-400' : '' }}"></i>
                    </a>
                    <ul id="menu-online-exams" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isOnlineExamsActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('online-exams.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('online-exams.dashboard') ? 'active-sublink' : '' }}">Online Exam Dashboard</a></li>
                        <li><a href="{{ route('online-exams.manage') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('online-exams.manage*') ? 'active-sublink' : '' }}">Manage Online Exams</a></li>
                        <li><a href="{{ route('online-exams.question-bank') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('online-exams.question-bank*') ? 'active-sublink' : '' }}">Question Bank</a></li>
                        <li><a href="{{ route('online-exams.question-topics') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('online-exams.question-topics*') ? 'active-sublink' : '' }}">Chapters & Topics</a></li>
                    </ul>
                </div>

                <!-- 11. Human Resource Treeview (16 Sub-items) -->
                @php $isHRActive = request()->routeIs('staff.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-hr')" id="menu-hr-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isHRActive ? 'bg-slate-800 text-pink-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-sitemap w-4 text-pink-400 text-center"></i>
                            <span>Human Resource</span>
                        </div>
                        <i id="menu-hr-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isHRActive ? 'rotate-90 text-pink-400' : '' }}"></i>
                    </a>
                    <ul id="menu-hr" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isHRActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('staff.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.index') ? 'active-sublink' : '' }}">Staff Directory</a></li>
                        <li><a href="{{ route('staff.create') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-pink-400 font-bold hover:text-pink-300 hover:bg-slate-800 {{ request()->routeIs('staff.create') ? 'active-sublink' : '' }}">⚡ Add Staff</a></li>
                        <li><a href="{{ route('staff.attendance') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.attendance*') ? 'active-sublink' : '' }}">Staff Attendance</a></li>
                        <li><a href="{{ route('staff.payroll') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.payroll*') ? 'active-sublink' : '' }}">Payroll</a></li>
                        <li><a href="{{ route('staff.salary') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.salary*') ? 'active-sublink' : '' }}">Set Salary</a></li>
                        <li><a href="{{ route('staff.salary-templates') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.salary-templates*') ? 'active-sublink' : '' }}">Salary Templates</a></li>
                        <li><a href="{{ route('staff.approve-leave') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.approve-leave*') ? 'active-sublink' : '' }}">Approve Leave</a></li>
                        <li><a href="{{ route('staff.leave-types') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.leave-types*') ? 'active-sublink' : '' }}">Leave Types</a></li>
                        <li><a href="{{ route('staff.departments') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.departments*') ? 'active-sublink' : '' }}">Departments</a></li>
                        <li><a href="{{ route('staff.designations') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.designations*') ? 'active-sublink' : '' }}">Designations</a></li>
                        <li><a href="{{ route('staff.loans') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.loans*') ? 'active-sublink' : '' }}">Manage Staff Loans</a></li>
                        <li><a href="{{ route('staff.id-card') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('staff.id-card*') ? 'active-sublink' : '' }}">Staff ID Card</a></li>
                    </ul>
                </div>

                <!-- 12. PTM Meetings Treeview (6 Sub-items Matching Live Demo) -->
                @php $isPTMActive = request()->routeIs('ptm.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-ptm')" id="menu-ptm-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isPTMActive ? 'bg-slate-800 text-amber-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-handshake-angle w-4 text-amber-400 text-center"></i>
                            <span>PTM Meetings</span>
                        </div>
                        <i id="menu-ptm-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isPTMActive ? 'rotate-90 text-amber-400' : '' }}"></i>
                    </a>
                    <ul id="menu-ptm" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isPTMActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('ptm.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('ptm.dashboard') ? 'active-sublink' : '' }}">PTM Dashboard</a></li>
                        <li><a href="{{ route('ptm.meetings') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('ptm.meetings*') ? 'active-sublink' : '' }}">PTM Schedule Meetings</a></li>
                        <li><a href="{{ route('ptm.guide') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('ptm.guide') ? 'active-sublink' : '' }}">PTM Guide</a></li>
                        <li><a href="{{ route('ptm.record') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('ptm.record*') ? 'active-sublink' : '' }}">PTM Attendance & Remarks</a></li>
                        <li><a href="{{ route('ptm.followups') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('ptm.followups*') ? 'active-sublink' : '' }}">PTM Follow-ups</a></li>
                        <li><a href="{{ route('ptm.reports') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('ptm.reports') ? 'active-sublink' : '' }}">PTM Reports</a></li>
                    </ul>
                </div>

                <!-- 13. Lesson Planner Treeview (8 Sub-items Matching Live Demo) -->
                @php $isLessonActive = request()->routeIs('lesson-plans.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-lessons')" id="menu-lessons-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isLessonActive ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-book-reader w-4 text-emerald-400 text-center"></i>
                            <span>Lesson Planner</span>
                        </div>
                        <i id="menu-lessons-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isLessonActive ? 'rotate-90 text-emerald-400' : '' }}"></i>
                    </a>
                    <ul id="menu-lessons" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isLessonActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('lesson-plans.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('lesson-plans.dashboard') ? 'active-sublink' : '' }}">Lesson Planner Dashboard</a></li>
                        <li><a href="{{ route('lesson-plans.plans') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 font-bold hover:text-emerald-300 hover:bg-slate-800 {{ request()->routeIs('lesson-plans.plans*') ? 'active-sublink' : '' }}">Lesson Plans</a></li>
                        <li><a href="{{ route('lesson-plans.guide') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('lesson-plans.guide') ? 'active-sublink' : '' }}">Lesson Planner Guide</a></li>
                        <li><a href="{{ route('lesson-plans.review') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('lesson-plans.review*') ? 'active-sublink' : '' }}">Lesson Plan Review</a></li>
                        <li><a href="{{ route('lesson-plans.approvals') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('lesson-plans.approvals*') ? 'active-sublink' : '' }}">Lesson Plan Approvals</a></li>
                        <li><a href="{{ route('lesson-plans.coverage') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('lesson-plans.coverage*') ? 'active-sublink' : '' }}">Lesson Plan Coverage</a></li>
                        <li><a href="{{ route('lesson-plans.reports') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('lesson-plans.reports*') ? 'active-sublink' : '' }}">Lesson Plan Reports</a></li>
                        <li><a href="{{ route('lesson-plans.settings') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('lesson-plans.settings*') ? 'active-sublink' : '' }}">Lesson Planner Settings</a></li>
                    </ul>
                </div>

                <!-- 14. OSM Module (6 Sub-items) -->
                @php $isOSMActive = request()->routeIs('osm.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-osm')" id="menu-osm-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isOSMActive ? 'bg-slate-800 text-rose-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-pen-nib w-4 text-rose-400 text-center"></i>
                            <span>OSM Module</span>
                        </div>
                        <i id="menu-osm-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isOSMActive ? 'rotate-90 text-rose-400' : '' }}"></i>
                    </a>
                    <ul id="menu-osm" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isOSMActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('osm.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('osm.dashboard*') ? 'active-sublink' : '' }}">OSM Dashboard</a></li>
                        <li><a href="{{ route('osm.sessions') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('osm.sessions*') ? 'active-sublink' : '' }}">OSM Sessions</a></li>
                        <li><a href="{{ route('osm.evaluate') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-rose-400 font-bold hover:text-rose-300 hover:bg-slate-800 {{ request()->routeIs('osm.evaluate*') ? 'active-sublink' : '' }}">OSM Evaluate</a></li>
                        <li><a href="{{ route('osm.moderation') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('osm.moderation*') ? 'active-sublink' : '' }}">OSM Moderation</a></li>
                        <li><a href="{{ route('osm.reports') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('osm.reports*') ? 'active-sublink' : '' }}">OSM Reports</a></li>
                        <li><a href="{{ route('osm.guide') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('osm.guide*') ? 'active-sublink' : '' }}">OSM Guide</a></li>
                    </ul>
                </div>

                <!-- 15. QR Code Attendance (4 Sub-items) -->
                @php $isQrActive = request()->routeIs('qr-attendance.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-qr')" id="menu-qr-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isQrActive ? 'bg-slate-800 text-cyan-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-qrcode w-4 text-cyan-400 text-center"></i>
                            <span>QR Code Attendance</span>
                        </div>
                        <i id="menu-qr-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isQrActive ? 'rotate-90 text-cyan-400' : '' }}"></i>
                    </a>
                    <ul id="menu-qr" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isQrActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('qr-attendance.scanner') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-cyan-400 font-semibold hover:text-cyan-300 hover:bg-slate-800 {{ request()->routeIs('qr-attendance.scanner*') ? 'active-sublink' : '' }}">QR Attendance</a></li>
                        <li><a href="{{ route('qr-attendance.settings') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('qr-attendance.settings*') ? 'active-sublink' : '' }}">QR Attendance Setting</a></li>
                        <li><a href="{{ route('qr-attendance.report') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('qr-attendance.report*') ? 'active-sublink' : '' }}">QR Attendance Report</a></li>
                        <li><a href="{{ route('qr-attendance.audit') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('qr-attendance.audit*') ? 'active-sublink' : '' }}">QR Scan Audit</a></li>
                    </ul>
                </div>

                <!-- 16. Biometric Devices (4 Sub-items) -->
                @php $isDevicesActive = request()->routeIs('biometric.*') || request()->routeIs('management.devices'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-devices')" id="menu-devices-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isDevicesActive ? 'bg-slate-800 text-teal-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-fingerprint w-4 text-teal-400 text-center"></i>
                            <span>Biometric Devices</span>
                        </div>
                        <i id="menu-devices-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isDevicesActive ? 'rotate-90 text-teal-400' : '' }}"></i>
                    </a>
                    <ul id="menu-devices" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isDevicesActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('biometric.devices') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('biometric.devices*') ? 'active-sublink' : '' }}">All Devices</a></li>
                        <li><a href="{{ route('biometric.logs') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-teal-400 font-semibold hover:text-teal-300 hover:bg-slate-800 {{ request()->routeIs('biometric.logs*') ? 'active-sublink' : '' }}">Attendance Logs</a></li>
                        <li><a href="{{ route('biometric.monitoring-logs') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-purple-400 hover:text-purple-300 hover:bg-slate-800 {{ request()->routeIs('biometric.monitoring-logs*') ? 'active-sublink' : '' }}">Face Monitoring</a></li>
                        <li><a href="{{ route('biometric.system-logs') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('biometric.system-logs*') ? 'active-sublink' : '' }}">Agent Logs</a></li>
                    </ul>
                </div>

                <!-- 17. Assessment (4 Sub-items) -->
                @php $isAssessmentActive = request()->routeIs('assessment.*') || request()->routeIs('assessments.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-assessment')" id="menu-assessment-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isAssessmentActive ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-clipboard-check w-4 text-emerald-400 text-center"></i>
                            <span>Assessment</span>
                        </div>
                        <i id="menu-assessment-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isAssessmentActive ? 'rotate-90 text-emerald-400' : '' }}"></i>
                    </a>
                    <ul id="menu-assessment" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isAssessmentActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('assessment.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('assessment.dashboard*') ? 'active-sublink' : '' }}">Assessment Dashboard</a></li>
                        <li><a href="{{ route('assessment.assessments') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 font-bold hover:text-emerald-300 hover:bg-slate-800 {{ request()->routeIs('assessment.assessments*') ? 'active-sublink' : '' }}">Assessments</a></li>
                        <li><a href="{{ route('assessment.guide') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('assessment.guide*') ? 'active-sublink' : '' }}">Assessment Guide</a></li>
                        <li><a href="{{ route('assessment.reports') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('assessment.reports*') ? 'active-sublink' : '' }}">Assessment Reports</a></li>
                    </ul>
                </div>

                <!-- 18. Surveys & Feedback (5 Sub-items) -->
                @php $isSurveysActive = request()->routeIs('survey.*') || request()->routeIs('feedback.*') || request()->routeIs('surveys.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-surveys')" id="menu-surveys-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isSurveysActive ? 'bg-slate-800 text-amber-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-poll w-4 text-amber-400 text-center"></i>
                            <span>Surveys & Feedback</span>
                        </div>
                        <i id="menu-surveys-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isSurveysActive ? 'rotate-90 text-amber-400' : '' }}"></i>
                    </a>
                    <ul id="menu-surveys" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isSurveysActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('survey.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('survey.dashboard') ? 'active-sublink text-amber-400 font-bold' : '' }}">Survey Dashboard</a></li>
                        <li><a href="{{ route('survey.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('survey.index') ? 'active-sublink text-amber-400 font-bold' : '' }}">All Surveys</a></li>
                        <li><a href="{{ route('survey.mine') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('survey.mine') ? 'active-sublink text-amber-400 font-bold' : '' }}">My Surveys</a></li>
                        <li><a href="{{ route('feedback.index') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('feedback.*') ? 'active-sublink text-amber-400 font-bold' : '' }}">Feedback Triage</a></li>
                        <li><a href="{{ route('survey.guide') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('survey.guide') ? 'active-sublink text-amber-400 font-bold' : '' }}">Survey Guide</a></li>
                    </ul>
                </div>

                <!-- 19. Compliance & Governance (11 Sub-items) -->
                @php $isComplianceActive = request()->routeIs('compliance.*') || request()->routeIs('management.compliance'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-compliance')" id="menu-compliance-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isComplianceActive ? 'bg-slate-800 text-rose-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-shield-alt w-4 text-rose-400 text-center"></i>
                            <span>Compliance & Governance</span>
                        </div>
                        <i id="menu-compliance-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isComplianceActive ? 'rotate-90 text-rose-400' : '' }}"></i>
                    </a>
                    <ul id="menu-compliance" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isComplianceActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('compliance.overview') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.overview') ? 'active-sublink text-rose-400 font-bold' : '' }}">Compliance Overview</a></li>
                        <li><a href="{{ route('compliance.profile') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.profile*') ? 'active-sublink text-rose-400 font-bold' : '' }}">School Profile</a></li>
                        <li><a href="{{ route('compliance.packs') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.packs*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Compliance Packs</a></li>
                        <li><a href="{{ route('compliance.fields') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.fields*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Field Settings</a></li>
                        <li><a href="{{ route('compliance.documents') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.documents*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Document Vault</a></li>
                        <li><a href="{{ route('compliance.checklist') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.checklist*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Compliance Checklist</a></li>
                        <li><a href="{{ route('compliance.records') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.records*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Data Records</a></li>
                        <li><a href="{{ route('compliance.validator') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.validator*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Data Validator</a></li>
                        <li><a href="{{ route('compliance.reports') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.reports*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Government Reports</a></li>
                        <li><a href="{{ route('compliance.inspections') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.inspections*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Inspections</a></li>
                        <li><a href="{{ route('compliance.calendar') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ request()->routeIs('compliance.calendar*') ? 'active-sublink text-rose-400 font-bold' : '' }}">Compliance Calendar</a></li>
                    </ul>
                </div>

                <!-- 20. Holistic Progress Card (5 Sub-items) -->
                @php $isHPCActive = request()->routeIs('hpc.*') || request()->routeIs('management.hpc-cbc'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-hpc')" id="menu-hpc-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isHPCActive ? 'bg-slate-800 text-violet-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-child w-4 text-violet-400 text-center"></i>
                            <span>Holistic Progress Card</span>
                        </div>
                        <i id="menu-hpc-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isHPCActive ? 'rotate-90 text-violet-400' : '' }}"></i>
                    </a>
                    <ul id="menu-hpc" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isHPCActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('hpc.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('hpc.dashboard') ? 'bg-violet-900/60 text-violet-300 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">HPC Dashboard <span class="bg-violet-900 text-violet-200 text-[9px] px-1 rounded">NEW</span></a></li>
                        <li><a href="{{ route('hpc.activities') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('hpc.activities*') ? 'bg-violet-900/60 text-violet-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">HPC Activities</a></li>
                        <li><a href="{{ route('hpc.cards') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('hpc.cards*') ? 'bg-violet-900/60 text-violet-300 font-semibold' : 'text-violet-400 font-medium hover:text-violet-300 hover:bg-slate-800' }}">Progress Cards</a></li>
                        <li><a href="{{ route('hpc.frameworks') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('hpc.frameworks*') ? 'bg-violet-900/60 text-violet-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">HPC Frameworks</a></li>
                        <li><a href="{{ route('hpc.card-appearance') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('hpc.card-appearance') ? 'bg-violet-900/60 text-violet-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Card Appearance</a></li>
                    </ul>
                </div>

                <!-- 21. Classrooms (12 Sub-items) -->
                @php $isRoomsActive = request()->routeIs('classrooms.*') || request()->routeIs('management.classrooms'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-rooms')" id="menu-rooms-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isRoomsActive ? 'bg-slate-800 text-orange-400' : 'text-slate-300' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-door-open w-4 text-orange-400 text-center"></i>
                            <span>Classrooms <span class="bg-orange-500/20 text-orange-400 text-[9px] px-1 rounded font-bold">NEW</span></span>
                        </div>
                        <i id="menu-rooms-arrow" class="fa-solid fa-chevron-right text-[9px] transition-transform duration-200 text-slate-500 {{ $isRoomsActive ? 'rotate-90 text-orange-400' : '' }}"></i>
                    </a>
                    <ul id="menu-rooms" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isRoomsActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('classrooms.rooms') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.rooms') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Classroom List</a></li>
                        <li><a href="{{ route('classrooms.buildings') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.buildings') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-orange-400 font-medium hover:text-orange-300 hover:bg-slate-800' }}">Buildings & Blocks</a></li>
                        <li><a href="{{ route('classrooms.labels') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.labels') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Room Label Matching</a></li>
                        <li><a href="{{ route('classrooms.plan') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.plan') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Room Plan</a></li>
                        <li><a href="{{ route('classrooms.plan.capacity') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.plan.capacity') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Capacity Report</a></li>
                        <li><a href="{{ route('classrooms.timetable') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.timetable') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Room Timetable</a></li>
                        <li><a href="{{ route('classrooms.free-rooms') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.free-rooms') ? 'bg-emerald-900/60 text-emerald-300 font-semibold' : 'text-emerald-400 hover:text-emerald-300 hover:bg-slate-800' }}">Free Rooms</a></li>
                        <li><a href="{{ route('classrooms.usage') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.usage') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Room Usage</a></li>
                        <li><a href="{{ route('classrooms.clashes') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.clashes') ? 'bg-red-900/60 text-red-300 font-semibold' : 'text-red-400 hover:text-red-300 hover:bg-slate-800' }}">Room Clashes</a></li>
                        <li><a href="{{ route('classrooms.bookings') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.bookings') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Room Bookings</a></li>
                        <li><a href="{{ route('classrooms.maintenance') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.maintenance') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Room Maintenance</a></li>
                        <li><a href="{{ route('classrooms.guide') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded {{ request()->routeIs('classrooms.guide') ? 'bg-orange-900/60 text-orange-300 font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Classroom Guide</a></li>
                    </ul>
                </div>

                <!-- ============================================== -->
                <!-- 🔥 MODULES & FACILITIES SECTION (19 MODULES EXACT LIVE DEMO) -->
                <!-- ============================================== -->
                <div class="pt-4 pb-1.5 px-3 text-[10px] font-black uppercase tracking-widest text-emerald-400 border-t border-slate-800/80 mt-2">
                    MODULES (19)
                </div>
                
                <!-- 1. Live Classes -->
                @php $isLiveClassActive = request()->routeIs('modules.live-classes*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-live-classes')" id="menu-live-classes-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isLiveClassActive ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-video w-4 text-center"></i>
                            <span>Live Classes</span>
                        </div>
                        <i id="menu-live-classes-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isLiveClassActive ? 'rotate-90 text-blue-400' : '' }}"></i>
                    </a>
                    <ul id="menu-live-classes" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isLiveClassActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.live-classes', ['tab' => 'manage']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLiveClassActive && request('tab') != 'settings' ? 'active-sublink' : '' }}">Manage Live Classes</a></li>
                        <li><a href="{{ route('modules.live-classes', ['tab' => 'settings']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLiveClassActive && request('tab') == 'settings' ? 'active-sublink' : '' }}">Live Class Settings</a></li>
                    </ul>
                </div>

                <!-- 2. Apps Center -->
                @php $isAppsActive = request()->routeIs('modules.apps*'); @endphp
                <a href="{{ route('modules.apps') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition {{ $isAppsActive ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                    <i class="fa-solid fa-th w-4 text-center"></i>
                    <span>Apps Center</span>
                </a>

                <!-- 3. Study Center -->
                @php $isStudyActive = request()->routeIs('modules.study-center*') || request()->routeIs('study-center*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-study-center')" id="menu-study-center-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isStudyActive ? 'bg-slate-800 text-indigo-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-book-reader w-4 text-center"></i>
                            <span>Study Center</span>
                        </div>
                        <i id="menu-study-center-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isStudyActive ? 'rotate-90 text-indigo-400' : '' }}"></i>
                    </a>
                    <ul id="menu-study-center" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isStudyActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('study-center.dashboard') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('study-center.dashboard') || (request()->routeIs('study-center.index') && request('tab', 'dashboard') == 'dashboard')) ? 'active-sublink' : '' }}">Dashboard</a></li>
                        <li><a href="{{ route('study-center.classwork') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('study-center.classwork*') || request('tab') == 'classwork') ? 'active-sublink' : '' }}">Classwork &amp; Logbook</a></li>
                        <li><a href="{{ route('study-center.syllabus') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('study-center.syllabus*') || request('tab') == 'syllabus') ? 'active-sublink' : '' }}">Manage Syllabus</a></li>
                        <li><a href="{{ route('study-center.materials') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('study-center.materials*') || request('tab') == 'materials') ? 'active-sublink' : '' }}">Manage Resources</a></li>
                        <li><a href="{{ route('study-center.homework') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('study-center.homework*') || request('tab') == 'homework') ? 'active-sublink' : '' }}">Homework &amp; Assignments</a></li>
                    </ul>
                </div>

                <!-- 4. Certificates -->
                @php $isCertActive = request()->routeIs('modules.certificates*') || request()->routeIs('certificates.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-certificates')" id="menu-certificates-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isCertActive ? 'bg-slate-800 text-amber-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-certificate w-4 text-center"></i>
                            <span>Certificates</span>
                        </div>
                        <i id="menu-certificates-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isCertActive ? 'rotate-90 text-amber-400' : '' }}"></i>
                    </a>
                    <ul id="menu-certificates" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isCertActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.certificates', ['tab' => 'templates']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCertActive && request('tab', 'templates') == 'templates' ? 'active-sublink' : '' }}">Certificate Templates</a></li>
                        <li><a href="{{ route('modules.certificates', ['tab' => 'generate']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCertActive && request('tab') == 'generate' ? 'active-sublink' : '' }}">Certificates &amp; Documents</a></li>
                    </ul>
                </div>

                <!-- 5. ID Cards -->
                @php $isIDActive = request()->routeIs('modules.id-cards*') || request()->routeIs('id-cards.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-id-cards')" id="menu-id-cards-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isIDActive ? 'bg-slate-800 text-teal-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-id-card w-4 text-center"></i>
                            <span>ID Cards</span>
                        </div>
                        <i id="menu-id-cards-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isIDActive ? 'rotate-90 text-teal-400' : '' }}"></i>
                    </a>
                    <ul id="menu-id-cards" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isIDActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('id-cards.students') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('id-cards.students') || (request()->routeIs('id-cards.*') && request('tab', 'students') == 'students')) ? 'active-sublink' : '' }}">Student ID Cards</a></li>
                        <li><a href="{{ route('id-cards.staff') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('id-cards.staff') || request('tab') == 'staff') ? 'active-sublink' : '' }}">Staff ID Cards</a></li>
                        <li><a href="{{ route('id-cards.designs') }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ (request()->routeIs('id-cards.designs') || request('tab') == 'designs') ? 'active-sublink' : '' }}">Card Designs</a></li>
                    </ul>
                </div>

                <!-- 6. Communicate -->
                @php $isCommActive = request()->routeIs('modules.communicate*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-communicate')" id="menu-communicate-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isCommActive ? 'bg-slate-800 text-sky-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-bullhorn w-4 text-center"></i>
                            <span>Communicate</span>
                        </div>
                        <i id="menu-communicate-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isCommActive ? 'rotate-90 text-sky-400' : '' }}"></i>
                    </a>
                    <ul id="menu-communicate" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isCommActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.communicate', ['tab' => 'notices']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCommActive && request('tab', 'notices') == 'notices' ? 'active-sublink' : '' }}">Notice Board</a></li>
                        <li><a href="{{ route('modules.communicate', ['tab' => 'events']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCommActive && request('tab') == 'events' ? 'active-sublink' : '' }}">Events &amp; Holidays</a></li>
                        <li><a href="{{ route('modules.communicate', ['tab' => 'broadcast_create']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCommActive && request('tab') == 'broadcast_create' ? 'active-sublink' : '' }}">Compose Broadcast</a></li>
                        <li><a href="{{ route('modules.communicate', ['tab' => 'broadcast_history']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCommActive && request('tab') == 'broadcast_history' ? 'active-sublink' : '' }}">Broadcast History</a></li>
                        <li><a href="{{ route('modules.communicate', ['tab' => 'gallery']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCommActive && request('tab') == 'gallery' ? 'active-sublink' : '' }}">Image Gallery</a></li>
                    </ul>
                </div>

                <!-- 7. Library -->
                @php $isLibraryActive = request()->routeIs('modules.library*') || request()->routeIs('library.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-library')" id="menu-library-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isLibraryActive ? 'bg-slate-800 text-indigo-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-book w-4 text-center"></i>
                            <span>Library</span>
                        </div>
                        <i id="menu-library-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isLibraryActive ? 'rotate-90 text-indigo-400' : '' }}"></i>
                    </a>
                    <ul id="menu-library" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isLibraryActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.library', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLibraryActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Library Dashboard</a></li>
                        <li><a href="{{ route('modules.library', ['tab' => 'issue-return']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLibraryActive && request('tab') == 'issue-return' ? 'active-sublink' : '' }}">Issue / Return Book</a></li>
                        <li><a href="{{ route('modules.library', ['tab' => 'books']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLibraryActive && request('tab') == 'books' ? 'active-sublink' : '' }}">Manage Books</a></li>
                        <li><a href="{{ route('modules.library', ['tab' => 'categories']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLibraryActive && request('tab') == 'categories' ? 'active-sublink' : '' }}">Book Categories</a></li>
                    </ul>
                </div>

                <!-- 8. Inventory -->
                @php $isInventoryActive = request()->routeIs('modules.inventory*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-inventory')" id="menu-inventory-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isInventoryActive ? 'bg-slate-800 text-emerald-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-boxes w-4 text-center"></i>
                            <span>Inventory</span>
                        </div>
                        <i id="menu-inventory-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isInventoryActive ? 'rotate-90 text-emerald-400' : '' }}"></i>
                    </a>
                    <ul id="menu-inventory" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isInventoryActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.inventory', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isInventoryActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Inventory Dashboard</a></li>
                        <li><a href="{{ route('modules.inventory', ['tab' => 'items']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isInventoryActive && request('tab') == 'items' ? 'active-sublink' : '' }}">Item List</a></li>
                        <li><a href="{{ route('modules.inventory', ['tab' => 'categories']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isInventoryActive && request('tab') == 'categories' ? 'active-sublink' : '' }}">Item Categories</a></li>
                        <li><a href="{{ route('modules.inventory', ['tab' => 'suppliers']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isInventoryActive && request('tab') == 'suppliers' ? 'active-sublink' : '' }}">Suppliers</a></li>
                        <li><a href="{{ route('modules.inventory', ['tab' => 'pos']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isInventoryActive && request('tab') == 'pos' ? 'active-sublink' : '' }}">Point of Sale</a></li>
                        <li><a href="{{ route('modules.inventory', ['tab' => 'purchase-orders']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isInventoryActive && request('tab') == 'purchase-orders' ? 'active-sublink' : '' }}">Purchase Orders</a></li>
                    </ul>
                </div>

                <!-- 9. Transport -->
                @php $isTransportActive = request()->routeIs('modules.transport*') || request()->routeIs('transport.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-transport')" id="menu-transport-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isTransportActive ? 'bg-slate-800 text-amber-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-bus w-4 text-center"></i>
                            <span>Transport</span>
                        </div>
                        <i id="menu-transport-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isTransportActive ? 'rotate-90 text-amber-400' : '' }}"></i>
                    </a>
                    <ul id="menu-transport" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isTransportActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.transport', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isTransportActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Transport Dashboard</a></li>
                        <li><a href="{{ route('modules.transport', ['tab' => 'vehicles']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isTransportActive && request('tab') == 'vehicles' ? 'active-sublink' : '' }}">Manage Vehicles</a></li>
                        <li><a href="{{ route('modules.transport', ['tab' => 'routes']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isTransportActive && request('tab') == 'routes' ? 'active-sublink' : '' }}">Manage Routes</a></li>
                        <li><a href="{{ route('modules.transport', ['tab' => 'tracking']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-emerald-400 font-bold hover:text-emerald-300 hover:bg-slate-800 {{ $isTransportActive && request('tab') == 'tracking' ? 'active-sublink' : '' }}">Live Vehicle Tracking</a></li>
                        <li><a href="{{ route('modules.transport', ['tab' => 'drivers']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isTransportActive && request('tab') == 'drivers' ? 'active-sublink' : '' }}">Drivers</a></li>
                    </ul>
                </div>

                <!-- 10. Hostel -->
                @php $isHostelActive = request()->routeIs('modules.hostel*') || request()->routeIs('hostel.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-hostel')" id="menu-hostel-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isHostelActive ? 'bg-slate-800 text-rose-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-hotel w-4 text-center"></i>
                            <span>Hostel</span>
                        </div>
                        <i id="menu-hostel-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isHostelActive ? 'rotate-90 text-rose-400' : '' }}"></i>
                    </a>
                    <ul id="menu-hostel" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isHostelActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.hostel', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isHostelActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Hostel Dashboard</a></li>
                        <li><a href="{{ route('modules.hostel', ['tab' => 'allocations']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isHostelActive && request('tab') == 'allocations' ? 'active-sublink' : '' }}">Student Allocation</a></li>
                        <li><a href="{{ route('modules.hostel', ['tab' => 'rooms']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isHostelActive && request('tab') == 'rooms' ? 'active-sublink' : '' }}">Manage Rooms</a></li>
                        <li><a href="{{ route('modules.hostel', ['tab' => 'hostels']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isHostelActive && request('tab') == 'hostels' ? 'active-sublink' : '' }}">Manage Hostels</a></li>
                    </ul>
                </div>

                <!-- 11. Help Center -->
                @php $isHelpActive = request()->routeIs('modules.help-center*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-help-center')" id="menu-help-center-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isHelpActive ? 'bg-slate-800 text-cyan-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-question w-4 text-center"></i>
                            <span>Help Center</span>
                        </div>
                        <i id="menu-help-center-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isHelpActive ? 'rotate-90 text-cyan-400' : '' }}"></i>
                    </a>
                    <ul id="menu-help-center" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isHelpActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.help-center', ['tab' => 'categories']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isHelpActive && request('tab', 'categories') == 'categories' ? 'active-sublink' : '' }}">Manage Categories</a></li>
                        <li><a href="{{ route('modules.help-center', ['tab' => 'articles']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isHelpActive && request('tab') == 'articles' ? 'active-sublink' : '' }}">Browse Articles</a></li>
                        <li><a href="{{ route('modules.help-center', ['tab' => 'chatbot']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-purple-400 font-bold hover:text-purple-300 hover:bg-slate-800 {{ $isHelpActive && request('tab') == 'chatbot' ? 'active-sublink' : '' }}">AI Chatbot</a></li>
                    </ul>
                </div>

                <!-- 12. Asset Management -->
                @php $isAssetActive = request()->routeIs('modules.assets*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-assets')" id="menu-assets-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isAssetActive ? 'bg-slate-800 text-orange-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-boxes-packing w-4 text-center"></i>
                            <span>Asset Management</span>
                        </div>
                        <i id="menu-assets-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isAssetActive ? 'rotate-90 text-orange-400' : '' }}"></i>
                    </a>
                    <ul id="menu-assets" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isAssetActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.assets', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAssetActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Asset Dashboard</a></li>
                        <li><a href="{{ route('modules.assets', ['tab' => 'register']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAssetActive && request('tab') == 'register' ? 'active-sublink' : '' }}">Asset Register</a></li>
                        <li><a href="{{ route('modules.assets', ['tab' => 'depreciation']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAssetActive && request('tab') == 'depreciation' ? 'active-sublink' : '' }}">Asset Depreciation</a></li>
                        <li><a href="{{ route('modules.assets', ['tab' => 'maintenance']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAssetActive && request('tab') == 'maintenance' ? 'active-sublink' : '' }}">Asset Maintenance</a></li>
                        <li><a href="{{ route('modules.assets', ['tab' => 'audits']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAssetActive && request('tab') == 'audits' ? 'active-sublink' : '' }}">Asset Audits</a></li>
                    </ul>
                </div>

                <!-- 13. Engagement -->
                @php $isEngageActive = request()->routeIs('modules.engagement*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-engagement')" id="menu-engagement-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isEngageActive ? 'bg-slate-800 text-pink-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-wand-magic-sparkles w-4 text-center"></i>
                            <span>Engagement</span>
                        </div>
                        <i id="menu-engagement-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isEngageActive ? 'rotate-90 text-pink-400' : '' }}"></i>
                    </a>
                    <ul id="menu-engagement" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isEngageActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.engagement', ['tab' => 'designs']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isEngageActive && request('tab', 'designs') == 'designs' ? 'active-sublink' : '' }}">Creatives</a></li>
                        <li><a href="{{ route('modules.engagement', ['tab' => 'birthdays']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isEngageActive && request('tab') == 'birthdays' ? 'active-sublink' : '' }}">Birthday Manager</a></li>
                        <li><a href="{{ route('modules.engagement', ['tab' => 'festivals']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isEngageActive && request('tab') == 'festivals' ? 'active-sublink' : '' }}">Festival Greetings</a></li>
                        <li><a href="{{ route('modules.engagement', ['tab' => 'settings']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isEngageActive && request('tab') == 'settings' ? 'active-sublink' : '' }}">Auto-send Settings</a></li>
                    </ul>
                </div>

                <!-- 14. CCTV -->
                @php $isCCTVActive = request()->routeIs('modules.cctv*') || request()->routeIs('cctv.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-cctv')" id="menu-cctv-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isCCTVActive ? 'bg-slate-800 text-red-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-video w-4 text-center"></i>
                            <span>CCTV</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        </div>
                        <i id="menu-cctv-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isCCTVActive ? 'rotate-90 text-red-400' : '' }}"></i>
                    </a>
                    <ul id="menu-cctv" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isCCTVActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.cctv', ['tab' => 'wall']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCCTVActive && request('tab', 'wall') == 'wall' ? 'active-sublink' : '' }}">Camera Wall</a></li>
                        <li><a href="{{ route('modules.cctv', ['tab' => 'cameras']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCCTVActive && request('tab') == 'cameras' ? 'active-sublink' : '' }}">CCTV Cameras</a></li>
                        <li><a href="{{ route('modules.cctv', ['tab' => 'logs']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCCTVActive && request('tab') == 'logs' ? 'active-sublink' : '' }}">CCTV Access Log</a></li>
                    </ul>
                </div>

                <!-- 15. Alumni [NEW] -->
                @php $isAlumniActive = request()->routeIs('modules.alumni*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-alumni')" id="menu-alumni-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isAlumniActive ? 'bg-slate-800 text-blue-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-user-graduate w-4 text-center"></i>
                            <span>Alumni</span>
                            <span class="text-[9px] bg-blue-500/20 text-blue-400 font-bold px-1.5 py-0.5 rounded">NEW</span>
                        </div>
                        <i id="menu-alumni-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isAlumniActive ? 'rotate-90 text-blue-400' : '' }}"></i>
                    </a>
                    <ul id="menu-alumni" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isAlumniActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.alumni', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAlumniActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Alumni Dashboard</a></li>
                        <li><a href="{{ route('modules.alumni', ['tab' => 'directory']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAlumniActive && request('tab') == 'directory' ? 'active-sublink' : '' }}">Alumni Directory</a></li>
                        <li><a href="{{ route('modules.alumni', ['tab' => 'events']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAlumniActive && request('tab') == 'events' ? 'active-sublink' : '' }}">Alumni Events</a></li>
                        <li><a href="{{ route('modules.alumni', ['tab' => 'checkin']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAlumniActive && request('tab') == 'checkin' ? 'active-sublink' : '' }}">Event Check-in</a></li>
                        <li><a href="{{ route('modules.alumni', ['tab' => 'guide']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isAlumniActive && request('tab') == 'guide' ? 'active-sublink' : '' }}">Alumni Guide</a></li>
                    </ul>
                </div>

                <!-- 16. Labs [NEW] -->
                @php $isLabsActive = request()->routeIs('modules.labs*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-labs')" id="menu-labs-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isLabsActive ? 'bg-slate-800 text-teal-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-flask w-4 text-center"></i>
                            <span>Labs</span>
                            <span class="text-[9px] bg-teal-500/20 text-teal-400 font-bold px-1.5 py-0.5 rounded">NEW</span>
                        </div>
                        <i id="menu-labs-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isLabsActive ? 'rotate-90 text-teal-400' : '' }}"></i>
                    </a>
                    <ul id="menu-labs" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isLabsActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.labs', ['tab' => 'labs']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLabsActive && request('tab', 'labs') == 'labs' ? 'active-sublink' : '' }}">Lab List</a></li>
                        <li><a href="{{ route('modules.labs', ['tab' => 'sessions']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLabsActive && request('tab') == 'sessions' ? 'active-sublink' : '' }}">Practical Register</a></li>
                        <li><a href="{{ route('modules.labs', ['tab' => 'incidents']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLabsActive && request('tab') == 'incidents' ? 'active-sublink' : '' }}">Lab Incidents</a></li>
                        <li><a href="{{ route('modules.labs', ['tab' => 'batches']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLabsActive && request('tab') == 'batches' ? 'active-sublink' : '' }}">Lab Batches</a></li>
                        <li><a href="{{ route('modules.labs', ['tab' => 'issues']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLabsActive && request('tab') == 'issues' ? 'active-sublink' : '' }}">Lab Issue Desk</a></li>
                        <li><a href="{{ route('modules.labs', ['tab' => 'reports']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLabsActive && request('tab') == 'reports' ? 'active-sublink' : '' }}">Lab Reports</a></li>
                        <li><a href="{{ route('modules.labs', ['tab' => 'guide']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isLabsActive && request('tab') == 'guide' ? 'active-sublink' : '' }}">Lab Guide</a></li>
                    </ul>
                </div>

                <!-- 17. Canteen [NEW] -->
                @php $isCanteenActive = request()->routeIs('modules.canteen*') || request()->routeIs('canteen.*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-canteen')" id="menu-canteen-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isCanteenActive ? 'bg-slate-800 text-orange-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-utensils w-4 text-center"></i>
                            <span>Canteen</span>
                            <span class="text-[9px] bg-orange-500/20 text-orange-400 font-bold px-1.5 py-0.5 rounded">NEW</span>
                        </div>
                        <i id="menu-canteen-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isCanteenActive ? 'rotate-90 text-orange-400' : '' }}"></i>
                    </a>
                    <ul id="menu-canteen" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isCanteenActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.canteen', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCanteenActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Canteen Dashboard</a></li>
                        <li><a href="{{ route('modules.canteen', ['tab' => 'pos']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-orange-400 font-bold hover:text-orange-300 hover:bg-slate-800 {{ $isCanteenActive && request('tab') == 'pos' ? 'active-sublink' : '' }}">POS / Counter Sales</a></li>
                        <li><a href="{{ route('modules.canteen', ['tab' => 'wallets']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCanteenActive && request('tab') == 'wallets' ? 'active-sublink' : '' }}">Campus Wallets</a></li>
                        <li><a href="{{ route('modules.canteen', ['tab' => 'transactions']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCanteenActive && request('tab') == 'transactions' ? 'active-sublink' : '' }}">Wallet Transactions</a></li>
                        <li><a href="{{ route('modules.canteen', ['tab' => 'products']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCanteenActive && request('tab') == 'products' ? 'active-sublink' : '' }}">Menu &amp; Products</a></li>
                        <li><a href="{{ route('modules.canteen', ['tab' => 'shifts']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCanteenActive && request('tab') == 'shifts' ? 'active-sublink' : '' }}">Shifts &amp; Day Sheet</a></li>
                        <li><a href="{{ route('modules.canteen', ['tab' => 'guide']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCanteenActive && request('tab') == 'guide' ? 'active-sublink' : '' }}">Canteen Guide</a></li>
                    </ul>
                </div>

                <!-- 18. Online Courses [NEW] -->
                @php $isCoursesActive = request()->routeIs('modules.courses*'); @endphp
                <div class="treeview-parent-item">
                    <a href="javascript:void(0)" onclick="toggleMenu(event, 'menu-courses')" id="menu-courses-parent" class="w-full flex items-center justify-between px-3 py-2 rounded-xl font-medium text-left transition hover:bg-slate-800 hover:text-white {{ $isCoursesActive ? 'bg-slate-800 text-indigo-400' : 'text-slate-400' }}">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-graduation-cap w-4 text-center"></i>
                            <span>Online Courses</span>
                            <span class="text-[9px] bg-indigo-500/20 text-indigo-400 font-bold px-1.5 py-0.5 rounded">NEW</span>
                        </div>
                        <i id="menu-courses-arrow" class="fa-solid fa-chevron-right text-[9px] text-slate-600 {{ $isCoursesActive ? 'rotate-90 text-indigo-400' : '' }}"></i>
                    </a>
                    <ul id="menu-courses" class="mt-1 space-y-0.5 pl-6 border-l border-slate-800 ml-5 {{ $isCoursesActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('modules.courses', ['tab' => 'dashboard']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab', 'dashboard') == 'dashboard' ? 'active-sublink' : '' }}">Courses Dashboard</a></li>
                        <li><a href="{{ route('modules.courses', ['tab' => 'courses']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab') == 'courses' ? 'active-sublink' : '' }}">All Courses</a></li>
                        <li><a href="{{ route('modules.courses', ['tab' => 'marking']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab') == 'marking' ? 'active-sublink' : '' }}">Course Marking Queue</a></li>
                        <li><a href="{{ route('modules.courses', ['tab' => 'learn']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab') == 'learn' ? 'active-sublink' : '' }}">My Learning</a></li>
                        <li><a href="{{ route('modules.courses', ['tab' => 'reports']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab') == 'reports' ? 'active-sublink' : '' }}">Course Reports</a></li>
                        <li><a href="{{ route('modules.courses', ['tab' => 'certificates']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab') == 'certificates' ? 'active-sublink' : '' }}">Course Certificates</a></li>
                        <li><a href="{{ route('modules.courses', ['tab' => 'orders']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab') == 'orders' ? 'active-sublink' : '' }}">Course Orders</a></li>
                        <li><a href="{{ route('modules.courses', ['tab' => 'guide']) }}" class="treeview-sublink block py-1.5 px-2 text-[11px] rounded text-slate-400 hover:text-white hover:bg-slate-800 {{ $isCoursesActive && request('tab') == 'guide' ? 'active-sublink' : '' }}">Courses Guide</a></li>
                    </ul>
                </div>

                <!-- 19. Reports & Analytics [NEW] -->
                @php $isReportsActive = request()->routeIs('modules.reports*'); @endphp
                <a href="{{ route('modules.reports') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl font-medium transition {{ $isReportsActive ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                    <span>Reports &amp; Analytics</span>
                    <span class="text-[9px] bg-emerald-500/20 text-emerald-400 font-bold px-1.5 py-0.5 rounded ml-auto">NEW</span>
                </a>
            </nav>

            <!-- Bottom System Status -->
            <div class="mt-auto p-3 m-2 rounded-xl bg-slate-950/70 border border-slate-800 text-xs">
                <div class="flex items-center justify-between text-slate-400 mb-1">
                    <span class="text-[10px] uppercase font-semibold">Treeview Accordion</span>
                    <span class="text-emerald-400 font-bold">21 Modules Ready</span>
                </div>
                <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-500 h-full rounded-full" style="width: 100%"></div>
                </div>
                <div class="mt-2 text-[10px] text-slate-400 flex items-center justify-between">
                    <span>Multi-School ERP v3.8</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-slate-100 custom-scroll">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-3.5 rounded-r shadow-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2.5 text-emerald-800 text-sm font-medium">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- =============================================================== -->
    <!-- 🔥 BULLETPROOF TREEVIEW JS ENGINE (Zero Page Reload on Parent)  -->
    <!-- =============================================================== -->
    <script>
    // 1. Accordion Toggle Function
    function toggleMenu(event, menuId) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(menuId + '-arrow');
        const parentBtn = document.getElementById(menuId + '-parent');
        if (!menu) return;

        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            if (arrow) {
                arrow.classList.add('rotate-90');
                arrow.classList.add('text-indigo-400');
            }
            if (parentBtn) {
                parentBtn.classList.add('bg-slate-800/80');
            }

            // Save open state to localStorage
            try {
                let openMenus = JSON.parse(localStorage.getItem('erp_open_menus') || '[]');
                if (!openMenus.includes(menuId)) {
                    openMenus.push(menuId);
                    localStorage.setItem('erp_open_menus', JSON.stringify(openMenus));
                }
            } catch(e) {}
        } else {
            menu.classList.add('hidden');
            if (arrow) {
                arrow.classList.remove('rotate-90');
                arrow.classList.remove('text-indigo-400');
            }
            if (parentBtn) {
                parentBtn.classList.remove('bg-slate-800/80');
            }

            // Remove open state from localStorage
            try {
                let openMenus = JSON.parse(localStorage.getItem('erp_open_menus') || '[]');
                openMenus = openMenus.filter(id => id !== menuId);
                localStorage.setItem('erp_open_menus', JSON.stringify(openMenus));
            } catch(e) {}
        }
    }

    // 2. On DOM Load: Restore user opened menus & ensure current active parent is expanded
    document.addEventListener('DOMContentLoaded', function() {
        // Restore remembered menus
        try {
            let openMenus = JSON.parse(localStorage.getItem('erp_open_menus') || '[]');
            openMenus.forEach(menuId => {
                const menu = document.getElementById(menuId);
                const arrow = document.getElementById(menuId + '-arrow');
                const parentBtn = document.getElementById(menuId + '-parent');
                if (menu) {
                    menu.classList.remove('hidden');
                    if (arrow) {
                        arrow.classList.add('rotate-90');
                        arrow.classList.add('text-indigo-400');
                    }
                    if (parentBtn) {
                        parentBtn.classList.add('bg-slate-800/80');
                    }
                }
            });
        } catch(e) {}

        // Ensure active sublink's parent is automatically opened
        document.querySelectorAll('.treeview-sublink.active-sublink').forEach(link => {
            const parentUl = link.closest('ul[id^="menu-"]');
            if (parentUl) {
                parentUl.classList.remove('hidden');
                const arrow = document.getElementById(parentUl.id + '-arrow');
                if (arrow) {
                    arrow.classList.add('rotate-90');
                    arrow.classList.add('text-indigo-400');
                }
                const parentBtn = document.getElementById(parentUl.id + '-parent');
                if (parentBtn) {
                    parentBtn.classList.add('bg-slate-800/80');
                }
            }
        });
    });

    // 3. Instant Live Sidebar Search Filter
    function filterSidebarMenu(query) {
        const q = (query || '').toLowerCase().trim();
        const items = document.querySelectorAll('.treeview-parent-item');

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            const menuUl = item.querySelector('ul[id^="menu-"]');
            const arrow = item.querySelector('[id$="-arrow"]');

            if (!q) {
                item.style.display = '';
                return;
            }

            if (text.includes(q)) {
                item.style.display = '';
                if (menuUl) {
                    menuUl.classList.remove('hidden');
                    if (arrow) {
                        arrow.classList.add('rotate-90');
                        arrow.classList.add('text-indigo-400');
                    }
                }
            } else {
                item.style.display = 'none';
            }
        });
    }

    // 4. Sidebar Toggle on Mobile / Mini
    function toggleSidebarCollapse() {
        const sidebar = document.getElementById('main-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }

    // 5. Global Modal Helpers (Accessible across all ERP modules)
    window.openModal = function(modalId) {
        const el = document.getElementById(modalId);
        if (!el) {
            console.warn('Modal not found:', modalId);
            return;
        }
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    window.closeModal = function(modalId) {
        const el = document.getElementById(modalId);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id$="Modal"]:not(.hidden)').forEach(function(m) {
                window.closeModal(m.id);
            });
        }
    });
    </script>

    <!-- Master Application JS Bundle -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
