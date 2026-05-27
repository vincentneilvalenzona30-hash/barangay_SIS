<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    use HasFactory;

    protected $fillable = ['resident_id', 'incident_type', 'date_of_incident', 'narrative', 'status'];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
