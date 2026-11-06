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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Record Payment Form -->
        <div class="inv-card overflow-hidden h-fit">
            <div class="inv-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-money-check-dollar text-emerald-600"></i>
                    Record Supplier Payment
                </span>
            </div>

            <form action="{{ route('school.inventory.supplier-payments.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier <span class="text-rose-500">*</span></label>
                    <select name="inventory_supplier_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-medium">
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->supplier_type }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Against PO (Optional)</label>
                    <select name="inventory_purchase_order_id" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="">-- General / No PO --</option>
                        @foreach($pos as $p)
                        <option value="{{ $p->id }}">{{ $p->po_no }} (₹{{ number_format($p->total_amount, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Amount (₹) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="amount" required placeholder="Amount" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Mode <span class="text-rose-500">*</span></label>
                    <select name="payment_mode" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-medium">
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer (NEFT/RTGS/IMPS)</option>
                        <option value="Cheque">Cheque / Demand Draft</option>
                        <option value="UPI">UPI / Digital QR</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Reference / Cheque # / Txn ID</label>
                    <input type="text" name="reference_number" placeholder="e.g. UTR-998822, CHQ-10442" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Notes</label>
                    <textarea name="notes" rows="2" placeholder="Settlement details, invoice clearance..." class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Record Payment
                </button>
            </form>
        </div>

        <!-- Payments Statement Ledger -->
        <div class="lg:col-span-2 inv-card overflow-hidden">
            <div class="inv-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-slate-600"></i>
                    Vendor Payments Statement Ledger
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Against PO</th>
                            <th>Amount Paid</th>
                            <th>Mode</th>
                            <th>Reference</th>
                            <th>Logged By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $pay)
                        <tr>
                            <td class="text-xs text-slate-600 font-medium">{{ $pay->payment_date ? $pay->payment_date->format('d M Y') : '—' }}</td>
                            <td>
                                <div class="font-bold text-slate-900 text-xs">{{ $pay->supplier->name ?? 'Supplier' }}</div>
                            </td>
                            <td>
                                @if($pay->purchaseOrder)
                                <a href="{{ route('school.inventory.purchase-orders.show', $pay->purchaseOrder->id) }}" class="font-mono text-xs text-sky-600 hover:underline">
                                    {{ $pay->purchaseOrder->po_no }}
                                </a>
                                @else
                                <span class="text-xs text-slate-400">Direct / General</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-bold font-mono text-xs text-emerald-700">₹{{ number_format($pay->amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="text-xs px-2 py-0.5 rounded bg-slate-100 font-medium text-slate-700">
                                    {{ $pay->payment_mode }}
                                </span>
                            </td>
                            <td class="font-mono text-xs text-slate-500">{{ $pay->reference_number ?: '—' }}</td>
                            <td class="text-xs text-slate-500">{{ $pay->created_by }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400 text-xs">No vendor payment entries recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
            <div class="p-3 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

