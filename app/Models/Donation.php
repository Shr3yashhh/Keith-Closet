<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        "full_name",
        "product_id",
        "warehouse_id",
        "quantity",
    ];

    public function product()
    {
        // return $this->belongsTo();
    }
}
