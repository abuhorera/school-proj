<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAssignment extends Model
{
    use HasFactory;

    protected $table = 'asset_assignments';

    protected $fillable = [
        'asset_id',
        'assignable_type',
        'assignable_id',
        'holder_name',
        'assigned_date',
        'expected_return_date',
        'returned_date',
        'condition_on_assign',
        'condition_on_return',
        'status',
        'remarks',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'expected_return_date' => 'date',
        'returned_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(SchoolAsset::class, 'asset_id');
    }
}

