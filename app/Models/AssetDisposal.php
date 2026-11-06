<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDisposal extends Model
{
    use HasFactory;

    protected $table = 'asset_disposals';

    protected $fillable = [
        'asset_id',
        'disposal_method',
        'disposal_date',
        'realised_value',
        'book_value_at_disposal',
        'gain_loss',
        'recipient',
        'reason',
        'status',
        'requested_by',
        'document_path',
        'remarks',
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'realised_value' => 'float',
        'book_value_at_disposal' => 'float',
        'gain_loss' => 'float',
    ];

    public function asset()
    {
        return $this->belongsTo(SchoolAsset::class, 'asset_id');
    }
}

