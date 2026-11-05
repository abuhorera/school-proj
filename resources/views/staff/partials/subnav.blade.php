<div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-2 flex flex-wrap items-center justify-between gap-2">
    <div class="flex items-center space-x-1 flex-wrap">
        <a href="{{ route('staff.index') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.index') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-users text-[11px]"></i>
            <span>Staff Directory</span>
        </a>
        <a href="{{ route('staff.attendance') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.attendance*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-calendar-check text-[11px]"></i>
            <span>Attendance</span>
        </a>
        <a href="{{ route('staff.payroll') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.payroll*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-money-check-dollar text-[11px]"></i>
            <span>Payroll</span>
        </a>
        <a href="{{ route('staff.salary') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.salary') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-wallet text-[11px]"></i>
            <span>Set Salary</span>
        </a>
        <a href="{{ route('staff.salary-templates') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.salary-templates*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-file-invoice text-[11px]"></i>
            <span>Salary Templates</span>
        </a>
        <a href="{{ route('staff.approve-leave') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.approve-leave*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-envelope-open-text text-[11px]"></i>
            <span>Approve Leave</span>
        </a>
        <a href="{{ route('staff.leave-types') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.leave-types*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-calendar-xmark text-[11px]"></i>
            <span>Leave Types</span>
        </a>
        <a href="{{ route('staff.departments') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.departments*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-building text-[11px]"></i>
            <span>Departments</span>
        </a>
        <a href="{{ route('staff.designations') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.designations*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-id-badge text-[11px]"></i>
            <span>Designations</span>
        </a>
        <a href="{{ route('staff.loans') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.loans*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-hand-holding-dollar text-[11px]"></i>
            <span>Loans</span>
        </a>
        <a href="{{ route('staff.id-card') }}" 
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('staff.id-card*') ? 'bg-pink-600 text-white shadow-xs' : 'text-slate-600 hover:text-pink-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-address-card text-[11px]"></i>
            <span>ID Cards</span>
        </a>
    </div>
    <div>
        <a href="{{ route('staff.create') }}" class="px-3.5 py-1.5 bg-pink-600 hover:bg-pink-700 text-white rounded-lg text-xs font-bold transition flex items-center space-x-1 shadow-2xs">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Add Staff</span>
        </a>
    </div>
</div>

