<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventorySupplier;
use App\Models\InventoryItem;
use App\Models\InventoryStockLog;
use App\Models\InventoryIssuedItem;
use App\Models\InventoryPurchaseOrder;
use App\Models\InventoryPurchaseOrderItem;
use App\Models\InventoryGoodsReceipt;
use App\Models\InventoryGoodsReceiptItem;
use App\Models\InventorySupplierPayment;
use App\Models\InventorySale;
use App\Models\InventorySaleItem;
use App\Models\Student;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryController extends Controller
{
    // =========================================================================
    // 1. DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        $totalItems = InventoryItem::count();
        $lowStockItems = InventoryItem::whereRaw('stock_quantity <= minimum_stock_level')->get();
        $lowStockCount = $lowStockItems->count();
        
        $expiredCount = InventoryItem::whereNotNull('expiry_date')
            ->where('expiry_date', '<', Carbon::today())
            ->count();
            
        $totalStockValue = InventoryItem::select(DB::raw('SUM(stock_quantity * unit_price) as total_val'))->value('total_val') ?? 0;
        
        $categoriesWithCount = InventoryCategory::withCount('items')->get();
        
        $recentLogs = InventoryStockLog::with('item', 'supplier')
            ->latest()
            ->take(6)
            ->get();
            
        $recentSales = InventorySale::latest()->take(5)->get();

        return view('inventory.dashboard', compact(
            'totalItems',
            'lowStockCount',
            'expiredCount',
            'totalStockValue',
            'categoriesWithCount',
            'recentLogs',
            'lowStockItems',
            'recentSales'
        ));
    }

    // =========================================================================
    // 2. ITEMS (LIST, CREATE, STORE, EDIT, UPDATE, DESTROY)
    // =========================================================================
    public function itemsIndex(Request $request)
    {
        $query = InventoryItem::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('item_type')) {
            $query->where('item_type', $request->item_type);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereRaw('stock_quantity <= minimum_stock_level')->where('stock_quantity', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('barcode', 'like', "%{$s}%");
            });
        }

        $items = $query->orderBy('name')->paginate(20)->withQueryString();
        $categories = InventoryCategory::orderBy('name')->get();

        return view('inventory.items', compact('items', 'categories'));
    }

    public function itemsCreate()
    {
        $categories = InventoryCategory::orderBy('name')->get();
        return view('inventory.items_create', compact('categories'));
    }

    public function itemsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:inventory_categories,id',
            'item_type' => 'required|string',
            'unit' => 'required|string',
            'stock_quantity' => 'nullable|numeric|min:0',
            'minimum_stock_level' => 'nullable|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        $sku = $request->sku;
        if (empty($sku)) {
            $sku = 'SKU-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $request->name), 0, 3)) . '-' . rand(100, 999);
        }

        $item = InventoryItem::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'sku' => $sku,
            'barcode' => $request->barcode,
            'item_type' => $request->item_type,
            'unit' => $request->unit === 'Other' ? ($request->unit_other ?: 'pcs') : $request->unit,
            'location' => $request->location,
            'stock_quantity' => $request->stock_quantity ?? 0,
            'minimum_stock_level' => $request->minimum_stock_level ?? 5,
            'unit_price' => $request->unit_price ?? 0.00,
            'selling_price' => $request->selling_price ?? 0.00,
            'expiry_date' => $request->expiry_date,
            'description' => $request->description,
        ]);

        if (($request->stock_quantity ?? 0) > 0) {
            InventoryStockLog::create([
                'inventory_item_id' => $item->id,
                'type' => 'opening_stock',
                'quantity' => $item->stock_quantity,
                'unit_cost' => $item->unit_price,
                'total_cost' => $item->stock_quantity * $item->unit_price,
                'date' => Carbon::today(),
                'performed_by' => auth()->user()->name ?? 'school admin',
                'remarks' => 'Opening stock recorded on item creation',
            ]);
        }

        return redirect()->route('school.inventory.items')->with('success', "Item '{$item->name}' created successfully!");
    }

    public function itemsEdit($id)
    {
        $item = InventoryItem::findOrFail($id);
        $categories = InventoryCategory::orderBy('name')->get();
        return view('inventory.items_edit', compact('item', 'categories'));
    }

    public function itemsUpdate(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'item_type' => 'required|string',
            'unit' => 'required|string',
        ]);

        $item->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'sku' => $request->sku ?: $item->sku,
            'barcode' => $request->barcode,
            'item_type' => $request->item_type,
            'unit' => $request->unit,
            'location' => $request->location,
            'minimum_stock_level' => $request->minimum_stock_level ?? 5,
            'unit_price' => $request->unit_price ?? 0.00,
            'selling_price' => $request->selling_price ?? 0.00,
            'expiry_date' => $request->expiry_date,
            'description' => $request->description,
        ]);

        return redirect()->route('school.inventory.items')->with('success', "Item '{$item->name}' updated successfully!");
    }

    public function itemsDestroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $name = $item->name;
        $item->delete();

        return redirect()->route('school.inventory.items')->with('success', "Item '{$name}' removed from inventory.");
    }

    // =========================================================================
    // 3. CATEGORIES
    // =========================================================================
    public function categoriesIndex()
    {
        $categories = InventoryCategory::withCount('items')->orderBy('name')->get();
        return view('inventory.categories', compact('categories'));
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string',
        ]);

        InventoryCategory::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'icon' => $request->icon ?: 'fa-box',
            'is_active' => true,
        ]);

        return redirect()->route('school.inventory.categories')->with('success', 'Category added successfully!');
    }

    public function categoriesUpdate(Request $request, $id)
    {
        $cat = InventoryCategory::findOrFail($id);
        $cat->update($request->only('name', 'type', 'description', 'icon'));

        return redirect()->route('school.inventory.categories')->with('success', 'Category updated successfully!');
    }

    public function categoriesDestroy($id)
    {
        $cat = InventoryCategory::findOrFail($id);
        $cat->delete();
        return redirect()->route('school.inventory.categories')->with('success', 'Category removed successfully!');
    }

    // =========================================================================
    // 4. SUPPLIERS
    // =========================================================================
    public function suppliersIndex()
    {
        $suppliers = InventorySupplier::withCount('purchaseOrders')->orderBy('name')->get();
        return view('inventory.suppliers', compact('suppliers'));
    }

    public function suppliersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
        ]);

        InventorySupplier::create([
            'name' => $request->name,
            'supplier_type' => $request->supplier_type ?: 'General',
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'gstin' => $request->gstin,
            'address' => $request->address,
            'status' => $request->status ?: 'Active',
        ]);

        return redirect()->route('school.inventory.suppliers')->with('success', 'Supplier registered successfully!');
    }

    public function suppliersUpdate(Request $request, $id)
    {
        $supplier = InventorySupplier::findOrFail($id);
        $supplier->update($request->only('name', 'supplier_type', 'contact_person', 'phone', 'email', 'gstin', 'address', 'status'));

        return redirect()->route('school.inventory.suppliers')->with('success', 'Supplier updated successfully!');
    }

    public function suppliersDestroy($id)
    {
        $supplier = InventorySupplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('school.inventory.suppliers')->with('success', 'Supplier removed.');
    }

    // =========================================================================
    // 5. ADD STOCK
    // =========================================================================
    public function addStockForm()
    {
        $items = InventoryItem::orderBy('name')->get();
        $suppliers = InventorySupplier::where('status', 'Active')->orderBy('name')->get();
        return view('inventory.add_stock', compact('items', 'suppliers'));
    }

    public function addStockStore(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $item = InventoryItem::findOrFail($request->inventory_item_id);
        $qty = (float) $request->quantity;
        $totalCost = (float) ($request->cost ?? 0);
        $unitCost = $qty > 0 ? ($totalCost / $qty) : $item->unit_price;

        $item->increment('stock_quantity', $qty);
        if ($unitCost > 0) {
            $item->update(['unit_price' => $unitCost]);
        }

        InventoryStockLog::create([
            'inventory_item_id' => $item->id,
            'inventory_supplier_id' => $request->inventory_supplier_id,
            'type' => 'purchase',
            'quantity' => $qty,
            'unit_cost' => $unitCost,
            'total_cost' => $totalCost,
            'date' => $request->date,
            'performed_by' => auth()->user()->name ?? 'school admin',
            'remarks' => $request->description ?: 'Direct stock replenishment',
        ]);

        return redirect()->route('school.inventory.items')->with('success', "Added {$qty} {$item->unit} to {$item->name}! New balance: {$item->stock_quantity}");
    }

    // =========================================================================
    // 6. ADJUSTMENTS
    // =========================================================================
    public function adjustmentsIndex()
    {
        $items = InventoryItem::orderBy('name')->get();
        $adjustments = InventoryStockLog::with('item')
            ->whereIn('type', ['adjustment_in', 'adjustment_out', 'damaged', 'expired', 'recount'])
            ->latest()
            ->paginate(15);

        return view('inventory.adjustments', compact('items', 'adjustments'));
    }

    public function adjustmentsStore(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'direction' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        $item = InventoryItem::findOrFail($request->inventory_item_id);
        $qty = (float) $request->quantity;

        if ($request->direction === 'in') {
            $item->increment('stock_quantity', $qty);
            $type = 'adjustment_in';
        } else {
            $item->decrement('stock_quantity', $qty);
            $type = in_array(strtolower($request->reason), ['damaged', 'expired']) ? strtolower($request->reason) : 'adjustment_out';
        }

        InventoryStockLog::create([
            'inventory_item_id' => $item->id,
            'type' => $type,
            'quantity' => $request->direction === 'in' ? $qty : -$qty,
            'unit_cost' => $item->unit_price,
            'total_cost' => $qty * $item->unit_price,
            'date' => Carbon::today(),
            'performed_by' => auth()->user()->name ?? 'school admin',
            'remarks' => "[{$request->reason}] " . ($request->notes ?: ''),
        ]);

        return redirect()->route('school.inventory.adjustments')->with('success', "Stock adjustment for '{$item->name}' recorded successfully!");
    }

    // =========================================================================
    // 7. ISSUE ITEM & RETURN
    // =========================================================================
    public function issueItemIndex()
    {
        $items = InventoryItem::where('stock_quantity', '>', 0)->orderBy('name')->get();
        $staffMembers = Staff::orderBy('first_name')->get();
        $students = Student::orderBy('first_name')->take(50)->get();
        
        $issuedList = InventoryIssuedItem::with('item')->latest()->paginate(15);

        return view('inventory.issue_item', compact('items', 'staffMembers', 'students', 'issuedList'));
    }

    public function issueItemStore(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'issued_to_type' => 'required|in:staff,student,department',
            'quantity' => 'required|integer|min:1',
            'issue_date' => 'required|date',
        ]);

        $item = InventoryItem::findOrFail($request->inventory_item_id);
        $qty = (int) $request->quantity;

        if ($item->stock_quantity < $qty) {
            return back()->with('error', "Insufficient stock! Only {$item->stock_quantity} {$item->unit} available.");
        }

        $recipientName = 'Department';
        if ($request->issued_to_type === 'staff') {
            $st = Staff::find($request->issued_to_id);
            $recipientName = $st ? "{$st->first_name} {$st->last_name} (Staff)" : ($request->issued_to_name ?: 'Staff');
        } elseif ($request->issued_to_type === 'student') {
            $stud = Student::find($request->issued_to_id);
            $recipientName = $stud ? "{$stud->first_name} {$stud->last_name} (Student)" : ($request->issued_to_name ?: 'Student');
        } else {
            $recipientName = $request->department_name ?: 'Science Department';
        }

        $item->decrement('stock_quantity', $qty);

        InventoryIssuedItem::create([
            'inventory_item_id' => $item->id,
            'issued_to_type' => $request->issued_to_type,
            'issued_to_id' => $request->issued_to_id,
            'issued_to_name' => $recipientName,
            'quantity' => $qty,
            'issue_date' => $request->issue_date,
            'status' => 'Issued',
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('school.inventory.issue-item')->with('success', "Issued {$qty} {$item->unit} of '{$item->name}' to {$recipientName}.");
    }

    public function returnItem(Request $request, $id)
    {
        $issued = InventoryIssuedItem::findOrFail($id);
        
        if ($issued->status === 'Returned') {
            return back()->with('info', 'Item has already been returned.');
        }

        $issued->update([
            'status' => 'Returned',
            'return_date' => Carbon::today(),
            'remarks' => $issued->remarks . ' | ' . ($request->remarks ?: 'Returned in good order'),
        ]);

        $issued->item->increment('stock_quantity', $issued->quantity);

        return redirect()->route('school.inventory.issue-item')->with('success', "Item '{$issued->item->name}' returned and stock restored!");
    }

    // =========================================================================
    // 8. POINT OF SALE (POS) & SALES HISTORY
    // =========================================================================
    public function posCreate()
    {
        $items = InventoryItem::where('stock_quantity', '>', 0)->orderBy('name')->get();
        $suppliers = InventorySupplier::where('status', 'Active')->orderBy('name')->get();
        $staffMembers = Staff::orderBy('first_name')->take(30)->get();
        $students = Student::orderBy('first_name')->take(30)->get();

        return view('inventory.pos_create', compact('items', 'suppliers', 'staffMembers', 'students'));
    }

    public function posStore(Request $request)
    {
        $request->validate([
            'billing_mode' => 'required|in:school,vendor',
            'buyer_type' => 'required|string',
            'payment_mode' => 'required|string',
        ]);

        $itemsJson = json_decode($request->cart_data, true);
        if (empty($itemsJson) || !is_array($itemsJson)) {
            return back()->with('error', 'Cart is empty! Please add at least one item.');
        }

        DB::beginTransaction();
        try {
            $invoiceNo = 'INV/POS/' . date('Y') . '/' . str_pad(InventorySale::count() + 1, 5, '0', STR_PAD_LEFT);
            $subtotal = 0;
            $taxTotal = 0;
            $discountTotal = (float) ($request->discount_amount ?? 0);

            $buyerName = $request->buyer_name ?: ($request->buyer_type === 'Walk-in' ? 'Walk-in Customer' : 'Student/Staff Buyer');

            $sale = InventorySale::create([
                'invoice_no' => $invoiceNo,
                'customer_name' => $buyerName,
                'customer_type' => $request->buyer_type,
                'sale_date' => Carbon::today(),
                'total_amount' => 0, // updated below
                'payment_method' => $request->payment_mode,
                'status' => 'Completed',
                'notes' => $request->notes,
                'billing_mode' => $request->billing_mode,
                'vendor_id' => $request->billing_mode === 'vendor' ? $request->vendor_id : null,
                'buyer_type' => $request->buyer_type,
                'buyer_id' => $request->buyer_id,
                'buyer_name' => $buyerName,
                'discount_amount' => $discountTotal,
                'payment_mode' => $request->payment_mode,
                'payment_reference' => $request->payment_reference,
            ]);

            foreach ($itemsJson as $line) {
                $item = InventoryItem::findOrFail($line['item_id']);
                $qty = (int) $line['qty'];
                $price = (float) $line['price'];
                $lineTaxPercent = (float) ($line['tax'] ?? 0);
                $lineTotal = $qty * $price;
                $lineTax = ($lineTotal * $lineTaxPercent) / 100;

                $subtotal += $lineTotal;
                $taxTotal += $lineTax;

                $item->decrement('stock_quantity', $qty);

                InventorySaleItem::create([
                    'inventory_sale_id' => $sale->id,
                    'inventory_item_id' => $item->id,
                    'item_name' => $item->name,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => 0,
                    'tax_percent' => $lineTaxPercent,
                    'line_total' => $lineTotal + $lineTax,
                ]);
            }

            $netAmount = max(0, ($subtotal + $taxTotal) - $discountTotal);

            $sale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'net_amount' => $netAmount,
                'total_amount' => $netAmount,
            ]);

            DB::commit();
            return redirect()->route('school.inventory.sales')->with('success', "Sale invoice {$invoiceNo} recorded successfully! Total: ₹" . number_format($netAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Sale failed: ' . $e->getMessage());
        }
    }

    public function salesHistory(Request $request)
    {
        $query = InventorySale::with('items.item', 'vendor');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('invoice_no', 'like', "%{$s}%")
                  ->orWhere('buyer_name', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%");
            });
        }

        $sales = $query->latest()->paginate(20)->withQueryString();
        return view('inventory.sales_history', compact('sales'));
    }

    public function salesShow($id)
    {
        $sale = InventorySale::with('items.item', 'vendor')->findOrFail($id);
        return view('inventory.sales_show', compact('sale'));
    }

    // =========================================================================
    // 9. PURCHASE ORDERS
    // =========================================================================
    public function purchaseOrdersIndex(Request $request)
    {
        $query = InventoryPurchaseOrder::with('supplier', 'items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->latest()->paginate(15)->withQueryString();
        return view('inventory.purchase_orders', compact('purchaseOrders'));
    }

    public function purchaseOrdersCreate()
    {
        $suppliers = InventorySupplier::where('status', 'Active')->orderBy('name')->get();
        $items = InventoryItem::orderBy('name')->get();
        return view('inventory.purchase_orders_create', compact('suppliers', 'items'));
    }

    public function purchaseOrdersStore(Request $request)
    {
        $request->validate([
            'inventory_supplier_id' => 'required|exists:inventory_suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $poNo = 'PO/YUG/' . date('Y') . '/' . str_pad(InventoryPurchaseOrder::count() + 1, 5, '0', STR_PAD_LEFT);
            $subtotal = 0;
            $taxTotal = 0;

            $po = InventoryPurchaseOrder::create([
                'po_no' => $poNo,
                'inventory_supplier_id' => $request->inventory_supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'status' => 'Draft',
                'terms' => $request->terms,
                'notes' => $request->notes,
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'created_by' => auth()->user()->name ?? 'school admin',
            ]);

            foreach ($request->items as $row) {
                if (empty($row['item_id']) || empty($row['quantity'])) continue;

                $item = InventoryItem::find($row['item_id']);
                $qty = (int) $row['quantity'];
                $cost = (float) ($row['unit_cost'] ?? ($item ? $item->unit_price : 0));
                $gst = (float) ($row['gst_percent'] ?? 0);
                $lineTot = $qty * $cost;
                $tax = ($lineTot * $gst) / 100;

                $subtotal += $lineTot;
                $taxTotal += $tax;

                InventoryPurchaseOrderItem::create([
                    'inventory_purchase_order_id' => $po->id,
                    'inventory_item_id' => $item ? $item->id : null,
                    'item_name' => $item ? $item->name : 'Item',
                    'ordered_quantity' => $qty,
                    'received_quantity' => 0,
                    'unit_cost' => $cost,
                    'gst_percent' => $gst,
                    'line_total' => $lineTot + $tax,
                ]);
            }

            $po->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);

            DB::commit();
            return redirect()->route('school.inventory.purchase-orders.show', $po->id)->with('success', "Purchase Order {$poNo} generated in Draft status!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'PO Generation failed: ' . $e->getMessage());
        }
    }

    public function purchaseOrdersShow($id)
    {
        $po = InventoryPurchaseOrder::with('supplier', 'items.item', 'goodsReceipts.items', 'payments')->findOrFail($id);
        return view('inventory.purchase_orders_show', compact('po'));
    }

    public function purchaseOrdersApprove($id)
    {
        $po = InventoryPurchaseOrder::findOrFail($id);
        $po->update([
            'status' => 'Approved',
            'approved_by' => auth()->user()->name ?? 'school admin',
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->route('school.inventory.purchase-orders.show', $po->id)->with('success', "PO {$po->po_no} approved!");
    }

    public function purchaseOrdersCancel($id)
    {
        $po = InventoryPurchaseOrder::findOrFail($id);
        $po->update(['status' => 'Cancelled']);

        return redirect()->route('school.inventory.purchase-orders.show', $po->id)->with('info', "PO {$po->po_no} has been cancelled.");
    }

    // =========================================================================
    // 10. GOODS RECEIPTS (GRNs)
    // =========================================================================
    public function grnsIndex()
    {
        $grns = InventoryGoodsReceipt::with('purchaseOrder', 'supplier', 'items.item')->latest()->paginate(15);
        return view('inventory.grns', compact('grns'));
    }

    public function grnShow($id)
    {
        $grn = InventoryGoodsReceipt::with('purchaseOrder', 'supplier', 'items.item')->findOrFail($id);
        return view('inventory.grns_show', compact('grn'));
    }

    public function grnReceivePo(Request $request, $poId)
    {
        $po = InventoryPurchaseOrder::with('items.item')->findOrFail($poId);

        DB::beginTransaction();
        try {
            $grnNo = 'GRN/YUG/' . date('Y') . '/' . str_pad(InventoryGoodsReceipt::count() + 1, 5, '0', STR_PAD_LEFT);

            $grn = InventoryGoodsReceipt::create([
                'grn_no' => $grnNo,
                'inventory_purchase_order_id' => $po->id,
                'inventory_supplier_id' => $po->inventory_supplier_id,
                'received_date' => Carbon::today(),
                'received_by' => auth()->user()->name ?? 'school admin',
                'notes' => $request->notes ?: 'Goods received against PO ' . $po->po_no,
            ]);

            foreach ($po->items as $poItem) {
                $qtyToReceive = $poItem->ordered_quantity - $poItem->received_quantity;
                if ($qtyToReceive <= 0) continue;

                $poItem->update([
                    'received_quantity' => $poItem->ordered_quantity,
                ]);

                if ($poItem->item) {
                    $poItem->item->increment('stock_quantity', $qtyToReceive);
                }

                InventoryGoodsReceiptItem::create([
                    'inventory_goods_receipt_id' => $grn->id,
                    'inventory_item_id' => $poItem->inventory_item_id,
                    'item_name' => $poItem->item_name,
                    'received_quantity' => $qtyToReceive,
                    'rejected_quantity' => 0,
                ]);
            }

            $po->update(['status' => 'Received']);

            DB::commit();
            return redirect()->route('school.inventory.grns.show', $grn->id)->with('success', "Goods received! GRN {$grnNo} created and item inventory updated.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Goods receipt failed: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 11. SUPPLIER PAYMENTS
    // =========================================================================
    public function supplierPaymentsIndex()
    {
        $suppliers = InventorySupplier::where('status', 'Active')->orderBy('name')->get();
        $pos = InventoryPurchaseOrder::where('status', 'Received')->orWhere('status', 'Approved')->latest()->get();
        $payments = InventorySupplierPayment::with('supplier', 'purchaseOrder')->latest()->paginate(15);

        return view('inventory.supplier_payments', compact('suppliers', 'pos', 'payments'));
    }

    public function supplierPaymentsStore(Request $request)
    {
        $request->validate([
            'inventory_supplier_id' => 'required|exists:inventory_suppliers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
        ]);

        InventorySupplierPayment::create([
            'inventory_supplier_id' => $request->inventory_supplier_id,
            'inventory_purchase_order_id' => $request->inventory_purchase_order_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_mode' => $request->payment_mode,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'created_by' => auth()->user()->name ?? 'school admin',
        ]);

        return redirect()->route('school.inventory.supplier-payments')->with('success', 'Supplier payment logged successfully!');
    }

    // =========================================================================
    // 12. VENDOR COLLECTIONS, REPORTS, GUIDE
    // =========================================================================
    public function vendorCollections()
    {
        $vendorSales = InventorySale::where('billing_mode', 'vendor')->with('vendor')->latest()->paginate(15);
        $suppliers = InventorySupplier::where('status', 'Active')->get();
        return view('inventory.vendor_collections', compact('vendorSales', 'suppliers'));
    }

    public function reports()
    {
        $categories = InventoryCategory::withCount('items')->get();
        $totalItems = InventoryItem::count();
        $totalStockValue = InventoryItem::select(DB::raw('SUM(stock_quantity * unit_price) as val'))->value('val') ?? 0;
        $totalSalesAmount = InventorySale::sum('net_amount');
        $totalSupplierPayments = InventorySupplierPayment::sum('amount');

        return view('inventory.reports', compact('categories', 'totalItems', 'totalStockValue', 'totalSalesAmount', 'totalSupplierPayments'));
    }

    public function guide()
    {
        return view('inventory.guide');
    }
}

