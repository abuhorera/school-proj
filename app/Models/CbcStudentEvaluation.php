<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbcStudentEvaluation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function assessment()
    {
        return $this->belongsTo(CbcAssessment::class, 'cbc_assessment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function outcome()
    {
        return $this->belongsTo(CbcOutcome::class, 'cbc_outcome_id');
    }
}

