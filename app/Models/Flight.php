<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;
    protected $fillable = [
        'flight_number',
        'airline',
        'website',
        'departure_airport',
        'departure_time',
        'arrival_airport',
        'arrival_time',
        'duration',
        'reservation_type',
        'price',
        'available_place',
        'transport_id',
        'departure_date'
];
}
