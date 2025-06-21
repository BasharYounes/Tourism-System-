<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_Hotel_complete_flight extends Model
{
    use HasFactory;
    protected $fillable = [
        'capacety',
        'active',
        'photo',
        'hotel_id',        
    ];
}
