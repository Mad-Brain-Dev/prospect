<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppression extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_id',
        'first_name',
        'last_name',
        'address_one',
        'address_two',
        'city',
        'state',
        'zip',
        'zip_plus_four',
        'delivery_point',
        'state_code',
        'county',
        'latitude',
        'longitude',
        'carrier',
        'segment',
        'org_phone',
        'resort',
        'phone_one',
        'phone_two',
    ];

    public function timeshare()
    {
        return $this->belongsTo(Time::class,'time_id');
    }
}
