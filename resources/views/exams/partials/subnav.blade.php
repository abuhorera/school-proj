<!-- Offline Examinations Top Sub-Navigation (Matching Demo MultiSchool ERP exam-tabs) -->
<div class="bg-white border-b border-slate-200 -mt-6 -mx-6 px-6 pt-4 mb-6 sticky top-0 z-10 shadow-xs">
    <div class="flex items-center justify-between pb-3">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Offline Examinations & Assessment Hub</h2>
                <p class="text-xs text-slate-500">Summative & Formative Evaluations • Exam Scheduling, Marks Entry, Cocurricular Grading, Marksheets & Hall Tickets</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('exams.marks.entry') }}" class="px-3 py-1.5 text-xs font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-pen-to-square text-purple-600"></i>
                <span>Enter Marks</span>
            </a>
            <a href="{{ route('exams.create') }}" class="px-3 py-1.5 text-xs font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add Exam</span>
            </a>
        </div>
    </div>

    <!-- Scrollable Tab Bar (Matching Demo) -->
    <div class="flex items-center space-x-1 overflow-x-auto custom-scroll -mb-px text-xs font-medium border-t border-slate-100 pt-1">
        <a href="{{ route('exams.dashboard') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.dashboard') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('exams.index') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ (request()->routeIs('exams.index') || request()->routeIs('exams.create')) ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Manage Exams</span>
        </a>
        <a href="{{ route('exams.types') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.types') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-list-ol"></i>
            <span>Exam Types</span>
        </a>
        <a href="{{ route('exams.schedule') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.schedule') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-clock"></i>
            <span>Schedule & Marks Setup</span>
        </a>
        <a href="{{ route('exams.marks.entry') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ (request()->routeIs('exams.marks.entry') || request()->routeIs('exams.enter-marks')) ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-pen-nib text-purple-600"></i>
            <span>Enter Marks</span>
            <span class="bg-purple-100 text-purple-800 text-[9px] px-1.5 py-0.2 rounded-full font-bold ml-1">ENTRY</span>
        </a>
        <a href="{{ route('exams.cocurricular') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.cocurricular') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-palette"></i>
            <span>Cocurricular Grades</span>
        </a>
        <a href="{{ route('exams.grades') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.grades') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Manage Grades</span>
        </a>
        <a href="{{ route('exams.marksheet.index') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.marksheet.index') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-file-invoice"></i>
            <span>Generate Marksheet</span>
        </a>
        <a href="{{ route('exams.marksheet.upload') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.marksheet.upload') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Upload Marksheet</span>
        </a>
        <a href="{{ route('exams.marksheet.upload-list') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.marksheet.upload-list') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-folder-open"></i>
            <span>Manage Uploads</span>
        </a>
        <a href="{{ route('exams.marksheet.remarks') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.marksheet.remarks') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-comment-dots"></i>
            <span>Teacher Remarks</span>
        </a>
        <a href="{{ route('exams.admit-cards') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('exams.admit-cards*') ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-id-card text-amber-500"></i>
            <span>Admit Cards</span>
        </a>
    </div>
</div>

