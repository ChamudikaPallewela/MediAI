<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class drugs extends Model
{
    use HasFactory;
    protected $table = 'drugs';

    protected $fillable = [
        'drug_name',
        'description',
        'expiry_date',
    ];

    public function clinics()
    {
        return $this->belongsToMany(clinics::class, 'medicine_clinic', 'drug_id', 'clinic_id');
    }
}
