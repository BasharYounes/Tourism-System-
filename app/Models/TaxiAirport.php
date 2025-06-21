<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiAirport extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'airport_id',
        'driver_id'
    ];
}
