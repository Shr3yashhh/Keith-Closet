<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "name",
        "patient_id",
        "document",
        "comment",
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
