<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complete_Flight extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'destination',
        'travel_dates_departure',
        'travel_dates_return',
        'reservation_type',
        'available_place',
        'transport_id',
        'transport_company',
        'price',
        'hotel_id',
        'nights',
        'inclusions',
        'activities',
        'famous',
        'photo'
];
}
