<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirLineCash extends Model
{
    use HasFactory;
    protected $fillable = ['airline_name','cash'];
}
