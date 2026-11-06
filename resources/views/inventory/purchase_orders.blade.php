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

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-file-invoice text-slate-700"></i>
                Purchase Orders ({{ $purchaseOrders->total() }})
            </span>
            <a href="{{ route('school.inventory.purchase-orders.create') }}" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i> New PO
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>PO #</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Items Count</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $idx => $po)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $purchaseOrders->firstItem() + $idx }}</td>
                        <td>
                            <a href="{{ route('school.inventory.purchase-orders.show', $po->id) }}" class="font-bold font-mono text-xs text-sky-600 hover:underline">
                                {{ $po->po_no }}
                            </a>
                        </td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs">{{ $po->supplier->name ?? 'Supplier' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $po->supplier->contact_person ?? '' }}</div>
                        </td>
                        <td class="text-xs text-slate-600">{{ $po->order_date ? $po->order_date->format('d M Y') : '—' }}</td>
                        <td class="text-xs text-slate-700 font-mono">{{ $po->items->count() }}</td>
                        <td class="text-xs font-bold font-mono text-slate-900">₹{{ number_format($po->total_amount, 2) }}</td>
                        <td>
                            @if($po->status === 'Received')
                            <span class="inv-badge inv-badge-ok">Received</span>
                            @elseif($po->status === 'Approved')
                            <span class="inv-badge bg-sky-100 text-sky-800">Approved</span>
                            @elseif($po->status === 'Draft')
                            <span class="inv-badge bg-amber-100 text-amber-800">Draft</span>
                            @else
                            <span class="inv-badge bg-slate-100 text-slate-600">{{ $po->status }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('school.inventory.purchase-orders.show', $po->id) }}" class="px-2.5 py-1 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition">
                                View &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400 text-xs">No purchase orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchaseOrders->hasPages())
        <div class="p-3 border-t border-slate-100">
            {{ $purchaseOrders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

