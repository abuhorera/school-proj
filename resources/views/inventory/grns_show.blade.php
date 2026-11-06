@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-3xl mx-auto">
    @include('inventory.nav')

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-dolly text-emerald-600"></i>
                Goods Receipt Note: <span class="font-mono">{{ $grn->grn_no }}</span>
            </span>
            <div class="flex items-center space-x-2">
                <a href="{{ route('school.inventory.grns') }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                    &larr; Back
                </a>
                @if($grn->purchaseOrder)
                <a href="{{ route('school.inventory.purchase-orders.show', $grn->purchaseOrder->id) }}" class="px-3 py-1 bg-sky-50 text-sky-700 border border-sky-200 hover:bg-sky-100 text-xs font-bold rounded-lg transition">
                    View PO: {{ $grn->purchaseOrder->po_no }}
                </a>
                @endif
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4 bg-slate-50/50 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Supplier:</span>
                <span class="font-bold text-slate-900">{{ $grn->supplier->name ?? '—' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Received Date:</span>
                <span class="font-bold text-slate-800">{{ $grn->received_date ? $grn->received_date->format('d M Y') : '—' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Received By:</span>
                <span class="font-bold text-slate-800">{{ $grn->received_by }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Purchase Order:</span>
                <span class="font-mono text-slate-700">{{ $grn->purchaseOrder->po_no ?? 'No PO' }}</span>
            </div>
        </div>

        <!-- Line items table -->
        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Received Qty</th>
                        <th class="text-center">Rejected Qty</th>
                        <th>Rejection Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grn->items as $gItem)
                    <tr>
                        <td class="font-bold text-slate-900 text-xs">{{ $gItem->item_name ?: ($gItem->item->name ?? 'Item') }}</td>
                        <td class="text-center font-mono font-bold text-emerald-600 text-xs">{{ $gItem->received_quantity }}</td>
                        <td class="text-center font-mono font-bold text-rose-600 text-xs">{{ $gItem->rejected_quantity }}</td>
                        <td class="text-xs text-slate-500">{{ $gItem->rejection_reason ?: '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($grn->notes)
        <div class="p-4 bg-slate-50 border-t border-slate-100 text-xs text-slate-600">
            <span class="font-bold text-slate-800">Delivery Notes:</span> {{ $grn->notes }}
        </div>
        @endif
    </div>
</div>
@endsection

