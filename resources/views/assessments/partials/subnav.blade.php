<div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
    <div class="card-body p-2">
        <ul class="nav nav-pills assessment-subnav d-flex flex-wrap align-items-center" style="gap: 6px;">
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('assessment.dashboard*') || request()->routeIs('assessments.index') ? 'active bg-emerald-600 text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('assessment.dashboard') }}" style="border-radius: 8px; font-size: 13px; {{ (request()->routeIs('assessment.dashboard*') || request()->routeIs('assessments.index')) ? 'background-color: #059669 !important;' : '' }}">
                    <i class="fas fa-th-large mr-1.5 {{ (request()->routeIs('assessment.dashboard*') || request()->routeIs('assessments.index')) ? 'text-white' : 'text-emerald-600' }}"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('assessment.assessments*') && !request()->routeIs('assessment.assessments.create') ? 'active bg-emerald-600 text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('assessment.assessments') }}" style="border-radius: 8px; font-size: 13px; {{ (request()->routeIs('assessment.assessments*') && !request()->routeIs('assessment.assessments.create')) ? 'background-color: #059669 !important;' : '' }}">
                    <i class="fas fa-clipboard-check mr-1.5 text-emerald-600 {{ (request()->routeIs('assessment.assessments*') && !request()->routeIs('assessment.assessments.create')) ? 'text-white' : '' }}"></i> Assessments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('assessment.reports') ? 'active bg-info text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('assessment.reports') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-chart-line mr-1.5 text-info {{ request()->routeIs('assessment.reports') ? 'text-white' : '' }}"></i> Analytics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('assessment.reports.ranking') ? 'active bg-warning text-dark' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('assessment.reports.ranking') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-trophy mr-1.5 text-warning {{ request()->routeIs('assessment.reports.ranking') ? 'text-dark' : '' }}"></i> Rank List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('assessment.reports.student') ? 'active bg-primary text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('assessment.reports.student') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-user-graduate mr-1.5 text-primary {{ request()->routeIs('assessment.reports.student') ? 'text-white' : '' }}"></i> Student Report
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('assessment.guide') ? 'active bg-secondary text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('assessment.guide') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-book-open mr-1.5 text-secondary {{ request()->routeIs('assessment.guide') ? 'text-white' : '' }}"></i> Guide
                </a>
            </li>
        </ul>
    </div>
</div>

