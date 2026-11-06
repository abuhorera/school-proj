<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'sku',
        'barcode',
        'item_type', // Consumable, Saleable, Asset
        'unit',
        'location',
        'stock_quantity',
        'minimum_stock_level',
        'unit_price',
        'selling_price',
        'expiry_date',
        'description',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function stockLogs()
    {
        return $this->hasMany(InventoryStockLog::class, 'inventory_item_id');
    }

    public function issuedItems()
    {
        return $this->hasMany(InventoryIssuedItem::class, 'inventory_item_id');
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->minimum_stock_level;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}

