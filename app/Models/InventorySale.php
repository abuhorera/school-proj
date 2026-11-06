<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_name',
        'customer_type',
        'sale_date',
        'total_amount',
        'payment_method',
        'status',
        'notes',
        'billing_mode', // school, vendor
        'vendor_id',
        'buyer_type', // Student, Staff, Walk-in
        'buyer_id',
        'buyer_name',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'net_amount',
        'payment_mode',
        'payment_reference',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InventorySaleItem::class, 'inventory_sale_id');
    }

    public function vendor()
    {
        return $this->belongsTo(InventorySupplier::class, 'vendor_id');
    }
}

