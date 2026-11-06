@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-4xl mx-auto">
    @include('inventory.nav')

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-file-circle-plus text-sky-600"></i>
                Create Purchase Order (PO)
            </span>
            <a href="{{ route('school.inventory.purchase-orders') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold">&larr; Back to Purchase Orders</a>
        </div>

        <form action="{{ route('school.inventory.purchase-orders.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Header Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier <span class="text-rose-500">*</span></label>
                    <select name="inventory_supplier_id" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-medium">
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->supplier_type }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Order Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <!-- Items Table -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Purchase Order Items</h4>
                    <button type="button" onclick="addPoItemRow()" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-bold transition flex items-center gap-1">
                        <i class="fa-solid fa-plus text-[10px]"></i> Add Item Row
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs" id="poItemsTable">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-left border-y border-slate-200">
                                <th class="py-2 px-3">Item</th>
                                <th class="py-2 px-3 w-28">Quantity</th>
                                <th class="py-2 px-3 w-32">Unit Cost (₹)</th>
                                <th class="py-2 px-3 w-24">GST %</th>
                                <th class="py-2 px-3 w-32 text-right">Line Total</th>
                                <th class="py-2 px-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="poItemsBody" class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2 px-3">
                                    <select name="items[0][item_id]" onchange="setRowPrice(this, 0)" required class="w-full py-1.5 px-2 text-xs rounded border border-slate-300">
                                        <option value="">-- Choose Item --</option>
                                        @foreach($items as $it)
                                        <option value="{{ $it->id }}" data-cost="{{ $it->unit_price }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2 px-3">
                                    <input type="number" name="items[0][quantity]" value="1" min="1" oninput="calcLine(0)" class="w-full py-1.5 px-2 text-xs rounded border border-slate-300 font-mono row-qty">
                                </td>
                                <td class="py-2 px-3">
                                    <input type="number" step="0.01" name="items[0][unit_cost]" value="0.00" oninput="calcLine(0)" class="w-full py-1.5 px-2 text-xs rounded border border-slate-300 font-mono row-cost">
                                </td>
                                <td class="py-2 px-3">
                                    <input type="number" step="0.1" name="items[0][gst_percent]" value="0" oninput="calcLine(0)" class="w-full py-1.5 px-2 text-xs rounded border border-slate-300 font-mono row-gst">
                                </td>
                                <td class="py-2 px-3 text-right font-bold font-mono text-slate-900 row-total">
                                    ₹0.00
                                </td>
                                <td class="py-2 px-2 text-center">
                                    <button type="button" onclick="this.closest('tr').remove(); calcGrand();" class="text-rose-500 hover:text-rose-700">&times;</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 font-bold border-t border-slate-200">
                                <td colspan="4" class="py-2.5 px-3 text-right">PO Total Amount:</td>
                                <td class="py-2.5 px-3 text-right font-mono text-emerald-600 text-sm" id="poGrandTotal">₹0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Terms & Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Commercial Terms</label>
                    <textarea name="terms" rows="2" placeholder="e.g. Net 30 days payment upon receipt of materials..." class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Internal Notes</label>
                    <textarea name="notes" rows="2" placeholder="Requisition approval reference, department notes..." class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('school.inventory.purchase-orders') }}" class="px-5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-800 shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Generate Purchase Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let rowIndex = 1;

function addPoItemRow() {
    const tbody = document.getElementById('poItemsBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="py-2 px-3">
            <select name="items[${rowIndex}][item_id]" onchange="setRowPrice(this, ${rowIndex})" required class="w-full py-1.5 px-2 text-xs rounded border border-slate-300">
                <option value="">-- Choose Item --</option>
                @foreach($items as $it)
                <option value="{{ $it->id }}" data-cost="{{ $it->unit_price }}">{{ $it->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="py-2 px-3">
            <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" oninput="calcLine(${rowIndex})" class="w-full py-1.5 px-2 text-xs rounded border border-slate-300 font-mono row-qty">
        </td>
        <td class="py-2 px-3">
            <input type="number" step="0.01" name="items[${rowIndex}][unit_cost]" value="0.00" oninput="calcLine(${rowIndex})" class="w-full py-1.5 px-2 text-xs rounded border border-slate-300 font-mono row-cost">
        </td>
        <td class="py-2 px-3">
            <input type="number" step="0.1" name="items[${rowIndex}][gst_percent]" value="0" oninput="calcLine(${rowIndex})" class="w-full py-1.5 px-2 text-xs rounded border border-slate-300 font-mono row-gst">
        </td>
        <td class="py-2 px-3 text-right font-bold font-mono text-slate-900 row-total">
            ₹0.00
        </td>
        <td class="py-2 px-2 text-center">
            <button type="button" onclick="this.closest('tr').remove(); calcGrand();" class="text-rose-500 hover:text-rose-700">&times;</button>
        </td>
    `;
    tbody.appendChild(tr);
    rowIndex++;
}

function setRowPrice(selectEl, idx) {
    const opt = selectEl.options[selectEl.selectedIndex];
    const cost = opt.dataset.cost || 0;
    const row = selectEl.closest('tr');
    row.querySelector('.row-cost').value = parseFloat(cost).toFixed(2);
    calcLine(idx);
}

function calcLine(idx) {
    const tbody = document.getElementById('poItemsBody');
    const rows = tbody.querySelectorAll('tr');
    let total = 0;

    rows.forEach(r => {
        const qty = parseFloat(r.querySelector('.row-qty').value) || 0;
        const cost = parseFloat(r.querySelector('.row-cost').value) || 0;
        const gst = parseFloat(r.querySelector('.row-gst').value) || 0;
        const lineBase = qty * cost;
        const lineTax = (lineBase * gst) / 100;
        const lineTot = lineBase + lineTax;
        r.querySelector('.row-total').textContent = '₹' + lineTot.toFixed(2);
        total += lineTot;
    });

    document.getElementById('poGrandTotal').textContent = '₹' + total.toFixed(2);
}

function calcGrand() {
    calcLine(0);
}
</script>
@endsection

