<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryGoodsReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_no',
        'inventory_purchase_order_id',
        'inventory_supplier_id',
        'received_date',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(InventoryPurchaseOrder::class, 'inventory_purchase_order_id');
    }

    public function supplier()
    {
        return $this->belongsTo(InventorySupplier::class, 'inventory_supplier_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryGoodsReceiptItem::class, 'inventory_goods_receipt_id');
    }
}

