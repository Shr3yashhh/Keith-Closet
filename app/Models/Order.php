<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_warehouse_id',
        'receiver_warehouse_id',
        'quantity',
        'status',
        'type',
        'username',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function senderWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'sender_warehouse_id');
    }
    public function receiverWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'receiver_warehouse_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
