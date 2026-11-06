<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'inventory_supplier_id',
        'type', // purchase, opening_stock, adjustment_in, adjustment_out, damaged, expired, recount
        'quantity',
        'unit_cost',
        'total_cost',
        'date',
        'performed_by',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(InventorySupplier::class, 'inventory_supplier_id');
    }
}

