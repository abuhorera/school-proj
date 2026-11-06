<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_sale_id',
        'inventory_item_id',
        'item_name',
        'quantity',
        'unit_price',
        'discount',
        'tax_percent',
        'line_total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(InventorySale::class, 'inventory_sale_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}

