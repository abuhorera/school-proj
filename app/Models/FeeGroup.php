<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeGroup extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function types()
    {
        return $this->hasMany(FeeType::class);
    }

    public function feeTypes()
    {
        return $this->hasMany(FeeType::class);
    }
}
