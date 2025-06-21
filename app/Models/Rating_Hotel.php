<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating_Hotel extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','hotel_id','rating','comment'];
}
