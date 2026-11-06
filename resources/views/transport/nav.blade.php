<style>
    .ts-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }
    .ts-stat-card {
        display: flex; align-items: center;
        padding: 20px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ts-stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); border-color: #cbd5e1; }
    .ts-stat-icon {
        width: 48px; height: 48px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem; margin-right: 15px; flex-shrink: 0;
    }
    .ts-bg-yellow { background-color: #fef3c7; color: #d97706; }
    .ts-bg-blue { background-color: #e0f2fe; color: #0284c7; }
    .ts-bg-emerald { background-color: #d1fae5; color: #059669; }
    .ts-bg-indigo { background-color: #e0e7ff; color: #4f46e5; }
    .ts-bg-slate { background-color: #f1f5f9; color: #64748b; }
    .ts-stat-value { font-size: 1.7rem; font-weight: 800; color: #0f172a; line-height: 1.1; margin-top: 4px; }
    .ts-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .ts-section-title {
        font-size: 1.05rem; font-weight: 700; color: #0f172a;
        padding: 16px 20px; border-bottom: 1px solid #e2e8f0;
        display: flex; justify-content: space-between; align-items: center;
    }
    .ts-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .ts-table th { background-color: #0f172a; color: #fff; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; padding: 11px 16px; border: none; letter-spacing: 0.5px; }
    .ts-table td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.88rem; }
    .ts-table tr:hover td { background-color: #f8fafc; }
    .badge-status { padding: 3px 9px; border-radius: 9999px; font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-in-service { background-color: #dcfce7; color: #166534; }
    .badge-on-trip { background-color: #e0f2fe; color: #0369a1; }
    .badge-maintenance { background-color: #fee2e2; color: #991b1b; }
    .ts-tabs-wrap {
        display: flex;
        align-items: stretch;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 22px;
        overflow-x: auto;
    }
    .ts-tabs {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        gap: 0 16px;
        min-width: 100%;
    }
    .ts-tab {
        padding: 10px 6px;
        color: #64748b;
        font-weight: 500;
        text-decoration: none;
        position: relative;
        font-size: 0.88rem;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }
    .ts-tab:hover { color: #0f172a; }
    .ts-tab.active {
        color: #d97706;
        font-weight: 600;
        border-bottom-color: #d97706;
    }
</style>

<div class="mb-4">
    <!-- Header title area -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-3 pt-2">
        <div class="ts-title-area">
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-bus text-amber-500"></i>
                Transport Management
            </h3>
            <p class="text-sm text-slate-500 mt-0.5">Manage school buses, routes, driver rosters, student boarding, and live GPS vehicle tracking.</p>
        </div>
        <div class="mt-3 md:mt-0 flex items-center space-x-2">
            <a href="{{ route('school.transport.tracking') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-satellite-dish"></i> Live Tracking
            </a>
            <a href="{{ route('school.transport.routes.create') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-amber-600 text-white hover:bg-amber-700 shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-route"></i> Add Route
            </a>
            <a href="{{ route('school.transport.vehicles.create') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-van-shuttle"></i> Add Vehicle
            </a>
        </div>
    </div>

    <!-- Sub-navigation Tabs -->
    <div class="ts-tabs-wrap no-scrollbar">
        <div class="ts-tabs">
            <a href="{{ route('school.transport.dashboard') }}" class="ts-tab {{ request()->routeIs('school.transport.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('school.transport.guide') }}" class="ts-tab {{ request()->routeIs('school.transport.guide') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i> Guide
            </a>
            <a href="{{ route('school.transport.vehicles') }}" class="ts-tab {{ request()->routeIs('school.transport.vehicles*') && !request()->routeIs('school.transport.vehicles.create') ? 'active' : '' }}">
                <i class="fas fa-bus"></i> Manage Vehicles
            </a>
            <a href="{{ route('school.transport.routes') }}" class="ts-tab {{ request()->routeIs('school.transport.routes*') && !request()->routeIs('school.transport.routes.create') ? 'active' : '' }}">
                <i class="fas fa-route"></i> Manage Routes
            </a>
            <a href="{{ route('school.transport.tracking') }}" class="ts-tab {{ request()->routeIs('school.transport.tracking') ? 'active' : '' }}">
                <i class="fas fa-satellite-dish text-emerald-500"></i> Live Tracking
            </a>
            <a href="{{ route('school.transport.live') }}" class="ts-tab {{ request()->routeIs('school.transport.live') ? 'active' : '' }}">
                <i class="fas fa-tower-broadcast"></i> Live Operations
            </a>
            <a href="{{ route('school.transport.drivers') }}" class="ts-tab {{ request()->routeIs('school.transport.drivers*') ? 'active' : '' }}">
                <i class="fas fa-id-card"></i> Drivers
            </a>
            <a href="{{ route('school.transport.reports.boarding') }}" class="ts-tab {{ request()->routeIs('school.transport.reports.boarding') ? 'active' : '' }}">
                <i class="fas fa-clipboard-user"></i> Boarding Register
            </a>
            <a href="{{ route('school.transport.fee-settings') }}" class="ts-tab {{ request()->routeIs('school.transport.fee-settings') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i> Fee Settings
            </a>
        </div>
    </div>
</div>

