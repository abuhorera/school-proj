<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryIssuedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'issued_to_type', // staff, student, department
        'issued_to_id',
        'issued_to_name',
        'quantity',
        'issue_date',
        'return_date',
        'status', // Issued, Returned, Lost, Damaged
        'remarks',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'return_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}

