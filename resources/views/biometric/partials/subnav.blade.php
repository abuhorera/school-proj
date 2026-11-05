<div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
    <div class="card-body p-2">
        <ul class="nav nav-pills biometric-subnav d-flex flex-wrap align-items-center" style="gap: 6px;">
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('biometric.devices*') && !request()->routeIs('biometric.devices.create') ? 'active bg-primary text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('biometric.devices') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-server mr-1.5 text-primary {{ request()->routeIs('biometric.devices*') && !request()->routeIs('biometric.devices.create') ? 'text-white' : '' }}"></i> All Devices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('biometric.logs*') ? 'active bg-info text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('biometric.logs') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-list-alt mr-1.5 text-info {{ request()->routeIs('biometric.logs*') ? 'text-white' : '' }}"></i> Attendance Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('biometric.monitoring-logs*') ? 'active bg-purple text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('biometric.monitoring-logs') }}" style="border-radius: 8px; font-size: 13px; {{ request()->routeIs('biometric.monitoring-logs*') ? 'background-color: #6f42c1 !important;' : '' }}">
                    <i class="fas fa-video mr-1.5 text-purple {{ request()->routeIs('biometric.monitoring-logs*') ? 'text-white' : '' }}"></i> Face Monitoring
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('biometric.system-logs*') ? 'active bg-secondary text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('biometric.system-logs') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-terminal mr-1.5 text-secondary {{ request()->routeIs('biometric.system-logs*') ? 'text-white' : '' }}"></i> Agent Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('biometric.playground*') ? 'active bg-warning text-dark' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('biometric.playground') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-code mr-1.5 text-warning {{ request()->routeIs('biometric.playground*') ? 'text-dark' : '' }}"></i> API Playground
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3 py-2 font-weight-bold {{ request()->routeIs('biometric.readiness*') ? 'active bg-success text-white' : 'text-dark hover-bg-light' }}" 
                   href="{{ route('biometric.readiness') }}" style="border-radius: 8px; font-size: 13px;">
                    <i class="fas fa-id-badge mr-1.5 text-success {{ request()->routeIs('biometric.readiness*') ? 'text-white' : '' }}"></i> Recognition Readiness
                </a>
            </li>
        </ul>
    </div>
</div>

