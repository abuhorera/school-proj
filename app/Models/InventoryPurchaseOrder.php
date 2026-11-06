<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_no',
        'inventory_supplier_id',
        'order_date',
        'expected_delivery_date',
        'status', // Draft, Approved, Partially Received, Received, Cancelled
        'terms',
        'notes',
        'subtotal',
        'tax_amount',
        'total_amount',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(InventorySupplier::class, 'inventory_supplier_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryPurchaseOrderItem::class, 'inventory_purchase_order_id');
    }

    public function goodsReceipts()
    {
        return $this->hasMany(InventoryGoodsReceipt::class, 'inventory_purchase_order_id');
    }

    public function payments()
    {
        return $this->hasMany(InventorySupplierPayment::class, 'inventory_purchase_order_id');
    }
}

