@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <div class="inv-card p-4 mb-5">
        <form action="{{ route('school.inventory.sales') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-80">
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Invoice #, Buyer Name..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
            </div>
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white font-semibold text-xs rounded-lg hover:bg-slate-800 transition">
                    Search
                </button>
                <a href="{{ route('school.inventory.sales.create') }}" class="px-4 py-1.5 bg-emerald-600 text-white font-bold text-xs rounded-lg hover:bg-emerald-700 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-cash-register"></i> New POS Sale
                </a>
            </div>
        </form>
    </div>

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-receipt text-slate-700"></i>
                Sales Invoices & Transactions ({{ $sales->total() }})
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Invoice #</th>
                        <th>Date & Time</th>
                        <th>Buyer</th>
                        <th>Billing Entity</th>
                        <th>Net Amount</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $idx => $sale)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $sales->firstItem() + $idx }}</td>
                        <td>
                            <a href="{{ route('school.inventory.sales.show', $sale->id) }}" class="font-bold font-mono text-xs text-sky-600 hover:underline">
                                {{ $sale->invoice_no }}
                            </a>
                        </td>
                        <td class="text-xs text-slate-600">
                            <div>{{ $sale->sale_date ? $sale->sale_date->format('d M Y') : $sale->created_at->format('d M Y') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $sale->created_at ? $sale->created_at->format('h:i A') : '' }}</div>
                        </td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs">{{ $sale->buyer_name ?: $sale->customer_name }}</div>
                            <div class="text-[10px] text-slate-400 capitalize">{{ $sale->buyer_type ?: $sale->customer_type }}</div>
                        </td>
                        <td>
                            <span class="text-xs font-medium px-2 py-0.5 rounded {{ $sale->billing_mode === 'vendor' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $sale->billing_mode === 'vendor' ? ($sale->vendor->name ?? 'Vendor') : 'School' }}
                            </span>
                        </td>
                        <td>
                            <span class="font-bold font-mono text-xs text-slate-900">₹{{ number_format($sale->net_amount ?: $sale->total_amount, 2) }}</span>
                        </td>
                        <td>
                            <span class="text-xs font-semibold text-slate-700 uppercase bg-slate-100 px-2 py-0.5 rounded">
                                {{ $sale->payment_mode ?: $sale->payment_method }}
                            </span>
                        </td>
                        <td>
                            <span class="inv-badge inv-badge-ok">
                                {{ $sale->status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('school.inventory.sales.show', $sale->id) }}" class="px-2.5 py-1 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition">
                                <i class="fa-solid fa-file-invoice text-slate-500 mr-1"></i> Receipt
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-slate-400 text-xs">No sales recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="p-3 border-t border-slate-100">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

