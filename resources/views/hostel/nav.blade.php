<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap');

    :root {
        --hst-primary: #0b1a3d;
        --hst-primary-light: #dbe1ff;
        --hst-accent: #ff8c00;
        --hst-accent-gradient: linear-gradient(135deg, #feb64e 0%, #ff8c00 100%);
        --hst-success: #10b981;
        --hst-success-light: #dcfce7;
        --hst-danger: #ef4444;
        --hst-danger-light: #fee2e2;
        --hst-warning: #f59e0b;
        --hst-warning-light: #fef3c7;
        --hst-text-dark: #0f172a;
        --hst-text-muted: #64748b;
        --hst-border: #e2e8f0;
        --hst-bg-card: #ffffff;
        --hst-bg-hover: #f8fafc;
    }

    .hst-title-area, .hst-tabs, .hst-tab, .btn-hst-primary, .btn-hst-outline {
        font-family: 'Inter', sans-serif;
    }
    
    .hst-title-area {
        margin-bottom: 20px;
    }
    
    .hst-title-area h3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        color: var(--hst-primary);
        font-size: 1.5rem;
    }
    
    .hst-title-area p {
        color: var(--hst-text-muted);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .hst-tabs-wrap {
        border-bottom: 1px solid var(--hst-border);
        margin-bottom: 25px;
        overflow-x: auto;
    }

    .hst-tabs {
        display: flex;
        gap: 20px;
        flex-wrap: nowrap;
        min-width: 100%;
    }
    
    .hst-tab {
        padding: 10px 5px;
        color: var(--hst-text-muted);
        font-weight: 500;
        text-decoration: none;
        position: relative;
        font-size: 0.95rem;
        transition: color 0.2s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .hst-tab:hover {
        color: var(--hst-primary);
        text-decoration: none;
    }
    
    .hst-tab.active {
        color: var(--hst-primary);
        font-weight: 600;
    }
    
    .hst-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: var(--hst-primary);
    }
    
    .btn-hst-primary {
        background: var(--hst-accent-gradient);
        border: none;
        color: #fff !important;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(255, 140, 0, 0.2);
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }
    .btn-hst-primary:hover {
        background: var(--hst-accent);
        color: #fff !important;
        transform: translateY(-1px);
    }

    .btn-hst-outline {
        background-color: var(--hst-bg-card);
        border: 1px solid var(--hst-border);
        color: var(--hst-primary) !important;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }
    .btn-hst-outline:hover {
        background-color: var(--hst-bg-hover);
        color: var(--hst-primary) !important;
    }

    .hst-card {
        background: var(--hst-bg-card);
        border: 1px solid var(--hst-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }

    .hst-stat-card {
        display: flex;
        align-items: center;
        padding: 20px;
        gap: 16px;
        border-radius: 12px;
    }

    .hst-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .hst-stat-icon.primary { background-color: #e0f2fe; color: #0284c7; }
    .hst-stat-icon.secondary { background-color: #f1f5f9; color: #475569; }
    .hst-stat-icon.danger { background-color: #fee2e2; color: #dc2626; }
    .hst-stat-icon.tertiary { background-color: #dcfce7; color: #16a34a; }

    .hst-stat-label {
        font-size: 0.8rem;
        color: var(--hst-text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .hst-stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--hst-text-dark);
        line-height: 1.1;
        margin-top: 4px;
    }

    .hst-section-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--hst-text-dark);
        padding: 16px 20px;
        border-bottom: 1px solid var(--hst-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hst-section-subtitle {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        color: var(--hst-text-muted);
        font-weight: 400;
        margin-top: 2px;
    }

    .hst-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .hst-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 12px 16px;
        border-bottom: 1px solid var(--hst-border);
        letter-spacing: 0.5px;
    }
    .hst-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: var(--hst-text-dark);
        font-size: 0.9rem;
    }
    .hst-table tr:hover td {
        background-color: #f8fafc;
    }

    .hst-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .hst-status-pill.success { background-color: #dcfce7; color: #166534; }
    .hst-status-pill.warning { background-color: #fef3c7; color: #92400e; }
    .hst-status-pill.danger { background-color: #fee2e2; color: #991b1b; }
    .hst-status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
    }

    .tpl-chip {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .tpl-chip.is-indigo { background-color: #e0e7ff; color: #4338ca; }
    .tpl-chip.is-amber { background-color: #fef3c7; color: #b45309; }
    .tpl-chip.is-blue { background-color: #e0f2fe; color: #0369a1; }
    .tpl-chip.is-emerald { background-color: #d1fae5; color: #047857; }

    .tpl-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }
    .tpl-table th {
        background: #f8fafc;
        padding: 12px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #475569;
        border-bottom: 1px solid var(--hst-border);
    }
    .tpl-table td {
        padding: 14px 16px;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .tpl-table tr:hover td { background-color: #f8fafc; }

    .hst-progress-bar {
        background-color: #f1f5f9;
        height: 8px;
        border-radius: 9999px;
        overflow: hidden;
    }
    .hst-progress-fill.gradient {
        background: var(--hst-accent-gradient);
        height: 100%;
        border-radius: 9999px;
    }

    .hst-allocation-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .hst-allocation-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .hst-allocation-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- Header & Title Area -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 pt-3">
    <div class="hst-title-area">
        <h3 class="flex items-center gap-2">
            <i class="fa-solid fa-hotel text-amber-500"></i>
            Hostel Management
        </h3>
        <p>Live overview of all hostel accommodation inventory and allocations.</p>
    </div>
    <div class="d-flex gap-2 mb-3 mb-md-0">
        <a href="{{ route('school.hostel.allocations') }}" class="btn-hst-primary">
            <i class="fas fa-user-plus mr-1"></i> Allocate Student
        </a>
        <a href="{{ route('school.hostel.hostels.create') }}" class="btn-hst-outline">
            <i class="fas fa-plus mr-1"></i> Add Hostel
        </a>
    </div>
</div>

<!-- Sub-navigation Tabs -->
<div class="hst-tabs-wrap no-scrollbar">
    <div class="hst-tabs">
        <a href="{{ route('school.hostel.dashboard') }}" class="hst-tab {{ request()->routeIs('school.hostel.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large mr-1"></i> Dashboard
        </a>
        <a href="{{ route('school.hostel.guide') }}" class="hst-tab {{ request()->routeIs('school.hostel.guide') ? 'active' : '' }}">
            <i class="fas fa-book-open mr-1"></i> Guide
        </a>
        <a href="{{ route('school.hostel.allocations') }}" class="hst-tab {{ request()->routeIs('school.hostel.allocations*') ? 'active' : '' }}">
            <i class="fas fa-users mr-1"></i> Student Allocation
        </a>
        <a href="{{ route('school.hostel.rooms') }}" class="hst-tab {{ request()->routeIs('school.hostel.rooms*') ? 'active' : '' }}">
            <i class="fas fa-door-open mr-1"></i> Manage Rooms
        </a>
        <a href="{{ route('school.hostel.room-types') }}" class="hst-tab {{ request()->routeIs('school.hostel.room-types*') ? 'active' : '' }}">
            <i class="fas fa-tags mr-1"></i> Room Types
        </a>
        <a href="{{ route('school.hostel.hostels') }}" class="hst-tab {{ request()->routeIs('school.hostel.hostels*') ? 'active' : '' }}">
            <i class="fas fa-building mr-1"></i> Manage Hostels
        </a>
    </div>
</div>

