@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- Top KPI cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-sky">
                <i class="fa-solid fa-boxes"></i>
            </div>
            <div>
                <div class="inv-stat-label">Total SKUs</div>
                <div class="inv-stat-value">{{ $totalItems }}</div>
                <div class="inv-stat-sub text-slate-500">Catalogued items</div>
            </div>
        </div>

        <div class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-emerald">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div>
                <div class="inv-stat-label">Total Asset Value</div>
                <div class="inv-stat-value text-emerald-600">₹{{ number_format($totalStockValue, 2) }}</div>
                <div class="inv-stat-sub text-slate-500">Inventory valuation</div>
            </div>
        </div>

        <div class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-purple">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div>
                <div class="inv-stat-label">POS Gross Revenue</div>
                <div class="inv-stat-value text-purple-600">₹{{ number_format($totalSalesAmount, 2) }}</div>
                <div class="inv-stat-sub text-slate-500">Total sale receipts</div>
            </div>
        </div>

        <div class="inv-stat-card">
            <div class="inv-stat-icon inv-bg-amber">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div>
                <div class="inv-stat-label">Supplier Outflows</div>
                <div class="inv-stat-value text-amber-600">₹{{ number_format($totalSupplierPayments, 2) }}</div>
                <div class="inv-stat-sub text-slate-500">Vendor disbursements</div>
            </div>
        </div>
    </div>

    <!-- Category Valuation & Distribution -->
    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-slate-700"></i>
                Category Valuation & Item Distribution Summary
            </span>
            <button onclick="window.print()" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                <i class="fa-solid fa-print mr-1"></i> Print Report
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Classification</th>
                        <th>Total Items Count</th>
                        <th>Stock Units Available</th>
                        <th>Estimated Category Valuation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    @php
                        $items = $cat->items;
                        $totalQty = $items->sum('stock_quantity');
                        $catVal = $items->sum(function($it) { return $it->stock_quantity * $it->unit_price; });
                    @endphp
                    <tr>
                        <td class="font-bold text-slate-900 text-xs flex items-center gap-2">
                            <i class="fa-solid {{ $cat->icon ?: 'fa-tag' }} text-slate-400"></i>
                            {{ $cat->name }}
                        </td>
                        <td>
                            <span class="text-xs px-2 py-0.5 rounded font-medium {{ $cat->type === 'Saleable' ? 'bg-purple-50 text-purple-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $cat->type }}
                            </span>
                        </td>
                        <td class="text-xs font-mono font-bold">{{ $cat->items_count }}</td>
                        <td class="text-xs font-mono">{{ number_format($totalQty) }} units</td>
                        <td class="text-xs font-mono font-bold text-slate-900">₹{{ number_format($catVal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

