<div class="osm-tabs-wrap">
    <div class="osm-tabs">
        <a class="osm-tab {{ request()->routeIs('osm.dashboard') ? 'active' : '' }}" href="{{ route('osm.dashboard') }}">
            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
        </a>
        <a class="osm-tab {{ request()->routeIs('osm.sessions*') ? 'active' : '' }}" href="{{ route('osm.sessions') }}">
            <i class="fas fa-layer-group mr-1"></i> Sessions
        </a>
        <a class="osm-tab {{ request()->routeIs('osm.evaluate*') ? 'active' : '' }}" href="{{ route('osm.evaluate') }}">
            <i class="fas fa-pen-nib mr-1"></i> Evaluate
            @php
                $pendingCount = \App\Models\OsmPaper::where('status', 'Pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge badge-warning badge-pill ml-1" style="font-size: 0.72rem;">{{ $pendingCount }}</span>
            @endif
        </a>
        <a class="osm-tab {{ request()->routeIs('osm.moderation*') ? 'active' : '' }}" href="{{ route('osm.moderation') }}">
            <i class="fas fa-balance-scale mr-1"></i> Moderation
        </a>
        <a class="osm-tab {{ request()->routeIs('osm.reports*') ? 'active' : '' }}" href="{{ route('osm.reports') }}">
            <i class="fas fa-chart-bar mr-1"></i> Reports
        </a>
        <a class="osm-tab {{ request()->routeIs('osm.guide') ? 'active' : '' }}" href="{{ route('osm.guide') }}">
            <i class="fas fa-question-circle mr-1"></i> Guide
        </a>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    .osm-title-area, .osm-tabs, .osm-tab, .btn-osm-primary { font-family: 'Inter', sans-serif; }
    .osm-title-area { margin-bottom: 20px; }
    .osm-title-area h3 { font-weight: 700; color: #0f172a; font-size: 1.5rem; }
    .osm-title-area p { color: #64748b; font-size: 0.9rem; margin-bottom: 0; }

    .osm-tabs-wrap { display: flex; align-items: stretch; border-bottom: 1px solid #e2e8f0; margin-bottom: 25px; max-width: 100%; }
    .osm-tabs {
        display: flex; flex-wrap: nowrap; align-items: stretch; gap: 0 18px;
        flex: 1 1 auto; min-width: 0;
        overflow-x: auto; -webkit-overflow-scrolling: touch;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .osm-tabs::-webkit-scrollbar { display: none; }
    .osm-tab {
        padding: 10px 5px; color: #64748b; font-weight: 500; text-decoration: none;
        position: relative; font-size: 0.9rem; white-space: nowrap; display: flex; align-items: center;
        flex: 0 0 auto;
    }
    @media (max-width: 575px) {
        .osm-tabs { gap: 0 14px; }
        .osm-tab { font-size: 0.85rem; }
        .osm-title-area h3 { font-size: 1.25rem; }
    }
    .osm-tab:hover { color: #0f172a; text-decoration: none; }
    .osm-tab.active { color: #0f172a; font-weight: 600; }
    .osm-tab.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background-color: #0284c7; }

    .btn-osm-primary { background-color: #0284c7; border-color: #0284c7; color: #fff; font-weight: 600; border-radius: 6px; }
    .btn-osm-primary:hover { background-color: #0369a1; border-color: #0369a1; color: #fff; }
</style>

