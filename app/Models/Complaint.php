<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $fillable =['comment','user_id'];


    public function replies()
    {
        return $this->hasMany(ComplaintResponse::class);
    }


}
