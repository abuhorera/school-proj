<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceInspectionItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'Done' => 'badge-success',
            'Pending' => 'badge-warning',
            'N/A' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }
}

