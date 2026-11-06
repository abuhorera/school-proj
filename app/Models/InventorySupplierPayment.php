<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySupplierPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_supplier_id',
        'inventory_purchase_order_id',
        'amount',
        'payment_date',
        'payment_mode', // Cash, Bank Transfer, Cheque, UPI
        'reference_number',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(InventorySupplier::class, 'inventory_supplier_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(InventoryPurchaseOrder::class, 'inventory_purchase_order_id');
    }
}

