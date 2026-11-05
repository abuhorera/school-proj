<!-- Front Office Module Top Sub-Navigation (Matching Demo MultiSchool ERP fo-tabs) -->
<div class="bg-white border-b border-slate-200 -mt-6 -mx-6 px-6 pt-4 mb-6 sticky top-0 z-10 shadow-xs">
    <div class="flex items-center justify-between pb-3">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold text-base">
                <i class="fa-solid fa-desktop"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Front Office Management</h2>
                <p class="text-xs text-slate-500">Reception Desk • Visitor Log, Admission Enquiries, Gate Passes, Complaints & Couriers</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('front-office.gate-terminal') }}" class="px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-barcode text-emerald-600"></i>
                <span>Gate Terminal</span>
            </a>
            <a href="{{ route('front-office.admission-enquiries.create') }}" class="px-3 py-1.5 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add New Enquiry</span>
            </a>
        </div>
    </div>

    <!-- Scrollable Tab Bar -->
    <div class="flex items-center space-x-1 overflow-x-auto custom-scroll -mb-px text-xs font-medium border-t border-slate-100 pt-1">
        <a href="{{ route('front-office.dashboard') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ (request()->routeIs('front-office.dashboard') || request()->routeIs('front-office.index')) ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('front-office.admission-enquiries') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('front-office.admission-enquiries*') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-regular fa-clipboard"></i>
            <span>Admission Enquiries</span>
        </a>
        <a href="{{ route('front-office.visitor-logs') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('front-office.visitor-logs*') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-address-book"></i>
            <span>Visitor Book</span>
        </a>
        <a href="{{ route('front-office.gate-passes') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('front-office.gate-passes*') && !request()->routeIs('front-office.gate-terminal*') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-id-badge"></i>
            <span>Gate Passes</span>
        </a>
        <a href="{{ route('front-office.gate-terminal') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('front-office.gate-terminal*') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-shield-halved text-emerald-600"></i>
            <span>Gate Terminal</span>
            <span class="bg-emerald-100 text-emerald-800 text-[9px] px-1.5 py-0.2 rounded-full font-bold ml-1">SCAN</span>
        </a>
        <a href="{{ route('front-office.campus-workers') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('front-office.campus-workers*') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-hard-hat"></i>
            <span>Campus Workers</span>
        </a>
        <a href="{{ route('front-office.complaints') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('front-office.complaints*') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-regular fa-comment-dots"></i>
            <span>Complaints</span>
        </a>
        <a href="{{ route('front-office.postal-records') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('front-office.postal-records*') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-envelope-open-text"></i>
            <span>Postal Records</span>
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

