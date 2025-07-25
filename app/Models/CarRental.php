<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRental extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'reservation_date',
        'from',
        'to',
        'cost',
        'user_id'
    ];
}
