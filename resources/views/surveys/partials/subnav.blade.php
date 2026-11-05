<div class="card mb-3 shadow-none border-0 bg-transparent">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 border-bottom">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('survey.dashboard') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('survey.dashboard') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-chart-pie mr-1.5 text-amber-500"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ (request()->routeIs('survey.index') || request()->routeIs('survey.show') || request()->routeIs('survey.edit')) ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('survey.index') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-list-alt mr-1.5 text-blue-500"></i> All Surveys
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('survey.mine') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('survey.mine') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-inbox mr-1.5 text-purple-500"></i> My Surveys
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('survey.templates') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('survey.templates') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-layer-group mr-1.5 text-teal-500"></i> Templates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('feedback.*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('feedback.index') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-tasks mr-1.5 text-rose-500"></i> Feedback Triage
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('survey.guide') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('survey.guide') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-book-open mr-1.5 text-emerald-500"></i> Guide
                </a>
            </li>
        </ul>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('survey.create', ['kind' => 'poll']) }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1" style="font-size: 12px; border-radius: 6px;">
                <i class="fas fa-poll text-amber-500 mr-1"></i> Quick poll
            </a>
            <a href="{{ route('survey.create') }}" class="btn btn-primary btn-sm px-3 py-1 font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                <i class="fas fa-plus mr-1"></i> New survey
            </a>
        </div>
    </div>
</div>

