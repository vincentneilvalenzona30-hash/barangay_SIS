<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ResidentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'civil_status',
        'contact_number',
        'address',
        'purok',
        'years_of_residency',
        'valid_id_type',
        'valid_id_number',
        'valid_id_path',
        'face_photo_path',
        'status',
        'admin_notes',
        'submitted_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'submitted_at' => 'datetime',
    ];

    protected $appends = [
        'valid_id_url',
        'face_photo_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getValidIdUrlAttribute(): string
    {
        return Storage::url($this->valid_id_path);
    }

    public function getFacePhotoUrlAttribute(): string
    {
        return Storage::url($this->face_photo_path);
    }
}
