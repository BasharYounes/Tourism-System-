<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel_complete_flight extends Model
{
    use HasFactory;
    protected $fillable = ['name','address','city','country','star_rating','rating_average','photo'];

}
