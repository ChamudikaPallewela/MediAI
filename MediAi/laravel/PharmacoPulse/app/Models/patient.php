<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    use HasFactory;
    protected $table = 'patients';

    protected $fillable = [
        'patient_name',
        'clinic_id',
        'address',
        'phone_number',
        'id_number',
        'date_of_birth',
    ];

    // Define relationships
    public function clinic()
    {
        return $this->belongsTo(clinics::class, 'clinic_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(prescription::class, 'patient_id');
    }

    public function qrCode()
    {
        return $this->hasOne(qrcode::class, 'patient_id');
    }
}
