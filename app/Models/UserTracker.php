<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTracker extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'current_latitude',
        'current_longitude',
        'last_latitude',
        'last_longitude',
        'is_active'
    ];
}
