<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryGoodsReceiptItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_goods_receipt_id',
        'inventory_item_id',
        'item_name',
        'received_quantity',
        'rejected_quantity',
        'rejection_reason',
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(InventoryGoodsReceipt::class, 'inventory_goods_receipt_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}

