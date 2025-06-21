<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismCountry extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'country',
        'city',
        'photo',
        'photo_dish'
    ];
}
