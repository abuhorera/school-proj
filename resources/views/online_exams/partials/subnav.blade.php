<div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-2 flex flex-wrap items-center justify-between gap-2">
    <div class="flex items-center space-x-1 flex-wrap">
        <a href="{{ route('online-exams.dashboard') }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('online-exams.dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-line text-[11px]"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('online-exams.manage') }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('online-exams.manage*') || request()->routeIs('online-exams.builder*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-laptop-code text-[11px]"></i>
            <span>Manage Online Exams</span>
        </a>
        <a href="{{ route('online-exams.question-bank') }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('online-exams.question-bank*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-database text-[11px]"></i>
            <span>Question Bank</span>
        </a>
        <a href="{{ route('online-exams.question-topics') }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ request()->routeIs('online-exams.question-topics*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-book-bookmark text-[11px]"></i>
            <span>Chapters & Topics</span>
        </a>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('online-exams.question-bank.create') }}" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold transition flex items-center space-x-1 shadow-2xs">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Add Question</span>
        </a>
        <a href="{{ route('online-exams.manage') }}?new=1" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center space-x-1 shadow-2xs">
            <i class="fa-solid fa-file-circle-plus text-[10px]"></i>
            <span>New Paper</span>
        </a>
    </div>
</div>

