@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- Info Banner -->
    <div class="inv-card p-5 mb-5 bg-gradient-to-r from-purple-50 to-indigo-50 border-purple-200">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900">Vendor Collections & Settlements</h4>
                <p class="text-xs text-slate-600 mt-0.5">
                    Track sales collected by the school cash counter / POS on behalf of contracted third-party vendors (e.g. Uniform Stitchers, Book Publishers, Lab Kit Manufacturers).
                </p>
            </div>
        </div>
    </div>

    <!-- Vendor Sales Table -->
    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-receipt text-slate-700"></i>
                Third-Party Vendor Sales ({{ $vendorSales->total() }})
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Vendor Partner</th>
                        <th>Customer / Buyer</th>
                        <th>Amount Collected</th>
                        <th>Payment Mode</th>
                        <th>Settlement Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendorSales as $vSale)
                    <tr>
                        <td class="font-mono font-bold text-xs text-sky-600">
                            <a href="{{ route('school.inventory.sales.show', $vSale->id) }}" class="hover:underline">
                                {{ $vSale->invoice_no }}
                            </a>
                        </td>
                        <td class="text-xs text-slate-600">{{ $vSale->sale_date ? $vSale->sale_date->format('d M Y') : '—' }}</td>
                        <td>
                            <span class="font-bold text-xs text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                {{ $vSale->vendor->name ?? 'Vendor' }}
                            </span>
                        </td>
                        <td class="text-xs font-semibold text-slate-800">{{ $vSale->buyer_name ?: $vSale->customer_name }}</td>
                        <td class="text-xs font-bold font-mono text-slate-900">₹{{ number_format($vSale->net_amount ?: $vSale->total_amount, 2) }}</td>
                        <td>
                            <span class="text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                                {{ $vSale->payment_mode ?: $vSale->payment_method }}
                            </span>
                        </td>
                        <td>
                            <span class="inv-badge inv-badge-ok">Collected</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 text-xs">
                            No third-party vendor sales recorded yet. Sales made via POS with "On behalf of Vendor" mode will appear here.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vendorSales->hasPages())
        <div class="p-3 border-t border-slate-100">
            {{ $vendorSales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

