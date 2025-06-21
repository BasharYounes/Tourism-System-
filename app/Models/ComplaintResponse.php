<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{
    use HasFactory;
    protected $fiilable = ['replied','complaint_id'];

    
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
