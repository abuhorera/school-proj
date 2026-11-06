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
        <!-- Stock Adjustment Form -->
        <div class="inv-card overflow-hidden h-fit">
            <div class="inv-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-sliders-h text-slate-700"></i>
                    Record Stock Adjustment
                </span>
            </div>
            <form action="{{ route('school.inventory.adjustments.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Item <span class="text-rose-500">*</span></label>
                    <select name="inventory_item_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-medium">
                        <option value="">-- Choose Item --</option>
                        @foreach($items as $it)
                        <option value="{{ $it->id }}">{{ $it->name }} (In Stock: {{ $it->stock_quantity }} {{ $it->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Direction / Operation <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="p-2 border border-slate-200 rounded-lg text-xs font-semibold flex items-center gap-2 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="direction" value="in" checked class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-emerald-700 font-bold">+ Increase Stock</span>
                        </label>
                        <label class="p-2 border border-slate-200 rounded-lg text-xs font-semibold flex items-center gap-2 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="direction" value="out" class="text-rose-600 focus:ring-rose-500">
                            <span class="text-rose-700 font-bold">- Reduce Stock</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Quantity <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="quantity" required placeholder="Adjustment quantity" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Reason <span class="text-rose-500">*</span></label>
                    <select name="reason" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="Physical Audit Recount">Physical Audit Recount</option>
                        <option value="Damaged / Broken">Damaged / Broken</option>
                        <option value="Expired Product">Expired Product</option>
                        <option value="Found Uncounted Stock">Found Uncounted Stock</option>
                        <option value="Lab Breakage">Lab Breakage</option>
                        <option value="Other Discrepancy">Other Discrepancy</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Auditor / Adjustment Notes</label>
                    <textarea name="notes" rows="2" placeholder="Details about recount or incident..." class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition shadow-xs flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Apply Stock Adjustment
                </button>
            </form>
        </div>

        <!-- Adjustments Log Table -->
        <div class="lg:col-span-2 inv-card overflow-hidden">
            <div class="inv-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                    Adjustment Logs & Audit History
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Change</th>
                            <th>Reason</th>
                            <th>Auditor</th>
                            <th>Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $adj)
                        <tr>
                            <td class="font-bold text-slate-900 text-xs">{{ $adj->item->name ?? 'Deleted Item' }}</td>
                            <td>
                                <span class="inv-badge {{ $adj->quantity > 0 ? 'inv-badge-ok font-mono' : 'inv-badge-danger font-mono' }}">
                                    {{ $adj->quantity > 0 ? '+'.$adj->quantity : $adj->quantity }}
                                </span>
                            </td>
                            <td>
                                <span class="text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $adj->type)) }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-600">{{ $adj->performed_by }}</td>
                            <td class="text-xs text-slate-500">{{ $adj->date ? $adj->date->format('d M Y') : '—' }}</td>
                            <td class="text-xs text-slate-500 max-w-xs truncate">{{ $adj->remarks ?: '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 text-xs">No adjustments logged yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($adjustments->hasPages())
            <div class="p-3 border-t border-slate-100">
                {{ $adjustments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

