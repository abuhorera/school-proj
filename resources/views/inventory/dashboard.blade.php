@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- KPI Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Items -->
        <a href="{{ route('school.inventory.items') }}" class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-sky">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <div class="inv-stat-label">Total Items</div>
                <div class="inv-stat-value">{{ $totalItems }}</div>
                <div class="inv-stat-sub text-slate-500">Tracked in inventory</div>
            </div>
        </a>

        <!-- Card 2: Low Stock -->
        <a href="{{ route('school.inventory.items', ['stock_status' => 'low']) }}" class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-amber">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <div class="inv-stat-label">Low Stock</div>
                <div class="inv-stat-value text-amber-600">{{ $lowStockCount }}</div>
                <div class="inv-stat-sub text-amber-700 font-medium">Needs reorder</div>
            </div>
        </a>

        <!-- Card 3: Expired Items -->
        <a href="{{ route('school.inventory.items') }}" class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-rose">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <div>
                <div class="inv-stat-label">Expired Items</div>
                <div class="inv-stat-value text-rose-600">{{ $expiredCount }}</div>
                <div class="inv-stat-sub text-rose-700 font-medium">Remove / write-off</div>
            </div>
        </a>

        <!-- Card 4: Total Stock Value -->
        <div class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-emerald">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div>
                <div class="inv-stat-label">Stock Value</div>
                <div class="inv-stat-value text-emerald-600">₹{{ number_format($totalStockValue, 2) }}</div>
                <div class="inv-stat-sub text-slate-500">Purchase value on hand</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left & Center 2 Columns -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Low Stock Items Table -->
            <div class="inv-card overflow-hidden">
                <div class="inv-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-bell text-amber-500"></i>
                        Critical & Low Stock Items
                    </span>
                    <a href="{{ route('school.inventory.items', ['stock_status' => 'low']) }}" class="text-xs text-sky-600 font-semibold hover:underline">View All Low Stock &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="inv-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Min Threshold</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $lItem)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $lItem->name }}</td>
                                <td class="font-mono text-xs text-slate-500">{{ $lItem->sku ?: '—' }}</td>
                                <td>
                                    <span class="text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                                        {{ $lItem->category->name ?? 'General' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="inv-badge {{ $lItem->stock_quantity <= 0 ? 'inv-badge-danger' : 'inv-badge-warn' }}">
                                        {{ $lItem->stock_quantity }} {{ $lItem->unit }}
                                    </span>
                                </td>
                                <td class="text-slate-600 text-xs font-semibold">{{ $lItem->minimum_stock_level }} {{ $lItem->unit }}</td>
                                <td>
                                    <a href="{{ route('school.inventory.add-stock') }}?item_id={{ $lItem->id }}" class="px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded text-xs font-semibold hover:bg-amber-100 transition">
                                        <i class="fa-solid fa-plus text-[10px]"></i> Reorder
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400 text-sm">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> All items are healthy and above minimum safety thresholds!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Breakdown -->
            <div class="inv-card overflow-hidden">
                <div class="inv-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-slate-600"></i>
                        Inventory by Category
                    </span>
                    <a href="{{ route('school.inventory.categories') }}" class="text-xs text-sky-600 font-semibold hover:underline">Manage Categories &rarr;</a>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($categoriesWithCount as $cat)
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 text-sm shadow-2xs">
                                <i class="fa-solid {{ $cat->icon ?: 'fa-boxes' }}"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900 leading-tight">{{ $cat->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ ucfirst($cat->type) }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-700 bg-white border border-slate-200 px-2.5 py-1 rounded-full">
                            {{ $cat->items_count }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right 1 Column -->
        <div class="space-y-6">
            <!-- Operations Quick Stats -->
            <div class="inv-card overflow-hidden">
                <div class="inv-section-title">
                    <span>Operations Overview</span>
                </div>
                <div class="divide-y divide-slate-100">
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-slate-600 font-medium">Point of Sale Sales</span>
                        <a href="{{ route('school.inventory.sales') }}" class="text-sm font-bold text-emerald-600 hover:underline">
                            {{ \App\Models\InventorySale::count() }} Invoices
                        </a>
                    </div>
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-slate-600 font-medium">Pending Purchase Orders</span>
                        <a href="{{ route('school.inventory.purchase-orders') }}" class="text-sm font-bold text-amber-600 hover:underline">
                            {{ \App\Models\InventoryPurchaseOrder::where('status', 'Draft')->orWhere('status', 'Approved')->count() }} Active
                        </a>
                    </div>
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-slate-600 font-medium">Goods Receipts (GRNs)</span>
                        <a href="{{ route('school.inventory.grns') }}" class="text-sm font-bold text-indigo-600 hover:underline">
                            {{ \App\Models\InventoryGoodsReceipt::count() }} Logged
                        </a>
                    </div>
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-slate-600 font-medium">Registered Suppliers</span>
                        <a href="{{ route('school.inventory.suppliers') }}" class="text-sm font-bold text-slate-800 hover:underline">
                            {{ \App\Models\InventorySupplier::count() }} Vendors
                        </a>
                    </div>
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <a href="{{ route('school.inventory.sales.create') }}" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cash-register"></i> Open Point of Sale (POS)
                    </a>
                    <a href="{{ route('school.inventory.guide') }}" class="w-full mt-2 py-2 px-4 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-question"></i> How this module works
                    </a>
                </div>
            </div>

            <!-- Recent Stock Activity -->
            <div class="inv-card overflow-hidden">
                <div class="inv-section-title">
                    <span>Recent Activity</span>
                </div>
                <div class="p-4 space-y-3.5">
                    @forelse($recentLogs as $log)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $log->quantity >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                            <i class="fa-solid {{ $log->quantity >= 0 ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-bold text-slate-900 truncate">{{ $log->item->name ?? 'Inventory Item' }}</div>
                            <div class="text-[11px] text-slate-500 capitalize">{{ str_replace('_', ' ', $log->type) }} · {{ $log->performed_by }}</div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono {{ $log->quantity >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $log->quantity > 0 ? '+'.$log->quantity : $log->quantity }}
                            </span>
                            <div class="text-[10px] text-slate-400">{{ $log->created_at ? $log->created_at->diffForHumans() : 'Recently' }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-slate-400 text-xs">No recent inventory activity logged.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

