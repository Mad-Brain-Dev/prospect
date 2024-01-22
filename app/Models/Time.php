<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zip',
        'phone_primary',
        'phone_secondary',
        'count',
        'csv_file_name',
    ];

    public function suppressions()
    {
        return $this->hasMany(Suppression::class,'time_id');
    }
}
