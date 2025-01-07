<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prescription extends Model
{
    use HasFactory;
    protected $table = 'prescriptions';

    protected $fillable = [
        'patient_id',
        'drug_id',
        'start_date',
        'end_date',
        'dosage',
        'instructions',
    ];

    public function prescription()
    {
        return $this->belongsToMany(prescription::class, 'prescription', 'drug_id', 'patient_id');
    }
    // Define relationships
    public function patient()
    {
        return $this->belongsTo(patient::class, 'patient_id');
    }

    public function drug()
    {
        return $this->belongsTo(drugs::class);
    }

    public function qrCode()
    {
        return $this->hasOne(qrcode::class, 'prescription_id');
    }
}
