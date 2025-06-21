<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFlightReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_flight_id',
        'user_id',
        'reservation_date',
        'people',
        'room_id',
        'reservation_cost'
        ];
}
