<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qrcode extends Model
{
    use HasFactory;
    protected $table = 'qr_codes';

    protected $fillable = [
        'patient_id',
        'prescription_id',
        'qr_code_value',
    ];

    // Define relationships
    public function patient()
    {
        return $this->belongsTo(patient::class, 'patient_id');
    }

    public function prescription()
    {
        return $this->belongsTo(prescription::class, 'prescription_id');
    }
    public function drugs()
    {
        return $this->belongsToMany(drugs::class);
    }
}
