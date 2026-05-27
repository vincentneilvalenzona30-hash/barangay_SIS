<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'birth_date',
        'gender', 'civil_status', 'address', 'contact_number'
    ];

    public function blotters()
    {
        return $this->hasMany(Blotter::class);
    }
}
