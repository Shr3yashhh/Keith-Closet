<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sex',
        'size',
        'category',
        'sku',
    ];

    public function productWarehouses(): HasMany
    {
        return $this->hasMany(ProductWarehouse::class);
    }
}
