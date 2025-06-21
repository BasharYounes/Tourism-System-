<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReservatio extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','room_id','reservation_date','from','to','reservation_cost'];

}
