<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_purchase_order_id',
        'inventory_item_id',
        'item_name',
        'ordered_quantity',
        'received_quantity',
        'unit_cost',
        'gst_percent',
        'line_total',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'gst_percent' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(InventoryPurchaseOrder::class, 'inventory_purchase_order_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}

