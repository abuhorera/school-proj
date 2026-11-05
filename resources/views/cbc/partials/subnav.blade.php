<!-- CBC Academics Top Sub-Navigation (Matching Demo MultiSchool ERP cbc-tabs) -->
<div class="bg-white border-b border-slate-200 -mt-6 -mx-6 px-6 pt-4 mb-6 sticky top-0 z-10 shadow-xs">
    <div class="flex items-center justify-between pb-3">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold text-base">
                <i class="fa-solid fa-shapes"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Competency-Based Curriculum (CBC)</h2>
                <p class="text-xs text-slate-500">NEP 2020 Framework • Strands, Learning Outcomes, 4-Tier Rubric Evaluations & Senior Pathways</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('cbc.assessments') }}" class="px-3 py-1.5 text-xs font-semibold text-violet-700 bg-violet-50 hover:bg-violet-100 rounded-lg border border-violet-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-clipboard-check text-violet-600"></i>
                <span>CBC Assessments</span>
            </a>
            <a href="{{ route('cbc.strands') }}" class="px-3 py-1.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add Strand</span>
            </a>
        </div>
    </div>

    <!-- Scrollable Tab Bar (Matching Demo) -->
    <div class="flex items-center space-x-1 overflow-x-auto custom-scroll -mb-px text-xs font-medium border-t border-slate-100 pt-1">
        <a href="{{ route('cbc.dashboard') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ (request()->routeIs('cbc.dashboard') || request()->routeIs('cbc.index')) ? 'border-violet-600 text-violet-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>CBC Dashboard</span>
        </a>
        <a href="{{ route('cbc.strands') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('cbc.strands') ? 'border-violet-600 text-violet-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Strands & Outcomes</span>
        </a>
        <a href="{{ route('cbc.assessments') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('cbc.assessments') ? 'border-violet-600 text-violet-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-check-double"></i>
            <span>CBC Assessments</span>
            <span class="bg-violet-100 text-violet-800 text-[9px] px-1.5 py-0.2 rounded-full font-bold ml-1">RUBRICS</span>
        </a>
        <a href="{{ route('cbc.competencies') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('cbc.competencies') ? 'border-violet-600 text-violet-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-lightbulb"></i>
            <span>Core Competencies</span>
        </a>
        <a href="{{ route('cbc.pathways') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('cbc.pathways') ? 'border-violet-600 text-violet-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-route"></i>
            <span>Pathways & Tracks</span>
        </a>
        <a href="{{ route('cbc.reports') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('cbc.reports*') ? 'border-violet-600 text-violet-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-file-waveform"></i>
            <span>CBC Reports</span>
        </a>
    </div>
</div>

