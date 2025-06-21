<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complete_Flight_Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
    'complete_flight_id',
    'user_id',
    'reservation_date',
    'people',
    'room_id',
    'reservation_cost'
    ];
}
