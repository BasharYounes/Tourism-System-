<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightReservatio extends Model
{
    use HasFactory;
    protected $fillable = ['flight_id','user_id','reservation_date','people'];
}
