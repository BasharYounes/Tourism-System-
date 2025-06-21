<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiReservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'from',
        'to',
        'taxi_airport_id',
        'user_id',
        'date',
        'cost',
    ];
}
