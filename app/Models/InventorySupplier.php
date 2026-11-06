<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'supplier_type', // General, Uniform Vendor, Book Vendor, Medical, Lab Equipment
        'contact_person',
        'phone',
        'email',
        'gstin',
        'address',
        'status', // Active, Inactive
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(InventoryPurchaseOrder::class, 'inventory_supplier_id');
    }

    public function payments()
    {
        return $this->hasMany(InventorySupplierPayment::class, 'inventory_supplier_id');
    }
}

