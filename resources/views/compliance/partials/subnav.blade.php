<div class="card mb-3 shadow-none border-0 bg-transparent">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 border-bottom">
        <ul class="nav nav-pills gap-1 overflow-auto" style="white-space: nowrap; max-width: 100%;">
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.overview') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.overview') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-shield-alt mr-1 text-rose-500"></i> Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.profile*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.profile') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-school mr-1 text-primary"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.packs*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.packs') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-boxes mr-1 text-amber-500"></i> Packs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.documents*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.documents') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-archive mr-1 text-indigo-500"></i> Document Vault
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.checklist*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.checklist') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-clipboard-check mr-1 text-emerald-500"></i> Checklist
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.records*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.records') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-table mr-1 text-teal-500"></i> Data Records
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.validator*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.validator') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-exclamation-triangle mr-1 text-danger"></i> Validator
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.reports*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.reports') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-file-export mr-1 text-cyan-600"></i> Govt Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.inspections*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.inspections') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-search-plus mr-1 text-purple-500"></i> Inspections
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1.5 px-3 {{ request()->routeIs('compliance.calendar*') ? 'active bg-dark text-white font-weight-bold shadow-sm' : 'text-secondary hover-bg-light' }}" 
                   href="{{ route('compliance.calendar') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-calendar-alt mr-1 text-orange-500"></i> Calendar
                </a>
            </li>
        </ul>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('compliance.validator.export') }}" class="btn btn-outline-danger btn-sm px-2.5 py-1" style="font-size: 12px; border-radius: 6px;">
                <i class="fas fa-file-csv mr-1"></i> Export Worklist
            </a>
            <a href="{{ route('compliance.documents') }}#addModal" class="btn btn-primary btn-sm px-3 py-1 font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                <i class="fas fa-upload mr-1"></i> Upload Document
            </a>
        </div>
    </div>
</div>

