<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clinics extends Model
{
    use HasFactory;
    protected $table = 'clinics';

    protected $fillable = [
        'clinic_name',
        
    ];
}
