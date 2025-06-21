<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteCompleteFlight extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','complete_flight_id'];
}
