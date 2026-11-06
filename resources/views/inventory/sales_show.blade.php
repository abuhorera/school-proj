@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-2xl mx-auto">
    @include('inventory.nav')

    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('school.inventory.sales') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900">&larr; Back to Sales History</a>
        <button onclick="window.print()" class="px-4 py-1.5 bg-slate-900 text-white font-bold text-xs rounded-lg hover:bg-slate-800 transition flex items-center gap-1.5">
            <i class="fa-solid fa-print"></i> Print Receipt
        </button>
    </div>

    <!-- Printable Receipt Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm font-sans" id="printableReceipt">
        <!-- Receipt Header -->
        <div class="text-center pb-4 border-b border-slate-100">
            <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">YUG INTERNATIONAL SCHOOL</h2>
            <p class="text-xs text-slate-500 mt-0.5">Campus Inventory & Book Depot</p>
            <div class="mt-2 inline-block px-3 py-1 bg-slate-100 rounded-full text-xs font-mono font-bold text-slate-700">
                INVOICE: {{ $sale->invoice_no }}
            </div>
        </div>

        <!-- Meta Info -->
        <div class="grid grid-cols-2 gap-4 py-4 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Buyer Details:</span>
                <span class="font-bold text-slate-900 text-sm">{{ $sale->buyer_name ?: $sale->customer_name }}</span>
                <div class="text-slate-500">{{ $sale->buyer_type ?: $sale->customer_type }}</div>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Date & Time:</span>
                <span class="font-semibold text-slate-800">{{ $sale->sale_date ? $sale->sale_date->format('d M Y') : $sale->created_at->format('d M Y') }}</span>
                <div class="text-slate-400 font-mono">{{ $sale->created_at ? $sale->created_at->format('h:i A') : '' }}</div>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Billing Entity:</span>
                <span class="font-semibold text-slate-800">{{ $sale->billing_mode === 'vendor' ? ($sale->vendor->name ?? 'Vendor') : 'School Store' }}</span>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Payment Method:</span>
                <span class="font-bold text-emerald-600 uppercase">{{ $sale->payment_mode ?: $sale->payment_method }}</span>
                @if($sale->payment_reference)
                <div class="text-[10px] font-mono text-slate-400">Ref: {{ $sale->payment_reference }}</div>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-4 border-b border-slate-100">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] border-b border-slate-100 pb-2">
                        <th class="text-left font-bold pb-2">Item Description</th>
                        <th class="text-center font-bold pb-2">Qty</th>
                        <th class="text-right font-bold pb-2">Rate</th>
                        <th class="text-right font-bold pb-2">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($sale->items as $sItem)
                    <tr>
                        <td class="py-2.5 font-bold text-slate-800">{{ $sItem->item_name }}</td>
                        <td class="py-2.5 text-center font-mono">{{ $sItem->quantity }}</td>
                        <td class="py-2.5 text-right font-mono">₹{{ number_format($sItem->unit_price, 2) }}</td>
                        <td class="py-2.5 text-right font-mono font-bold text-slate-900">₹{{ number_format($sItem->line_total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="py-2.5 font-bold text-slate-800">{{ $sale->notes ?: 'General Sale' }}</td>
                        <td class="py-2.5 text-center font-mono">1</td>
                        <td class="py-2.5 text-right font-mono">₹{{ number_format($sale->total_amount, 2) }}</td>
                        <td class="py-2.5 text-right font-mono font-bold text-slate-900">₹{{ number_format($sale->total_amount, 2) }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totals Summary -->
        <div class="pt-4 space-y-1.5 text-xs text-right">
            <div class="flex justify-between text-slate-500">
                <span>Subtotal:</span>
                <span class="font-mono">₹{{ number_format($sale->subtotal ?: $sale->total_amount, 2) }}</span>
            </div>
            @if($sale->discount_amount > 0)
            <div class="flex justify-between text-rose-600">
                <span>Discount:</span>
                <span class="font-mono">-₹{{ number_format($sale->discount_amount, 2) }}</span>
            </div>
            @endif
            @if($sale->tax_amount > 0)
            <div class="flex justify-between text-slate-500">
                <span>Tax (GST):</span>
                <span class="font-mono">₹{{ number_format($sale->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-100">
                <span>Total Paid:</span>
                <span class="text-emerald-600 font-mono">₹{{ number_format($sale->net_amount ?: $sale->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 pt-4 border-t border-dashed border-slate-200 text-center text-[11px] text-slate-400">
            Thank you for your purchase! All exchange inquiries must be presented within 7 days with this slip.
        </div>
    </div>
</div>
@endsection

