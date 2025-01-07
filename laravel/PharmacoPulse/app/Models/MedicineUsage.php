<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'drug_id',
        'usage_date',
        'month',
    ];

    // Define relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function drug()
    {
        return $this->belongsTo(drugs::class);
    }
}
