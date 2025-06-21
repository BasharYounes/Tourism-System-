<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiCar extends Model
{
    use HasFactory;
    protected $fillable =[
        'type_car',
        'color',
        'car_number',
        'photo'
    ];
}
