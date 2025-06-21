<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_car',
        'color',
        'monthly_rent',
        'class',
        'car_number',
        'photo',
        'people_number'
    ];
}
