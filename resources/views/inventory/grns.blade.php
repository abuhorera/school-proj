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
                <i class="fa-solid fa-dolly text-slate-700"></i>
                Goods Receipt Notes (GRN) ({{ $grns->total() }})
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>GRN #</th>
                        <th>PO #</th>
                        <th>Supplier</th>
                        <th>Received Date</th>
                        <th>Lines Count</th>
                        <th>Received By</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grns as $idx => $grn)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $grns->firstItem() + $idx }}</td>
                        <td>
                            <a href="{{ route('school.inventory.grns.show', $grn->id) }}" class="font-bold font-mono text-xs text-sky-600 hover:underline">
                                {{ $grn->grn_no }}
                            </a>
                        </td>
                        <td>
                            @if($grn->purchaseOrder)
                            <a href="{{ route('school.inventory.purchase-orders.show', $grn->purchaseOrder->id) }}" class="font-mono text-xs text-slate-700 hover:underline font-semibold">
                                {{ $grn->purchaseOrder->po_no }}
                            </a>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs">{{ $grn->supplier->name ?? 'Supplier' }}</div>
                        </td>
                        <td class="text-xs text-slate-600">{{ $grn->received_date ? $grn->received_date->format('d M Y') : '—' }}</td>
                        <td class="text-xs font-mono font-bold text-slate-700">{{ $grn->items->count() }}</td>
                        <td class="text-xs text-slate-600">{{ $grn->received_by }}</td>
                        <td class="text-right">
                            <a href="{{ route('school.inventory.grns.show', $grn->id) }}" class="px-2.5 py-1 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition">
                                View &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400 text-xs">No goods receipt notes logged yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($grns->hasPages())
        <div class="p-3 border-t border-slate-100">
            {{ $grns->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

