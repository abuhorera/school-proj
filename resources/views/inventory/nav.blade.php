<style>
    .inv-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        margin-bottom: 20px;
    }
    .inv-stat-card {
        display: flex; align-items: center;
        padding: 20px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .inv-stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); border-color: #cbd5e1; }
    .inv-stat-icon {
        width: 48px; height: 48px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; margin-right: 15px; flex-shrink: 0;
    }
    .inv-bg-sky { background-color: #e0f2fe; color: #0284c7; }
    .inv-bg-amber { background-color: #fef3c7; color: #d97706; }
    .inv-bg-rose { background-color: #ffe4e6; color: #e11d48; }
    .inv-bg-emerald { background-color: #d1fae5; color: #059669; }
    .inv-bg-indigo { background-color: #e0e7ff; color: #4f46e5; }
    .inv-bg-slate { background-color: #f1f5f9; color: #64748b; }
    .inv-stat-value { font-size: 1.65rem; font-weight: 800; color: #0f172a; line-height: 1.1; margin-top: 4px; }
    .inv-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .inv-stat-sub { font-size: 0.75rem; margin-top: 4px; }
    .inv-section-title {
        font-size: 1.05rem; font-weight: 700; color: #0f172a;
        padding: 16px 20px; border-bottom: 1px solid #e2e8f0;
        display: flex; justify-content: space-between; align-items: center;
    }
    .inv-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .inv-table th { background-color: #0f172a; color: #fff; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; padding: 11px 16px; border: none; letter-spacing: 0.5px; }
    .inv-table td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 0.88rem; }
    .inv-table tr:hover td { background-color: #f8fafc; }
    .inv-badge { padding: 3px 9px; border-radius: 9999px; font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .inv-badge-warn { background-color: #fef3c7; color: #854d0e; }
    .inv-badge-danger { background-color: #fee2e2; color: #991b1b; }
    .inv-badge-ok { background-color: #dcfce7; color: #166534; }
    .inv-tabs-wrap {
        display: flex;
        align-items: stretch;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 22px;
        overflow-x: auto;
    }
    .inv-tabs {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        gap: 0 16px;
        min-width: 100%;
    }
    .inv-tab {
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
    .inv-tab:hover { color: #0f172a; }
    .inv-tab.active {
        color: #0284c7;
        font-weight: 600;
        border-bottom-color: #0284c7;
    }
</style>

<div class="mb-4">
    <!-- Header title area -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-3 pt-2">
        <div class="inv-title-area">
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-boxes text-slate-700"></i>
                Inventory & Stock
            </h3>
            <p class="text-sm text-slate-500 mt-0.5">Track items, stock levels, expiry, issuance, Point of Sale, and suppliers across the school.</p>
        </div>
        <div class="mt-3 md:mt-0 flex items-center space-x-2">
            <a href="{{ route('school.inventory.sales.create') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-cash-register"></i> Open POS
            </a>
            <a href="{{ route('school.inventory.add-stock') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus-circle"></i> Add Stock
            </a>
            <a href="{{ route('school.inventory.items.create') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-box-open"></i> New Item
            </a>
        </div>
    </div>

    <!-- Sub-navigation Tabs -->
    <div class="inv-tabs-wrap no-scrollbar">
        <div class="inv-tabs">
            <a href="{{ route('school.inventory.dashboard') }}" class="inv-tab {{ request()->routeIs('school.inventory.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('school.inventory.guide') }}" class="inv-tab {{ request()->routeIs('school.inventory.guide') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i> Guide
            </a>
            <a href="{{ route('school.inventory.items') }}" class="inv-tab {{ request()->routeIs('school.inventory.items*') && !request()->routeIs('school.inventory.items.create') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i> Items
            </a>
            <a href="{{ route('school.inventory.categories') }}" class="inv-tab {{ request()->routeIs('school.inventory.categories') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="{{ route('school.inventory.add-stock') }}" class="inv-tab {{ request()->routeIs('school.inventory.add-stock') ? 'active' : '' }}">
                <i class="fas fa-plus-square"></i> Add Stock
            </a>
            <a href="{{ route('school.inventory.adjustments') }}" class="inv-tab {{ request()->routeIs('school.inventory.adjustments') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i> Adjustments
            </a>
            <a href="{{ route('school.inventory.issue-item') }}" class="inv-tab {{ request()->routeIs('school.inventory.issue-item') ? 'active' : '' }}">
                <i class="fas fa-share-square"></i> Issue / Return
            </a>
            <a href="{{ route('school.inventory.sales.create') }}" class="inv-tab {{ request()->routeIs('school.inventory.sales.create') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i> Point of Sale
            </a>
            <a href="{{ route('school.inventory.sales') }}" class="inv-tab {{ request()->routeIs('school.inventory.sales') && !request()->routeIs('school.inventory.sales.create') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i> Sales
            </a>
            <a href="{{ route('school.inventory.purchase-orders') }}" class="inv-tab {{ request()->routeIs('school.inventory.purchase-orders*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Purchase Orders
            </a>
            <a href="{{ route('school.inventory.grns') }}" class="inv-tab {{ request()->routeIs('school.inventory.grns*') ? 'active' : '' }}">
                <i class="fas fa-dolly"></i> Goods Receipts
            </a>
            <a href="{{ route('school.inventory.supplier-payments') }}" class="inv-tab {{ request()->routeIs('school.inventory.supplier-payments') ? 'active' : '' }}">
                <i class="fas fa-money-check-alt"></i> Supplier Payments
            </a>
            <a href="{{ route('school.inventory.suppliers') }}" class="inv-tab {{ request()->routeIs('school.inventory.suppliers') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Suppliers
            </a>
            <a href="{{ route('school.inventory.vendor-collections') }}" class="inv-tab {{ request()->routeIs('school.inventory.vendor-collections') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i> Vendor Collections
            </a>
            <a href="{{ route('school.inventory.reports') }}" class="inv-tab {{ request()->routeIs('school.inventory.reports') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </div>
    </div>
</div>
