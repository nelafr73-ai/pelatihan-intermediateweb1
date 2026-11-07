<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lpk extends Model
{
    use HasFactory;

    protected $table = 'lpks';

    protected $fillable = [
        'name',
        'domain',
        'settings',
        'is_active',

        'lpk_name',
        'legal_name',
        'nib',
        'description',

        'phone',
        'email',
        'website',
        'instagram',
        'facebook',
        'tiktok',

        'province',
        'city',
        'district',
        'address',
        'latitude',
        'longitude',

        'logo',
        'banner',
        'facilities',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'facilities' => 'array',
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
