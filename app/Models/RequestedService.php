<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestedService extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'provider_id',
        'user_latitude',
        'user_longitude',
        'status',
        'is_canceled',
        'is_seen',
        'is_seen_admin',
    ];
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function requestedUser()
    {
        return $this->belongsTo(UserTracker::class, 'user_id', 'user_id');
    }
}
