<div class="qr-tabs-wrap">
    <div class="qr-tabs">
        <a class="qr-tab {{ request()->routeIs('qr-attendance.scanner') ? 'active' : '' }}" href="{{ route('qr-attendance.scanner') }}">
            <i class="fas fa-qrcode mr-1"></i> QR Attendance
        </a>
        <a class="qr-tab {{ request()->routeIs('qr-attendance.settings*') ? 'active' : '' }}" href="{{ route('qr-attendance.settings') }}">
            <i class="fas fa-sliders-h mr-1"></i> QR Attendance Setting
        </a>
        <a class="qr-tab {{ request()->routeIs('qr-attendance.report*') ? 'active' : '' }}" href="{{ route('qr-attendance.report') }}">
            <i class="fas fa-chart-line mr-1"></i> QR Attendance Report
        </a>
        <a class="qr-tab {{ request()->routeIs('qr-attendance.audit*') ? 'active' : '' }}" href="{{ route('qr-attendance.audit') }}">
            <i class="fas fa-shield-alt mr-1"></i> QR Scan Audit
        </a>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    .qr-title-area, .qr-tabs, .qr-tab, .btn-qr-primary { font-family: 'Inter', sans-serif; }
    .qr-title-area { margin-bottom: 20px; }
    .qr-title-area h3 { font-weight: 700; color: #0f172a; font-size: 1.5rem; }
    .qr-title-area p { color: #64748b; font-size: 0.9rem; margin-bottom: 0; }

    .qr-tabs-wrap { display: flex; align-items: stretch; border-bottom: 1px solid #e2e8f0; margin-bottom: 25px; max-width: 100%; }
    .qr-tabs {
        display: flex; flex-wrap: nowrap; align-items: stretch; gap: 0 18px;
        flex: 1 1 auto; min-width: 0;
        overflow-x: auto; -webkit-overflow-scrolling: touch;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .qr-tabs::-webkit-scrollbar { display: none; }
    .qr-tab {
        padding: 10px 5px; color: #64748b; font-weight: 500; text-decoration: none;
        position: relative; font-size: 0.9rem; white-space: nowrap; display: flex; align-items: center;
        flex: 0 0 auto;
    }
    @media (max-width: 575px) {
        .qr-tabs { gap: 0 14px; }
        .qr-tab { font-size: 0.85rem; }
        .qr-title-area h3 { font-size: 1.25rem; }
    }
    .qr-tab:hover { color: #0f172a; text-decoration: none; }
    .qr-tab.active { color: #0f172a; font-weight: 600; }
    .qr-tab.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background-color: #0284c7; }

    .btn-qr-primary { background-color: #0284c7; border-color: #0284c7; color: #fff; font-weight: 600; border-radius: 6px; }
    .btn-qr-primary:hover { background-color: #0369a1; border-color: #0369a1; color: #fff; }
</style>

